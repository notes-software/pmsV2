<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_team_member_table = [
	"mode" => "NEW",
	"table"	=> "team_member",
	"primary_key" => "team_member_id",
	"up" => [
		"team_member_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"teamCode" => "TEXT NOT NULL",
		"user_id" => "INT(11) NOT NULL",
		"role_id" => "INT(11) NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
