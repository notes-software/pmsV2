<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_task_member_table = [
	"mode" => "NEW",
	"table"	=> "task_member",
	"primary_key" => "task_member_id",
	"up" => [
		"task_member_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"teamCode" => "TEXT NOT NULL",
		"projectCode" => "TEXT NOT NULL",
		"task_id" => "INT(11) NOT NULL",
		"user_id" => "INT(11) NOT NULL",
		"invite_status" => "INT(1) NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
