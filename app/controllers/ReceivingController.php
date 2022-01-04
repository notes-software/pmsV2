<?php

namespace App\Controllers;

use App\Core\Request;

class ReceivingController
{
    public function index()
    {
        abort_if(gate_denies('receiving_access'), '403 Forbidden');

        $pageTitle = "Receiving";
        $branch = $_SESSION['system']['branch_id'];

        $branches = DB()->selectLoop("*", "branch", "id > 0 ORDER BY id DESC")->get();

        $rrData = DB()->selectLoop("*", "receiving_header", "branch = '$branch' ORDER BY status ASC, id DESC")
            ->with([
                "receiving_detail" => [
                    'id',
                    'receiving_header_id'
                ],
                "supplier" => [
                    'supplier_id',
                    'id'
                ],
                "purchase_header" => [
                    'po_header_id',
                    'id'
                ]
            ])
            ->get();

        // dd($rrData);

        return view('/receiving/index', compact('pageTitle', 'rrData', 'branches'));
    }

    public function create()
    {
        abort_if(gate_denies('receiving_create'), '403 Forbidden');

        $pageTitle = "New Receiving";
        $branch = $_SESSION['system']['branch_id'];
        $refCode = "RR" . $branch . date('ymdhis');

        $suppliers = DB()->selectLoop("*", "supplier", "id > 0 AND branch_id ='$branch' ORDER BY id DESC")->get();

        $products = DB()->selectLoop("*", "products", "id > 0 ORDER BY id DESC")
            ->with([
                "branch" => [
                    'branch',
                    'id'
                ],
                "product_category" => [
                    'category',
                    'id'
                ]
            ])
            ->get();

        $outStandingPo = DB()->selectLoop("*", "purchase_header", "status = 1 ORDER BY id DESC")
            ->andFilter([
                "purchase_detail" => "AND status = 0"
            ])
            ->withCount([
                "purchase_detail" => ['id', 'po_header_id']
            ])
            ->get();

        $units = DB()->selectLoop("*", "product_unit", "id > 0 ORDER BY id DESC")->get();

        return view('/receiving/create', compact('pageTitle', 'suppliers', 'refCode', 'units', 'products', 'outStandingPo'));
    }

    public function displayReceivedItems()
    {
        $headerId = $_REQUEST['rr_header_id'];
        $branch = $_SESSION['system']['branch_id'];

        $details = DB()->selectLoop("*", "receiving_detail", "receiving_header_id = '$headerId' ORDER BY id DESC")
            ->with([
                "products" => [
                    'product_id',
                    'id'
                ],
                "product_unit" => [
                    'unit',
                    'id'
                ]
            ])
            ->get();

        $header = DB()->select("*", "receiving_header", "id = '$headerId' AND branch = '$branch'")
            ->with([
                "supplier" => [
                    'supplier_id',
                    'id'
                ]
            ])
            ->get();

        $response['data'] = [];
        $data = [];
        foreach ($details as $detail) {
            $data = [
                "id" => $detail['id'],
                "product" => (empty($detail['products'][0])) ? $detail['products']['name'] : $detail['products'][0]['name'],
                "unit" => (empty($detail['product_unit'][0])) ? $detail['product_unit']['name'] : $detail['product_unit'][0]['name'],
                "qty" => $detail['qty'],
                "supplier" => $header['supplier']['name'],
                "cost" => $detail['supplier_price'],
            ];

            array_push($response['data'], $data);
        }

        echo json_encode($response);
    }

    public function receiveItem()
    {
        $branch = $_SESSION['system']['branch_id'];

        $request = Request::validate('', [
            'ref_code' => ['required'],
            'date' => ['required'],
            'po_header_id' => ['required'],
            'po_detail_id' => ['required']
        ]);

        if (empty($request['validationError'])) {

            $po_header = DB()->select("*", "purchase_header", "id = '$request[po_header_id]'")->get();
            $po_detail = DB()->select("*", "purchase_detail", "id = '$request[po_detail_id]'")->get();

            $isExist = DB()->select("*", "receiving_header", "ref_code = '$request[ref_code]' AND branch = '$branch'")->get();

            if ($isExist) {
                $insertToHeader =  $isExist['id'];
            } else {
                $insertToHeader = DB()->insert("receiving_header", [
                    "branch" => $branch,
                    "ref_code" => $request['ref_code'],
                    "po_header_id" => $request['po_header_id'],
                    "supplier_id" => $po_header['supplier_id'],
                    "remarks" => $request['remarks'],
                    "date" => $request['date']
                ], "Y");
            }

            DB()->update("purchase_detail", [
                "status" => 1
            ], "id = '$request[po_detail_id]'");

            $response = DB()->insert("receiving_detail", [
                "branch" => $branch,
                "product_id" => $po_detail['product'],
                "receiving_header_id" => $insertToHeader,
                "po_detail_id" => $request['po_detail_id'],
                "supplier_price" => $po_detail['price'],
                "unit" => $po_detail['unit'],
                "qty" => $po_detail['qty']
            ]);

            echo $insertToHeader;
        } else {
            echo json_encode($request['validationError']);
        }
    }

    public function displayPoItems()
    {
        $headerId = $_REQUEST['po_header_id'];
        $branch = $_SESSION['system']['branch_id'];

        $details = DB()->selectLoop("*", "purchase_detail", "po_header_id = '$headerId' AND status != 1 ORDER BY id DESC")
            ->with([
                "products" => [
                    'product',
                    'id'
                ],
                "product_unit" => [
                    'unit',
                    'id'
                ]
            ])
            ->get();

        $header = DB()->select("*", "purchase_header", "id = '$headerId' AND branch = '$branch'")
            ->with([
                "supplier" => [
                    'supplier_id',
                    'id'
                ]
            ])
            ->get();

        $response['data'] = [];
        $data = [];
        foreach ($details as $detail) {
            $data = [
                "id" => $detail['id'],
                "product" => (empty($detail['products'][0])) ? $detail['products']['name'] : $detail['products'][0]['name'],
                "unit" => (empty($detail['product_unit'][0])) ? $detail['product_unit']['name'] : $detail['product_unit'][0]['name'],
                "qty" => $detail['qty'],
                "supplier" => $header['supplier']['name'],
                "cost" => $detail['price'],
            ];

            array_push($response['data'], $data);
        }

        echo json_encode($response);
    }

    public function removeReceivedItem()
    {
        $branch = $_SESSION['system']['branch_id'];

        $rcvng = DB()->select("*", "receiving_detail", "id = '$_REQUEST[rr_detail_id]' AND branch = '$branch'")->get();

        DB()->update("purchase_detail", [
            "status" => 0
        ], "id = '$rcvng[po_detail_id]'");

        $response = DB()->delete("receiving_detail", "id = '$_REQUEST[rr_detail_id]' AND branch = '$branch'");
        echo $response;
    }

    public function edit($id)
    {
        abort_if(gate_denies('receiving_edit'), '403 Forbidden');

        $pageTitle = "Receiving Detail";
        $branch = $_SESSION['system']['branch_id'];

        $rrData = DB()->select("*", "receiving_header", "id = '$id' AND branch = '$branch'")
            ->with([
                "receiving_detail" => ['id', 'receiving_header_id'],
                "supplier" => ['supplier_id', 'id'],
            ])
            ->get();

        $outStandingPo = DB()->select("*", "purchase_header", "id = '$rrData[po_header_id]' AND status = 1 ORDER BY id DESC")->get();

        return view('/receiving/edit', compact('pageTitle', 'rrData', 'outStandingPo'));
    }

    public function finish()
    {
        $branch = $_SESSION['system']['branch_id'];

        $response = DB()->update("receiving_header", [
            "finish_date" => date('Y-m-d'),
            "status" => 1
        ], "id = '$_REQUEST[rr_id]' AND branch = '$branch'");

        echo $response;
    }

    public function rrPrint($id)
    {
        $branch = $_SESSION['system']['branch_id'];

        $rrData = DB()->select("*", "receiving_header", "id = '$id' AND branch = '$branch'")
            ->with([
                "receiving_detail" => ['id', 'receiving_header_id'],
                "supplier" => ['supplier_id', 'id'],
                "purchase_header" => ['po_header_id', 'id']
            ])
            ->get();

        return view('/receiving/print-rr', compact('rrData'));
    }
}
