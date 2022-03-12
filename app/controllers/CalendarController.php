<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;
use PDO;

class CalendarController
{
	public function index()
	{
		// abort_if(gate_denies('branch_access'), 403);

		$pageTitle = "My Calendar";
		$user_id = Auth::user('id');

		$requests = DB()->selectLoop("*", "request_logs", "request_id > 0 ORDER BY request_id DESC")->get();

		$projects = DB()->selectLoop("t1.CODE as project_code", "(SELECT pm.projectCode AS CODE FROM project_member AS pm, team_member AS tm WHERE pm.teamCode = tm.teamCode AND tm.user_id = '$user_id' AND pm.type = 1 GROUP BY pm.projectCode UNION ALL SELECT pjm.projectCode AS CODE FROM project_member AS pjm, projects AS p WHERE p.projectCode = pjm.projectCode AND pjm.user_id = '$user_id' AND pjm.type = 0 UNION ALL SELECT prj.projectCode as CODE FROM projects as prj WHERE prj.proj_pm = '$user_id') AS t1", "t1.CODE != '' GROUP BY t1.CODE")
			->with([
				'projects' => ['project_code', 'projectCode']
			])
			->get();

		return view('/calendar/index', compact('pageTitle', 'requests', 'projects'));
	}

	public function tasks()
	{
		$user_id = Auth::user('id');

		$data = [];
		$tasks = DB()->selectLoop("*", "task_member", "user_id = '$user_id'")
			->andFilter([
				"tasks" => "ORDER BY priority_stats DESC , taskDueDate ASC"
			])
			->with([
				"tasks" => ['task_id', 'task_id'],
				// "projects" => ['projectCode', 'projectCode']
			])
			->get();

		$count = 1;
		foreach ($tasks as $task) {
			if (!empty($task['tasks'])) {

				$_task = (!empty($task['tasks'][0]))
					? $task['tasks'][0]
					: $task['tasks'];

				if ($_task['priority_stats'] == 0) {
					$prioColor = "green";
				} else if ($_task['priority_stats'] == 1) {
					$prioColor = "orange";
				} else {
					$prioColor = "red";
				}

				if ($_task['status'] == 1) {
					$_type = "IN PROGRESS";
				} else if ($_task['status'] == 2) {
					$_type = "DONE";
				} else {
					$_type = "TO DO";
				}

				if ($_task['finishDate'] != '0000-00-00 00:00:00') {
					$endDate = $_task['finishDate'];
				} else {
					$endDate = $_task['taskDueDate'];
				}

				$taskDesc = substr(html_entity_decode($_task['taskDescription']), 0, 100);

				$data[] = [
					'id' => $_task['task_id'],
					'code' => $_task['task_code'],
					'projCode' => $_task['projectCode'],
					'type' => $_task['status'],
					'title' => '[' . $_type . '] ' . $taskDesc,
					'start' =>  date('Y-m-d', strtotime($_task['taskCreateDate'])),
					// 'end' =>  $endDate,
					'allDay' => false,
					'backgroundColor' => $prioColor,
					'borderColor' => $prioColor
				];
			}
		}

		echo json_encode($data);
	}

	public function taskDetail()
	{
		$request = Request::validate('/mycalendar');
		$task_id = $request["task_id"];
		$type = $request["type"];
		$response = array();

		if ($type == 1) {
			$_type = "IN PROGRESS";
		} else if ($type == 2) {
			$_type = "DONE";
		} else {
			$_type = "TO DO";
		}

		$response['task_detail'] = array();
		$task_q = DB()->selectLoop("*", "tasks", "task_id = '$task_id'")->get();
		if (count($task_q) > 0) {
			foreach ($task_q as $taskList) {
				$task_items = array(
					"task_id"       => $taskList['task_id'],
					"priority"      => $taskList['priority_stats'],
					"projectName"   => (!empty(getProjectName($taskList['projectCode'], 'projectName'))) ? getProjectName($taskList['projectCode'], 'projectName') : 'PERSONAL',
					"date"          => date('Y-m-d', strtotime($taskList['taskDueDate'])),
					"task_code"     => $taskList['task_code'],
					"task_title"    => html_entity_decode($taskList['taskTitle']),
					"task"          => html_entity_decode($taskList['taskDescription']),
					"task_type"     => $_type,
					"task_member"   => getTaskMember($taskList['projectCode'], $taskList['task_id']),
					"member_remove" => allowDeleteTask($taskList['projectCode'], $taskList['task_id'])
				);
				array_push($response['task_detail'], $task_items);
			}
		}

		echo json_encode($response);
	}

	public function taskAdd()
	{
		$request = Request::validate('/mycalendar');

		$created_date = $request['created_date'];
		$taskDueDate = $request['due_date'];
		$user_id = Auth::user('id');
		$taskTitle = addslashes($request['task_title']);
		$taskDescription = addslashes($request['taskDescription']);
		$status = 1;
		$priority_stats = $request['priority_status'];
		$projectCode = $request['projectCode'];
		$date_mins = date('is');
		$randCode = strtoupper(randChar(4) . $date_mins);

		$data = [
			'projectCode' => $projectCode,
			'taskTitle' => $taskTitle,
			'taskDescription' => $taskDescription,
			'taskDueDate' => $taskDueDate,
			'taskCreateDate' => $created_date,
			'status' => $status,
			'priority_stats' => $priority_stats,
			'task_code' => $randCode
		];

		$taskID = DB()->insert("tasks", $data, "Y");
		if ($taskID) {
			$data = [
				'projectCode' => $projectCode,
				'task_id' => $taskID,
				'user_id' => $user_id
			];
			$res = DB()->insert("task_member", $data);
			echo $res;
		} else {
			echo 0;
		}
	}
}
