<?php

namespace App\Controllers;

use App\Core\Request;

class SupplierController
{
    public function index()
    {
        abort_if(gate_denies('supplier_access'), '403 Forbidden');

        $pageTitle = "Supplier";
        $branch = $_SESSION['system']['branch_id'];

        $suppliers = DB()->selectLoop("*", "supplier", "branch_id = '$branch' ORDER BY id DESC")->get();

        return view('/supplier/index', compact('pageTitle', 'suppliers'));
    }

    public function store()
    {
        $branch = $_SESSION['system']['branch_id'];

        $request = Request::validate('/supplier', [
            'supplier_name' => ['required']
        ]);

        $response = DB()->insert("supplier", [
            "branch_id" => $branch,
            "name" => $request['supplier_name'],
            "address" => $request['address'],
            "contact" => $request['contact'],
            "tin" => $request['tin'],
            "date" => date('Y-m-d'),
            "status" => 0
        ]);

        if ($response) {
            $message = ["message" => "Added successfully."];
        } else {
            $message = ["message" => "Error adding supplier."];
        }

        return redirect('/supplier', $message);
    }

    public function edit($id)
    {
        abort_if(gate_denies('supplier_edit'), '403 Forbidden');

        $branch = $_SESSION['system']['branch_id'];

        $supplier = DB()->select("*", "supplier", "id = '$id' AND branch_id = '$branch'")->get();

        $data = [
            "id" => $supplier['id'],
            "name" => $supplier['name'],
            "address" => $supplier['address'],
            "contact" => $supplier['contact'],
            "tin" => $supplier['tin'],
            "date" => $supplier['date'],
            "status" => $supplier['status']
        ];

        echo json_encode($data);
    }

    public function update()
    {
        $request = Request::validate('/supplier', [
            'u_supplier_name' => ['required']
        ]);

        $response = DB()->update("supplier", [
            "name" => $request['u_supplier_name'],
            "address" => $request['u_address'],
            "contact" => $request['u_contact'],
            "tin" => $request['u_tin'],
            "date" => date('Y-m-d')
        ], "id = '$request[u_supplier_id]'");

        if ($response) {
            $message = ["message" => "Updated successfully."];
        } else {
            $message = ["message" => "Error updating supplier."];
        }

        return redirect('/supplier', $message);
    }

    public function destroy()
    {
        abort_if(gate_denies('supplier_delete'), '403 Forbidden');

        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("supplier", "id = '$id'");
        }

        return redirect('/supplier', ["message" => "deleted successfully."]);
    }
}
