<?php

namespace App\Controllers;

use App\Core\Request;

class ProductCategoryController
{
    public function index()
    {
        abort_if(gate_denies('product_category_access'), '403 Forbidden');

        $pageTitle = "Product Category";

        $prodCategories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY id DESC")->get();

        return view('/product/category/index', compact('pageTitle', 'prodCategories'));
    }

    public function store()
    {
        $request = Request::validate('/product/category', [
            'category_name' => ['required']
        ]);

        DB()->insert("product_category", [
            "name" => $request['category_name'],
            "status" => 0
        ]);

        return redirect('/product/category', ["message" => "Added successfully."]);
    }

    public function edit($id)
    {
        $category = DB()->select("*", "product_category", "id = '$id'")->get();

        $data = [
            "id" => $category['id'],
            "name" => $category['name'],
            "status" => $category['status']
        ];

        echo json_encode($data);
    }

    public function update()
    {
        $request = Request::validate('/product/category', [
            'edit_category_id' => ['required'],
            'edit_category_name' => ['required']
        ]);

        DB()->update("product_category", [
            "name" => $request['edit_category_name']
        ], "id = '$request[edit_category_id]'");

        return redirect('/product/category', ["message" => "Updated successfully."]);
    }

    public function destroy()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("product_category", "id = '$id'");
        }

        return redirect('/product/category', ["message" => "deleted successfully."]);
    }
}
