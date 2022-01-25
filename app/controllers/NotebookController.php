<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;
use PDO;

class NotebookController
{
	public function index()
	{
		// abort_if(gate_denies('branch_access'), '403 Forbidden');

		$pageTitle = "Notebook";

		$requests = DB()->selectLoop("*", "request_logs", "request_id > 0 ORDER BY request_id DESC")->get();

		return view('/notebook/index', compact('pageTitle', 'requests'));
	}

	public function add()
	{
		echo '<div class="card cardNote"><div class="card-body" id="crd_bdy"><textarea class="card-title note-title" rows="1" placeholder="Title" maxlength="999" dir="ltr" style="height: 20px;font-size: 14px; font-style: bolder; font-family: inherit;margin-bottom: 0px;" id="note_title"></textarea><div class="card-text note-content mb-2" rows="1" contenteditable="true" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 12px; font-family: inherit;white-space: pre-wrap;-webkit-user-modify: read-write-plaintext-only;outline: none;" id="note_content" data-text="content here..."></div><span class="badge navbar-badge" title="Cancel" onclick="cancelNote()" style="right: 15px !important;"><i class="fas fa-ban" style="color: red;font-size: 12px;"></i></span><span class="badge navbar-badge" title="Save Changes" style="right: 35;" onclick="saveNote()"><i class="fas fa-save" style="font-size: 13px;"></i></span></div></div>';
	}

	public function save()
	{
		$request = Request::validate('/');

		$title = $request['title'];
		$content = htmlentities(addslashes($request['content']));
		$user_id = Auth::user('id');

		$data = array(
			'note_title' => $title,
			'note_content' => $content,
			'user_id' => $user_id
		);

		$res = DB()->insert("notes", $data);
		echo $res;
	}

	public function data()
	{
		$loop_n = [];
		$user_id = Auth::user('id');
		$loop_notes = DB()->selectLoop("*", "notes", "user_id = '$user_id' ORDER BY note_id DESC")->get();
		if (is_array($loop_notes)) {
			foreach ($loop_notes as $note_list) {
				$loop_n[] = array(
					'id' => $note_list['note_id'],
					'title' => $note_list['note_title'],
					'content' => html_entity_decode($note_list['note_content']),
					'user_id' => $note_list['user_id']
				);
			}
		}

		if (!is_array($loop_n)) {
			echo "no data available";
		} else {
			foreach ($loop_n as $note_list) {
				$noteID = "update_note_content_" . $note_list['id'];

				echo '<div class="card cardNote"><div class="card-body p-3"><div onclick="openNote(\'' . $note_list['id'] . '\')" ><div class="card-title note-title note-content mb-2" rows="1" contenteditable="false" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 16px; font-family: inherit;white-space: pre-wrap;outline: none;" id="update_note_title_' . $note_list['id'] . '">' . html_entity_decode($note_list["title"]) . '</div><div class="card-text note-content mb-2" rows="1" contenteditable="false" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 12px; font-family: inherit;white-space: pre-wrap;outline: none;" id="' . $noteID . '">' . html_entity_decode($note_list["content"]) . '</div></div><div class="col-md-12 mt-1"></div><div class="col-md-12"><div style="display: flex;flex-direction: row;justify-content: end;align-items: center;"><div class="btn-group"><a onclick="deleteNote(\'' . $note_list['id'] . '\')" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete Note"><i class="far fa-trash-alt p-1"></i></a></div></div></div></div></div>';
			}
		}
	}

	/**
	 * <div class="row"><span class="badge badge-secondary">work</span></div>
	 * 
	 */

	/**
	 * <textarea class="card-title note-title" rows="1" placeholder="Title" maxlength="999" dir="ltr" style="height: 20px;font-size: 16px; font-style: bolder; font-family: inherit;margin-bottom: 15px;" id="update_note_title_' . $note_list['id'] . '">' . $note_list["title"] . '</textarea>
	 * 
	 */

	/**
	 * <span class="" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete Note" onclick="deleteNote(\'' . $note_list['id'] . '\')" style="right: 15px !important;"><i class="far fa-trash-alt" style="color: red;font-size: 12px;"></i></span><span class="" data-toggle="tooltip" data-placement="bottom" data-original-title="Save Changes" style="right: 35;" onclick="updateNote(\'' . $note_list['id'] . '\')"><i class="fas fa-save" style="font-size: 13px;"></i></span>
	 * 
	 */

	public function delete()
	{
		$request = Request::validate('/notebook');

		$user_id = Auth::user('id');
		$note_ID = $request['id'];

		$resconvo_ch = DB()->delete("notes", "note_id = '$note_ID' AND user_id = '$user_id'");

		echo $resconvo_ch;
	}

	public function update()
	{
		$request = Request::validate('/notebook');
		$title = $request['title'];
		$content = htmlentities(addslashes($request['content']));
		$user_id = Auth::user('id');

		$note_id = $request['id'];
		$data = array(
			'note_title' => $title,
			'note_content' => $content,
			'user_id' => $user_id
		);
		$res = DB()->update("notes", $data, "note_id='$note_id'");
		echo $res;
	}

	public function search()
	{
		$request = Request::validate('/notebook');

		$search_q = $request['search_q'];
		$user_id = Auth::user('id');

		$loop_n = array();
		$loop_notes = DB()->selectLoop("*", "notes", "user_id = '$user_id' AND (note_content LIKE '%$search_q%' OR note_title LIKE '%$search_q%') ORDER BY note_id DESC")->get();
		if (count($loop_notes) > 0) {
			foreach ($loop_notes as $note_list) {
				$loop_n[] = array(
					'id' => $note_list['note_id'],
					'title' => $note_list['note_title'],
					'content' => $note_list['note_content'],
					'user_id' => $note_list['user_id']
				);
			}
		};

		if (count($loop_n) < 1) {
			echo "no data available";
		} else {
			foreach ($loop_n as $note_list) {
				$noteID = "update_note_content_" . $note_list['id'];
				echo '<div class="card cardNote"><div class="card-body"><textarea class="card-title note-title" rows="1" placeholder="Title" maxlength="999" dir="ltr" style="height: 20px;font-size: 14px; font-style: bolder; font-family: inherit;margin-bottom: 0px;" id="update_note_title_' . $note_list['id'] . '">' . $note_list["title"] . '</textarea><div class="card-text note-content mb-2" rows="1" contenteditable="true" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 12px; font-family: inherit;white-space: pre-wrap;-webkit-user-modify: read-write-plaintext-only;outline: none;" id="' . $noteID . '">' . html_entity_decode($note_list["content"]) . '</div><span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete Note" onclick="deleteNote(\'' . $note_list['id'] . '\')" style="right: 15px !important;"><i class="far fa-trash-alt" style="color: red;font-size: 12px;"></i></span><span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="Save Changes" style="right: 35;" onclick="updateNote(\'' . $note_list['id'] . '\')"><i class="fas fa-save" style="font-size: 13px;"></i></span></div></div>';
			}
		}
	}
}
