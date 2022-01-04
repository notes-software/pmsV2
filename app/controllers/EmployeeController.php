<?php

namespace App\Controllers;

use App\Core\Request;

class EmployeeController
{
    public function index()
    {
        abort_if(gate_denies('employee_show'), '403 Forbidden');

        $pageTitle = "Employee";
        $branch = $_SESSION['system']['branch_id'];

        $employees = DB()->selectLoop("*", "employee", "branch_id = '$branch' ORDER BY id DESC")
            ->with([
                "employee_position" => [
                    'position_id',
                    'id'
                ]
            ])
            ->get();

        return view('/employee/index', compact('pageTitle', 'employees'));
    }

    public function create()
    {
        abort_if(gate_denies('employee_create'), '403 Forbidden');

        $pageTitle = "Add Employee";

        $positions = DB()->selectLoop("*", "employee_position", "id > 0 ORDER BY position ASC")->get();

        return view('/employee/create', compact('pageTitle', 'positions'));
    }

    public function store()
    {
        $request = Request::validate('/employee/create', [
            'employee_number' => ['required'],
            'employee_fullname' => ['required'],
            'employee_address' => ['required'],
            'employee_date_hired' => ['required']
        ]);

        $payload = [
            "branch_id" => $_SESSION['system']['branch_id'],
            "ref_code" => $request['employee_number'],
            "fullname" => $request['employee_fullname'],
            "address" => $request['employee_address'],
            "contact" => $request['employee_contact'],
            "datehired" => $request['employee_date_hired'],
            "position_id" => $request['employee_position'],
            "status" => 0
        ];

        DB()->insert("employee", $payload);

        return redirect('/employee', ["message" => "Added successfully."]);
    }

    public function edit($id)
    {
        abort_if(gate_denies('employee_edit'), '403 Forbidden');

        $pageTitle = "Employee Detail";

        $employee = DB()->select("*", "employee", "id = '$id'")
            ->with([
                "employee_position" => [
                    'position_id',
                    'id'
                ]
            ])
            ->get();

        $positions = DB()->selectLoop("*", "employee_position", "id > 0 ORDER BY position ASC")->get();

        return view('/employee/edit', compact('pageTitle', 'employee', 'positions'));
    }

    public function update($id)
    {
        $request = Request::validate('/employee/view/' . $id, [
            'employee_number' => ['required'],
            'employee_fullname' => ['required'],
            'employee_address' => ['required'],
            'employee_date_hired' => ['required']
        ]);

        $payload = [
            "ref_code" => $request['employee_number'],
            "fullname" => $request['employee_fullname'],
            "address" => $request['employee_address'],
            "contact" => $request['employee_contact'],
            "datehired" => $request['employee_date_hired'],
            "position_id" => $request['employee_position'],
            "status" => 0
        ];

        DB()->update("employee", $payload, "id = '$id'");

        return redirect('/employee/view/' . $id, ["message" => "Updated successfully."]);
    }

    public function destroy()
    {
        abort_if(gate_denies('employee_delete'), '403 Forbidden');

        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("employee", "id = '$id'");
        }

        return redirect('/employee', ["message" => "deleted successfully."]);
    }

    public function positionIndex()
    {
        abort_if(gate_denies('employee_positon_access'), '403 Forbidden');

        $pageTitle = "Employee Position";

        $positions = DB()->selectLoop("*", "employee_position", "id > 0 ORDER BY id DESC")->get();

        return view('/employee/position', compact('pageTitle', 'positions'));
    }

    public function positionStore()
    {
        $request = Request::validate('/employee/position', [
            'position_name' => ['required']
        ]);

        DB()->insert("employee_position", [
            "position" => $request['position_name']
        ]);

        return redirect('/employee/position', ["message" => "Added successfully."]);
    }

    public function positionEdit($id)
    {
        $positions = DB()->select("*", "employee_position", "id = '$id'")->get();

        $data = [
            "id" => $positions['id'],
            "position" => $positions['position']
        ];

        echo json_encode($data);
    }

    public function positionUpdate()
    {
        $request = Request::validate('/employee/position', [
            'u_position_id' => ['required'],
            'u_position_name' => ['required']
        ]);

        DB()->update("employee_position", [
            "position" => $request['u_position_name']
        ], "id = '$request[u_position_id]'");

        return redirect('/employee/position', ["message" => "Updated successfully."]);
    }

    public function positionDestroy()
    {
        foreach ($_REQUEST['id'] as $id) {
            DB()->delete("employee_position", "id = '$id'");
        }

        return redirect('/employee/position', ["message" => "deleted successfully."]);
    }
}
