<?php

namespace App\Controllers;

use App\Core\Request;

class PurchaseController
{
    public function index()
    {
        abort_if(gate_denies('purchase_access'), '403 Forbidden');

        $pageTitle = "Purchase";
        $branch = $_SESSION['system']['branch_id'];

        $branches = DB()->selectLoop("*", "branch", "id > 0 ORDER BY id DESC")->get();

        $purchases = DB()->selectLoop("*", "purchase_header", "branch = '$branch' ORDER BY status ASC, id DESC")
            ->with([
                "purchase_detail" => [
                    'id',
                    'po_header_id'
                ],
                "supplier" => [
                    'supplier_id',
                    'id'
                ]
            ])
            ->get();

        return view('/purchase/index', compact('pageTitle', 'purchases', 'branches'));
    }

    public function create()
    {
        abort_if(gate_denies('purchase_create'), '403 Forbidden');

        $pageTitle = "New Purchase";
        $branch = $_SESSION['system']['branch_id'];
        $refCode = "PO" . $branch . date('ymdhis');

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

        $units = DB()->selectLoop("*", "product_unit", "id > 0 ORDER BY id DESC")->get();

        return view('/purchase/create', compact('pageTitle', 'suppliers', 'refCode', 'units', 'products'));
    }

    public function store()
    {
        $branch = $_SESSION['system']['branch_id'];

        $request = Request::validate('', [
            'ref_code' => ['required'],
            'supplier' => ['required'],
            'po_date' => ['required']
        ]);

        $data = [];
        if (empty($request['validationError'])) {
            $curId = DB()->insert("purchase_header", [
                "branch" => $branch,
                "supplier_id" => $request['supplier'],
                "ref_code" => $request['ref_code'],
                "remarks" => $request['remarks'],
                "date" => $request['po_date'],
                "status" => 0
            ], "Y");

            $data['id'] = $curId;
        } else {
            $data['validationError'] = $request['validationError'];
        }

        echo json_encode($data);
    }

    public function addItem()
    {
        $branch = $_SESSION['system']['branch_id'];

        $request = Request::validate('', [
            'po_header_id' => ['required'],
            'product' => ['required'],
            'unit' => ['required'],
            'qty' => ['required'],
            'price' => ['required'],
        ]);

        $data = [];
        if (empty($request['validationError'])) {

            $response = DB()->insert("purchase_detail", [
                "po_header_id" => $request['po_header_id'],
                "product" => $request['product'],
                "unit" => $request['unit'],
                "qty" => $request['qty'],
                "price" => $request['price']
            ]);

            $data['id'] = $response;
        } else {
            $data['validationError'] = $request['validationError'];
        }

        echo json_encode($data);
    }

    public function displayItems()
    {
        $headerId = $_REQUEST['po_header_id'];

        $details = DB()->selectLoop("*", "purchase_detail", "po_header_id = '$headerId' ORDER BY id DESC")
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

        $response['data'] = [];
        $data = [];
        foreach ($details as $detail) {
            $data = [
                "id" => $detail['id'],
                "product" => (empty($detail['products'][0])) ? $detail['products']['name'] : $detail['products'][0]['name'],
                "unit" => (empty($detail['product_unit'][0])) ? $detail['product_unit']['name'] : $detail['product_unit'][0]['name'],
                "qty" => $detail['qty'],
                "cost" => $detail['price'],
            ];

            array_push($response['data'], $data);
        }

        echo json_encode($response);
    }

    public function edit($id)
    {
        abort_if(gate_denies('purchase_edit'), '403 Forbidden');

        $pageTitle = "Purchase Detail";
        $branch = $_SESSION['system']['branch_id'];

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

        $purchase = DB()->select("*", "purchase_header", "id = '$id' AND branch = '$branch'")
            ->with([
                "purchase_detail" => [
                    'id',
                    'po_header_id'
                ],
                "supplier" => [
                    'supplier_id',
                    'id'
                ]
            ])
            ->get();

        $units = DB()->selectLoop("*", "product_unit", "id > 0 ORDER BY id DESC")->get();

        return view('/purchase/edit', compact('pageTitle', 'suppliers', 'units', 'products', 'purchase'));
    }

    public function update()
    {
        $request = Request::validate('', [
            'ref_code' => ['required'],
            'supplier' => ['required'],
            'po_date' => ['required']
        ]);

        if (empty($request['validationError'])) {
            DB()->update("purchase_header", [
                "supplier_id" => $request['supplier'],
                "remarks" => $request['remarks'],
                "date" => $request['po_date'],
            ], "id = '$request[po_header_id]'");

            echo 1;
        } else {
            echo json_encode($request['validationError']);
        }
    }

    public function destroy()
    {
        abort_if(gate_denies('purchase_delete'), '403 Forbidden');

        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("product_unit", "id = '$id'");
        }

        return redirect('/product/unit', ["message" => "deleted successfully."]);
    }

    public function selectUnit()
    {
        $data = "";
        $data .= "<option value=''>-- select unit --</option>";

        $productId = $_REQUEST['product'];
        $product = DB()->select("*", "products", "id = '$productId'")->get();
        $units = DB()->selectLoop("*", "product_unit", "category = '$product[category]'")->get();

        foreach ($units as $unit) {
            $data .= "<option value='" . $unit['id'] . "'>" . $unit['name'] . "</option>";
        }

        echo $data;
    }

    public function deleteItem()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("purchase_detail", "id = '$id' AND po_header_id = '$_REQUEST[po_header_id]'");
        }

        echo 1;
    }

    public function poPrint($id)
    {
        $branch = $_SESSION['system']['branch_id'];

        $purchase = DB()->select("*", "purchase_header", "id = '$id' AND branch = '$branch'")
            ->with([
                "purchase_detail" => [
                    'id',
                    'po_header_id'
                ],
                "supplier" => [
                    'supplier_id',
                    'id'
                ]
            ])
            ->get();

        return view('/purchase/print-po', compact('purchase'));
    }

    public function finish()
    {
        $branch = $_SESSION['system']['branch_id'];

        $response = DB()->update("purchase_header", [
            "finish_date" => date('Y-m-d'),
            "status" => 1
        ], "id = '$_REQUEST[poHeaderId]' AND branch = '$branch'");

        echo $response;
    }
}
