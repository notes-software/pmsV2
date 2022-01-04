<?php

namespace App\Controllers;

use App\Core\Request;

class RequestBookController
{
	public function index()
	{
		// abort_if(gate_denies('branch_access'), '403 Forbidden');

		$pageTitle = "Request Book";

		$requests = DB()->selectLoop("*", "request_logs", "request_id > 0 ORDER BY request_id DESC")->get();

		return view('/requestbook/index', compact('pageTitle', 'requests'));
	}

	public function store()
	{
		abort_if(gate_denies('branch_create'), '403 Forbidden');

		$request = Request::validate('/branch', [
			'branch_name' => ['required']
		]);

		DB()->insert("branch", [
			"name" => $request['branch_name'],
			"status" => 0
		]);

		return redirect('/branch', ["message" => "Added successfully."]);
	}

	public function edit($id)
	{
		abort_if(gate_denies('branch_edit'), '403 Forbidden');

		$pageTitle = "Branch";

		$branch = DB()->select("*", "branch", "id = '$id'")->get();

		$data = [
			"id" => $branch['id'],
			"name" => $branch['name'],
			"status" => $branch['status']
		];

		echo json_encode($data);
	}

	public function update()
	{
		$request = Request::validate('/branch', [
			'u_branch_id' => ['required'],
			'u_branch_name' => ['required']
		]);

		DB()->update("branch", [
			"name" => $request['u_branch_name']
		], "id = '$request[u_branch_id]'");

		return redirect('/branch', ["message" => "Updated successfully."]);
	}

	public function destroy()
	{
		abort_if(gate_denies('branch_delete'), '403 Forbidden');

		foreach ($_REQUEST['id'] as $id) {
			DB()->delete("branch", "id = '$id'");
		}

		return redirect('/branch', ["message" => "deleted successfully."]);
	}
}
