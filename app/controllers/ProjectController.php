<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;

class ProjectController
{
	public function index()
	{
		// abort_if(gate_denies('branch_access'), '403 Forbidden');

		$pageTitle = "Projects";
		$user_id = Auth::user('id');

		$projects = DB()->selectLoop("t1.CODE as project_code", "(SELECT pm.projectCode AS CODE FROM project_member AS pm, team_member AS tm WHERE pm.teamCode = tm.teamCode AND tm.user_id = '$user_id' AND pm.type = 1 GROUP BY pm.projectCode UNION ALL SELECT pjm.projectCode AS CODE FROM project_member AS pjm, projects AS p WHERE p.projectCode = pjm.projectCode AND pjm.user_id = '$user_id' AND pjm.type = 0 UNION ALL SELECT prj.projectCode as CODE FROM projects as prj WHERE prj.proj_pm = '$user_id') AS t1", "t1.CODE != '' GROUP BY t1.CODE")
			->with([
				'projects' => ['project_code', 'projectCode']
			])
			->get();

		return view('/projects/index', compact('pageTitle', 'projects'));
	}

	public function store()
	{
		$request = Request::validate('/project');

		$user_id = Auth::user('id');
		$project_name = $request['project_name'];
		$project_description = $request['project_description'];
		$projectCode = randChar(10);

		$data = array(
			'projectCode' => $projectCode,
			'projectName' => $project_name,
			'projectDescription' => $project_description,
			'proj_pm' => $user_id
		);
		$result = DB()->insert("projects", $data);

		$memberData = [
			'projectCode' => $projectCode,
			'user_id' => $user_id,
			'role_id' => 2
		];
		DB()->insert("project_member", $memberData);

		echo $result;
	}

	public function view($projectCode)
	{
		$project = DB()->select('*', 'projects', "projectCode = '$projectCode' ORDER BY projectName ASC")->get();
		$projectMembers = getProjectMember($projectCode);

		$pageTitle = $project['projectName'];
		return view('/projects/detail', compact('pageTitle', 'project', 'projectMembers'));
	}

	public function task()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);

		$response['todo'] = [];
		$response['inprogress'] = [];
		$response['done'] = [];
		$tasks = getUserTask($request['project_code'], $request['memberSelected']);

		// var_dump($tasks);
		// die();

		foreach ($tasks as $task) {
			if (!empty($task['tasks'])) {
				$_task = (!empty($task['tasks'][0]))
					? $task['tasks'][0]
					: $task['tasks'];

				if ($_task['task_id'] != null) {

					if ($_task['priority_stats'] == 0) {
						$prio = "green";
					} else if ($_task['priority_stats'] == 1) {
						$prio = "orange";
					} else {
						$prio = "red";
					}

					if ($_task['status'] == 0) {
						$module = "TO DO";
					} else if ($_task['status'] == 1) {
						$module = "IN PROGRESS";
					} else if ($_task['status'] == 2) {
						$module = "DONE";
					}

					$items = array(
						"task_id"   => $_task['task_id'],
						"priority"  => $_task['priority_stats'],
						"priority_color" => $prio,
						"date"      => date('m/d/Y', strtotime($_task['taskDueDate'])),
						"task_code" => $_task['task_code'],
						"task"      => html_entity_decode($_task['taskDescription']),
						"task_member"   => getTaskMember($_task['projectCode'], $task['task_id']),
						"allow_delete"  => allowDeleteTask($_task['projectCode'], $task['task_id']),
						"finishDate" => date("Y-n-d", strtotime($_task['finishDate'])),
						"module" => $module
					);

					if ($_task['status'] == 0) {
						array_push($response['todo'], $items);
					} else if ($_task['status'] == 1) {
						array_push($response['inprogress'], $items);
					} else if ($_task['status'] == 2) {
						array_push($response['done'], $items);
					}
				}
			}
		}

		echo json_encode($response);
	}

	public function updateTaskType()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);
		$task_id = $request["id"];
		$task_type = $request["type"];

		$task_code = DB()->select("task_code", "tasks", "task_id = '$task_id'")->get();

		if ($task_type == 1) {
			$data = array(
				// "taskCreateDate" => date("Y-m-d H:i:s"),
				'status' => $task_type,
			);
		} else if ($task_type == 2) {
			$data = array(
				'status' => $task_type,
				"finishDate" => date("Y-m-d H:i:s")
			);
		}

		$result = DB()->update("tasks", $data, "task_id = '$task_id'");
		if ($result) {
			if ($task_type != 0) {
				if ($task_type == 1) {
					$types = "ongoing";
				} else if ($task_type == 2) {
					$types = "done";
				}
				$msg = Auth::user('fullname') . " updated a task [" . $task_code["task_code"] . "] to " . $types;
				$module = "Task";
				$user_id = Auth::user('id');
				log_activity($msg, $module, $user_id, $task_code['task_code']);
			}
		}
	}

	public function taskDetail()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);
		$task_id = $request["task_id"];
		$type = $request["type"];
		$response = array();

		$response['task_detail'] = array();
		$task_q = DB()->selectLoop("*", "tasks", "task_id = '$task_id'")->get();
		if (count($task_q) > 0) {
			foreach ($task_q as $taskList) {
				$task_items = array(
					"task_id"       => $taskList['task_id'],
					"priority"      => $taskList['priority_stats'],
					"date"          => date('Y-m-d', strtotime($taskList['taskDueDate'])),
					"task_code"     => $taskList['task_code'],
					"task"          => html_entity_decode($taskList['taskDescription']),
					"task_type"     => $type,
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
		$request = Request::validate('/project/' . $_REQUEST['projectCode']);

		$taskDueDate = $request['due_date'];
		$user_id = Auth::user('id');
		$taskDescription = htmlentities(addslashes($request['taskDescription']));
		$status = 0;
		$priority_stats = $request['priority_status'];
		$projectCode = $request['projectCode'];
		$date_mins = date('is');
		$randCode = strtoupper(randChar(4) . $date_mins);

		$data = [
			'projectCode' => $projectCode,
			'taskDescription' => $taskDescription,
			'taskDueDate' => $taskDueDate,
			'taskCreateDate' => date("Y-m-d H:i:s"),
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

	public function taskUpdate()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);
		$task_code = $request['task_code'];

		$data = array(
			'taskDescription'    => htmlentities(addslashes($request['task_desc'])),
			'taskDueDate'        => date("Y-m-d", strtotime($request['task_due_date'])),
			'priority_stats'    => $request['task_prio']
		);

		$res = DB()->update("tasks", $data, "task_code = '$task_code'");
		echo $res;
	}

	public function taskDelete()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);

		$taskID = $request["id"];

		$res = DB()->delete("tasks", "task_id = '$taskID' AND projectCode = '$request[project_code]'");
		if ($res) {
			$reslt = DB()->delete("task_member", "task_id = '$taskID' AND projectCode = '$request[project_code]'");
			echo $reslt;
		} else {
			echo 0;
		}
	}

	public function taskSearchMember()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);
		$search_q = $request['search_tq'];
		$task_code = $request['task_code'];
		$data = "";

		$task_id = DB()->select("task_id, projectCode", "tasks", "task_code = '$task_code'")->get();
		if ($search_q != "") {
			if (strpos($search_q, '@')) {
				$loop_user = DB()->selectLoop("*", "users", "email LIKE '%$search_q%'")->get();
				if (count($loop_user) > 0) {
					foreach ($loop_user as $user_list) {
						$taskMemberChecker = DB()->select("count(user_id) as number_of_user", "task_member", "task_id = '$task_id[task_id]' AND user_id = '$user_list[id]'")->get();
						$user_avatar = getUserAvatar($user_list['id']);
						if ($taskMemberChecker['number_of_user'] < 1) {
							$data .= '<li class="list-group-item pl-0"><div class="row align-items-center"><div class="col-2"><a href="#" class="avatar rounded-circle" style="width: 40px;height: 40px;"><img src="' . $user_avatar . '" style="width: 100%;height: 100%;object-fit: cover;" class="rounded-circle"></a></div><div class="col-8"><h5 class="text-muted mb-0">' . $user_list['fullname'] . '</h5><small class="text-muted">' . $user_list['email'] . '</small></div><div class="col-2"><div style="align-items: baseline;justify-content: flex-end;display: flex;"><a href="#" class="btn btn-success btn-sm" onclick="inviteMemberToTask(\'' . $user_list['id'] . '\', \'' . $task_id['task_id'] . '\', \'' . $task_id['projectCode'] . '\')">Share</a></div></div></div></li>';
						} else {
							$data .= '<li class="list-group-item px-0"><b>' . $user_list['fullname'] . '</b> is already in your task.</li>';
						}
					}

					echo $data;
				}
			} else {
				echo 1;
			}
		}
	}

	public function taskInviteMember()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);

		$user_id = $request["user_id"];
		$task_id = $request["task_id"];
		$project_code = $request["project_code"];

		$isNotExist = DB()->select("count(user_id) as totals", "task_member", "user_id = '$user_id' AND task_id = '$task_id' AND projectCode = '$project_code'")->get();
		if ($isNotExist['totals'] < 1) {

			$isProjExist = DB()->select("count(*)", "project_member", "user_id = '$user_id' AND projectCode = '$project_code'")->get();
			if (!empty($isProjExist[0])) {
				if ($isProjExist[0] < 1) {
					$data = array(
						'projectCode'    => $project_code,
						'user_id'        => $user_id
					);
					$res = DB()->insert("project_member", $data);
				}
			}

			$data = array(
				'projectCode'    => $project_code,
				'task_id'        => $task_id,
				'user_id'        => $user_id,
				'invite_status'    => 1
			);
			$res = DB()->insert("task_member", $data);
			echo $res;
		} else {
			echo 2;
		}
	}

	public function details($projectCode)
	{
		$pageTitle = "Project Settings";
		$user_id = Auth::user('id');

		$projectDetail = DB()->select('*', 'projects', "projectCode = '$projectCode'")->get();

		return view('/projects/settings/index', compact('pageTitle', 'projectDetail'));
	}

	public function members()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['projCode']);
		$proj_name = $request['proj_name'];
		$projCode = $request['projCode'];
		$proj_stats = $request['proj_stats'];
		$response = array();

		$response['proj_member'] = array();

		$loop_mem = getProjectMember($projCode);
		if (count($loop_mem) < 1) {
			echo "invite member to this project";
		} else {
			foreach ($loop_mem as $memList) {
				$user_avatar = getUserAvatar($memList['user_id']);

				$members = array(
					"id" => $memList['user_id'],
					"name" => $memList["memberName"],
					"avatar" => $user_avatar,
				);
				array_push($response['proj_member'], $members);
			}
		}

		echo json_encode($response);
	}

	public function memberDelete()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['projCode']);

		$projectCode = $request["projCode"];
		$member_id = $request["id"];
		$test = $this->deleteProjectMember($projectCode, "", $member_id, 0);
		echo $test;
	}

	public function deleteProjectMember($projectCode, $teamCode, $member_id, $type)
	{
		if ($type == 1) {
			// GROUP MEMBER
			$loop_group_in_project = $this->getMemberInTeam($teamCode);
			if (count($loop_group_in_project) > 0) {
				foreach ($loop_group_in_project as $groupList) {
					$isInGroup = $this->checkIFuserIsInGroup($projectCode, $groupList['user_id'], $teamCode);
					$isInmember = $this->checkIFuserIsInMember($projectCode, $groupList['user_id']);
					$delProjectMember = DB()->delete("project_member", "teamCode = '$teamCode' AND projectCode = '$projectCode' AND type = 1");
					if ($delProjectMember) {
						if ($isInGroup == 1) {
							// echo $groupList[user_id]." IF equals: do not delete task!<br><br>";
						} else {
							if ($isInmember == 1) {
								// echo $groupList[user_id]." IF equals: do not delete task!<br><br>";
							} else {
								$this->deleteUserTask($groupList['user_id'], $projectCode);
							}
						}
					}
				}
			}
		} else {
			$isInGroup = $this->checkIFuserIsInGroup($projectCode, $member_id);
			$isInmember = $this->checkIFuserIsInMember($projectCode, $member_id);
			$delProjectMember = DB()->delete("project_member", "user_id = '$member_id' AND projectCode = '$projectCode' AND type = 0");
			if ($delProjectMember) {
				if ($isInGroup == 1) {
					// echo $member_id." IF equals: do not delete task!<br><br>";
				} else {
					if ($isInmember == 1) {
						// echo $member_id." IF not: delete task!<br>";
						$this->deleteUserTask($member_id, $projectCode);
					} else {
						// echo $member_id." IF equals: do not delete task!<br><br>";
					}
				}
			}
		}
	}

	public function getMemberInTeam($teamCode)
	{
		$tm_data = DB()->selectLoop("*", "team_member", "teamCode = '$teamCode'")->get();
		if (count($tm_data) > 0) {
			foreach ($tm_data as $tm_list) {
				$data[] = array(
					'teamCode'   => $tm_list['teamCode'],
					'user_id'    => $tm_list['user_id'],
					'role_id'    => $tm_list['role_id']
				);
			}
			return $data;
		}
	}

	public function checkIFuserIsInMember($projectCode, $deleted_user)
	{

		$member = DB()->select("count(user_id) as  total_users", "project_member", "user_id = '$deleted_user' AND projectCode = '$projectCode' AND type = 0")->get();

		if ($member['total_users'] > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function checkIFuserIsInGroup($projectCode, $deleted_user, $deleted_teamCode = "")
	{
		$data = 0;
		$t_params = ($deleted_teamCode == "") ? "AND `type` = 1" : "AND teamCode != '$deleted_teamCode' AND `type` = 1";
		$loopRemainingGroup = DB()->selectLoop("teamCode", "project_member", "projectCode = '$projectCode' $t_params")->get();
		foreach ($loopRemainingGroup as $rm_list) {

			$Group = DB()->select("count(user_id) as total_user", "team_member", "teamCode = '$rm_list[teamCode]' AND user_id = '$deleted_user'")->get();
			if ($Group['total_user'] > 0) {
				$data += 1;
			} else {
				$data = 0;
			}
		}

		if ($data > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	public function deleteUserTask($member_id, $projectCode)
	{
		$getTaskData = DB()->selectLoop("task_id", "task_member", "user_id = '$member_id' AND projectCode = '$projectCode'")->get();
		if (count($getTaskData) > 0) {
			foreach ($getTaskData as $taskList) {
				DB()->delete("tasks", "task_id = '$taskList[task_id]'");
			}
		}

		$reslt = DB()->delete("task_member", "user_id = '$member_id' AND projectCode = '$projectCode'");
		echo $reslt;
	}

	public function delete()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['projCode']);
		$projectCode = $request['projCode'];

		$this->remove($projectCode);
	}

	public function remove($projectCode)
	{
		// PROJECT
		$res = DB()->delete("projects", "projectCode = '$projectCode'");
		if ($res) {
			// PROJECT MEMBER
			DB()->delete("project_member", "projectCode = '$projectCode'");
			// TASK
			DB()->delete("tasks", "projectCode = '$projectCode'");
			// TASK MEMBER
			DB()->delete("task_member", "projectCode = '$projectCode'");
		}
	}

	public function finish()
	{
		// close a project

		$request = Request::validate('/project/settings/' . $_REQUEST['projectCode']);

		$projectCode = $request['projectCode'];

		$data = array(
			'status' => 1
		);

		DB()->update("projects", $data, "projectCode = '$projectCode'");
		redirect('/project/settings/' . $projectCode);
	}

	public function saveInvite()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['projCode']);
		$projectCode = $request['projCode'];
		$inviteID = $request['id'];

		$data = array(
			'projectCode' => $projectCode,
			'user_id' => $inviteID,
			'type' => 0
		);

		$res = DB()->insert("project_member", $data);
		echo $res;
	}

	public function saveGroupInvite()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['projCode']);
		$selected_group = $request['selected_group'];
		$projCode = $request['projCode'];

		$errors = [];
		$loopTeamMember = DB()->selectLoop("user_id", "team_member", "teamCode = '$selected_group'")->get();
		foreach ($loopTeamMember as $member) {
			$res = DB()->select("COUNT(user_id) as total_user", "project_member", "teamCode = '$selected_group' AND projectCode = '$projCode' AND user_id = '$member[user_id]'")->get();
			if ($res['total_user'] > 0) {
				$errors[] = [
					"type" => 2,
					"msg" => getUserName($member['user_id']) . " is already in this project."
				];
			} else {
				$data = array(
					'projectCode' => $projCode,
					'teamCode' => $selected_group,
					'user_id' => $member['user_id'],
					'type' => 0
				);
				$reslt = DB()->insert("project_member", $data);
				$errors[] = [
					"type" => $reslt,
					"msg" => ""
				];
			}
		}

		echo json_encode($errors);
	}

	public function searchPeople()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['project_code']);

		$search_q = $request['search_q'];
		$projectCode = $request['project_code'];
		if ($search_q != "") {
			if (strpos($search_q, '@')) {
				$loop_user = DB()->selectLoop("*", "users", "email LIKE '%$search_q%'")->get();
				if (count($loop_user) > 0) {
					$data = "";
					foreach ($loop_user as $user_list) {
						$projMemberChecker = DB()->select("count(user_id) as total_user", "project_member", "projectCode = '$projectCode' AND user_id = '$user_list[id]' AND type = 0")->get();
						$user_avatar = getUserAvatar($user_list['id']);
						if ($projMemberChecker['total_user'] < 1) {
							$data .= '<li class="list-group-item px-0 pb-0"><div class="row align-items-center"><div class="col-md-2 pr-0"><a href="#" class="avatar rounded-circle" style="width: 40px;height: 40px;"><img src="' . $user_avatar . '" style="width: 100%;height: 100%;object-fit: cover;" class="rounded-circle"></a></div><div class="col pl-2"><h5 class="text-muted mb-0">' . $user_list['fullname'] . '</h5><small class="text-muted">' . $user_list['email'] . '</small></div><div class="col-md-1"><div style="align-items: baseline;justify-content: flex-end;display: flex;"><a href="#" class="btn btn-success btn-sm" onclick="invitePeopleToProject(\'' . $user_list['id'] . '\')">invite</a></div></div></div></li>';
						} else {
							$data .= '<li class="list-group-item px-0 pb-0"><b>' . $user_list['fullname'] . '</b> is already in your project.</li>';
						}
					}

					echo $data;
				}
			} else {
				echo 1;
			}
		}
	}

	public function update()
	{
		$request = Request::validate('/project/settings/' . $_REQUEST['code']);

		$name = $request['name'];
		$description = $request['description'];
		$code = $request['code'];

		$data = array(
			'projectName' => $name,
			'projectDescription' => $description
		);

		$res = DB()->update("projects", $data, "projectCode = '$code'");
		echo $res;
	}
}
