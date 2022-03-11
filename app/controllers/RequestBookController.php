<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;

class RequestBookController
{
	public function index()
	{
		// abort_if(gate_denies('branch_access'), 403);

		$pageTitle = "Request Book";

		$requests = DB()->selectLoop("*", "request_logs", "status != 2 ORDER BY request_id DESC")
			->with([
				'users' => ['person_assigned', 'id']
			])
			->get();

		return view('/requestbook/index', compact('pageTitle', 'requests'));
	}

	public function save()
	{
		$request = Request::validate('/requestbook');

		$user_id = Auth::user('id');
		$requestedBy = $request['requestedBy'];
		$description = htmlentities(addslashes($request['description']));

		$data = array(
			'request_date' => date("Y-m-d H:i:s"),
			'logs' => $description,
			'requested_by' => $requestedBy,
			'person_assigned' => $user_id
		);

		$res = DB()->insert("request_logs", $data);
		echo $res;
	}

	public function delete()
	{
		$request = Request::validate('/requestbook');
		$request_id = $request['id'];

		$res = DB()->delete("request_logs", "request_id = '$request_id'");
		echo $res;
	}

	public function approve()
	{
		$request = Request::validate('/requestbook');

		$user_id = Auth::user('id');
		$request_id = $request['request_id'];
		$rb_remarks = htmlentities(addslashes($request['rb_remarks']));

		$data = array(
			'approved_by' => $user_id,
			'status' => 1,
			'approve_date' => date("Y-m-d H:i:s"),
			'remarks' => $rb_remarks
		);

		$res = DB()->update("request_logs", $data, "request_id = '$request_id'");
		echo $res;
	}

	public function badgeCounter()
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$totalCounter = totalRequestBook();
		$last_line_rb_counter = $totalCounter;
		if ($last_line_rb_counter != $_SESSION['rb']['last_rb_counter']) {
			echo "data: {$totalCounter}\n\n";
			$_SESSION['rb']['last_rb_counter'] = $last_line_rb_counter;
		}

		//echo "data:", json_encode($c) , "\n\n";
		echo "retry: 15000\n";
		ob_flush();
		flush();
	}








	public function store()
	{
		abort_if(gate_denies('branch_create'), 403);

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
		abort_if(gate_denies('branch_edit'), 403);

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
		abort_if(gate_denies('branch_delete'), 403);

		foreach ($_REQUEST['id'] as $id) {
			DB()->delete("branch", "id = '$id'");
		}

		return redirect('/branch', ["message" => "deleted successfully."]);
	}
}
