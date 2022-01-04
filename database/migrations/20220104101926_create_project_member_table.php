<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_project_member_table = [
	"mode" => "NEW",
	"table"	=> "project_member",
	"primary_key" => "project_member_id",
	"up" => [
		"project_member_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"projectCode" => "TEXT NOT NULL",
		"teamCode" => "TEXT NULL",
		"user_id" => "INT(11) NOT NULL",
		"type" => "INT(3) NOT NULL",
		"role_id" => "INT(11) NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
