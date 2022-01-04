<?php

namespace App\Controllers;

use App\Core\Request;

class ProductUnitController
{
    public function index()
    {
        abort_if(gate_denies('product_unit_access'), '403 Forbidden');

        $pageTitle = "Product Unit";

        $prodCategories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY id DESC")->get();

        $prodUnits = DB()->selectLoop("*", "product_unit", "id > 0 ORDER BY id DESC")
            ->with([
                "product_category" => [
                    'category',
                    'id'
                ]
            ])
            ->get();

        return view('/product/unit/index', compact('pageTitle', 'prodUnits', 'prodCategories'));
    }

    public function store()
    {
        $request = Request::validate('/product/unit', [
            'unit_category' => ['required'],
            'unit_name' => ['required'],
            'unit_qty' => ['required']
        ]);

        DB()->insert("product_unit", [
            "category" => $request['unit_category'],
            "name" => $request['unit_name'],
            "qty" => $request['unit_qty'],
            "status" => 0
        ]);

        return redirect('/product/unit', ["message" => "Added successfully."]);
    }

    public function edit($id)
    {
        $category = DB()->select("*", "product_unit", "id = '$id'")->get();

        $data = [
            "id" => $category['id'],
            "category" => $category['category'],
            "name" => $category['name'],
            "qty" => $category['qty'],
            "status" => $category['status']
        ];

        echo json_encode($data);
    }

    public function update()
    {
        $request = Request::validate('/product/unit', [
            'edit_unit_id' => ['required'],
            'edit_unit_category' => ['required'],
            'edit_unit_name' => ['required'],
            'edit_unit_qty' => ['required']
        ]);

        DB()->update("product_unit", [
            "category" => $request['edit_unit_category'],
            "name" => $request['edit_unit_name'],
            "qty" => $request['edit_unit_qty']
        ], "id = '$request[edit_unit_id]'");

        return redirect('/product/unit', ["message" => "Updated successfully."]);
    }

    public function destroy()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("product_unit", "id = '$id'");
        }

        return redirect('/product/unit', ["message" => "deleted successfully."]);
    }
}
