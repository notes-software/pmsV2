<?php

namespace App\Controllers;

use App\Core\Request;

class ProductController
{
    public function index()
    {
        abort_if(gate_denies('product_access'), '403 Forbidden');

        $pageTitle = "Products";
        $branch = $_SESSION['system']['branch_id'];

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

        return view('/product/index', compact('pageTitle', 'products'));
    }

    public function create()
    {
        abort_if(gate_denies('product_create'), '403 Forbidden');

        $pageTitle = "Add Product";

        $categories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY name ASC")->get();

        return view('/product/create', compact('pageTitle', 'categories'));
    }

    public function store()
    {
        $request = Request::validate('/product/create', [
            'barcode' => ['required'],
            'name' => ['required'],
            'category' => ['required']
        ]);

        $payload = [
            "code" => $request['barcode'],
            "category" => $request['category'],
            "name" => $request['name'],
            "branch" => $_SESSION['system']['branch_id'],
            "status" => 0,
            "selling_price" => $request['selling_price']
        ];

        DB()->insert("products", $payload);

        return redirect('/product/create', ["message" => "Added successfully."]);
    }

    public function edit($id)
    {
        abort_if(gate_denies('product_edit'), '403 Forbidden');

        $pageTitle = "Product Detail";

        $product = DB()->select("*", "products", "id = '$id'")->get();

        $categories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY name ASC")->get();

        return view('/product/edit', compact('pageTitle', 'categories', 'product'));
    }

    public function update($id)
    {
        $request = Request::validate('/product/view/' . $id, [
            'barcode' => ['required'],
            'name' => ['required'],
            'category' => ['required']
        ]);

        $payload = [
            "code" => $request['barcode'],
            "category" => $request['category'],
            "name" => $request['name'],
            "selling_price" => $request['selling_price']
        ];

        DB()->update("products", $payload, "id = '$id'");

        return redirect('/product/view/' . $id, ["message" => "Updated successfully."]);
    }

    public function destroy()
    {
        abort_if(gate_denies('product_delete'), '403 Forbidden');

        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("products", "id = '$id'");
        }

        return redirect('/product', ["message" => "deleted successfully."]);
    }
}
