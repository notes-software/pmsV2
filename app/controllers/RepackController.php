<?php

namespace App\Controllers;

use App\Core\Request;
use App\Controllers\InventoryController;

class RepackController
{
    public function index()
    {
        abort_if(gate_denies('repack_access'), '403 Forbidden');

        $pageTitle = "Product Repack";
        $branch = $_SESSION['system']['branch_id'];

        $repacks = DB()->selectLoop("*", "product_repack", "branch = '$branch' ORDER BY status ASC, id DESC")
            ->with([
                "branch" => [
                    'branch',
                    'id'
                ],
                "products" => [
                    'from_item',
                    'id'
                ]
            ])
            ->get();

        return view('/repack/index', compact('pageTitle', 'repacks'));
    }

    public function create()
    {
        abort_if(gate_denies('repack_create'), '403 Forbidden');

        $pageTitle = "Add Repack";
        $branch = $_SESSION['system']['branch_id'];
        $refCode = "RPCK" . $branch . date('ymdhis');

        $categories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY name ASC")->get();

        $products = DB()->selectLoop("*", "products", "branch = '$branch' ORDER BY id DESC")
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

        return view('/repack/create', compact('pageTitle', 'categories', 'products', 'refCode'));
    }

    public function store()
    {
        $branch = $_SESSION['system']['branch_id'];

        $request = Request::validate('', [
            'ref_code' => ['required'],
            'date' => ['required'],
            'original_product' => ['required'],
            'original_unit' => ['required'],
            'original_qty' => ['required'],
            'to_unit' => ['required'],
            'to_qty' => ['required']
        ]);

        $data = [];
        if (empty($request['validationError'])) {
            $currentInv = (new InventoryController)->getInvPerUnit($request['date'], $branch, $request['original_product'], $request['original_unit']);
            $itemBalance = $currentInv - $request['original_qty'];
            if ($itemBalance > 0) {
                $response = DB()->insert("product_repack", [
                    "ref_code" => $request['ref_code'],
                    "branch" => $branch,
                    "from_item" => $request['original_product'],
                    "from_unit" => $request['original_unit'],
                    "from_qty" => $request['original_qty'],
                    "to_item" => $request['original_product'],
                    "to_unit" => $request['to_unit'],
                    "to_qty" => $request['to_qty'],
                    "remarks" => $request['remarks'],
                    "date" => date('Y-m-d',  strtotime($request['date'])),
                    "status" => 1
                ]);

                $data['id'] = $response;
            } else {
                $error = ["original_qty" => "Insufficient inventory quantity for original item."];
                $_SESSION["RESPONSE_MSG"] = $error;
                $data['validationError'] = $error;
            }
        } else {
            $data['validationError'] = $request['validationError'];
        }

        echo json_encode($data);
    }

    public function computeQty()
    {
        $original_unit_qty = $this->getUnitQty($_REQUEST['original_unit']);
        $original_qty = $_REQUEST['original_qty'];
        $to_unit_qty = $this->getUnitQty($_REQUEST['to_unit']);

        echo ($original_qty * $original_unit_qty) / $to_unit_qty;
    }

    public function getUnitQty($id)
    {
        $response = DB()->select("qty", "product_unit", "id = '$id'")->get();
        return $response['qty'];
    }

    public function edit($id)
    {
        abort_if(gate_denies('repack_edit'), '403 Forbidden');

        $pageTitle = "Repack Detail";
        $branch = $_SESSION['system']['branch_id'];
        $refCode = "RPCK" . $branch . date('ymdhis');

        $categories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY name ASC")->get();

        $products = DB()->selectLoop("*", "products", "branch = '$branch' ORDER BY id DESC")
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

        $repacks = DB()->select("*", "product_repack", "id = '$id' AND branch = '$branch' ORDER BY status ASC, id DESC")
            ->with([
                "branch" => [
                    'branch',
                    'id'
                ],
                "products" => [
                    'from_item',
                    'id'
                ]
            ])
            ->get();

        return view('/repack/edit', compact('pageTitle', 'categories', 'products', 'refCode', 'repacks'));
    }

    public function update()
    {
        $branch = $_SESSION['system']['branch_id'];

        $request = Request::validate('', [
            'ref_code' => ['required'],
            'date' => ['required'],
            'original_product' => ['required'],
            'original_unit' => ['required'],
            'original_qty' => ['required'],
            'to_unit' => ['required'],
            'to_qty' => ['required'],
            'rpck_id' => ['required']
        ]);

        $data = [];
        if (empty($request['validationError'])) {
            $currentInv = (new InventoryController)->getInvPerUnit($request['date'], $branch, $request['original_product'], $request['original_unit']);
            $itemBalance = $currentInv - $request['original_qty'];
            if ($itemBalance > 0) {
                $response = DB()->update("product_repack", [
                    "from_item" => $request['original_product'],
                    "from_unit" => $request['original_unit'],
                    "from_qty" => $request['original_qty'],
                    "to_item" => $request['original_product'],
                    "to_unit" => $request['to_unit'],
                    "to_qty" => $request['to_qty'],
                    "remarks" => $request['remarks'],
                    "date" => date('Y-m-d',  strtotime($request['date'])),
                    "status" => 1
                ], "id = '$request[rpck_id]'");

                $data['id'] = $response;
            } else {
                $error = ["original_qty" => "Insufficient inventory quantity for original item."];
                $_SESSION["RESPONSE_MSG"] = $error;
                $data['validationError'] = $error;
            }
        } else {
            $data['validationError'] = $request['validationError'];
        }

        echo json_encode($data);
    }
}
