<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_team_table = [
	"mode" => "NEW",
	"table"	=> "teams",
	"primary_key" => "team_id",
	"up" => [
		"team_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"teamCode" => "VARCHAR(50) NOT NULL",
		"teamName" => "TEXT NOT NULL",
		"teamLeader" => "INT(11) NOT NULL",
		"created_by" => "INT(11) NOT NULL",
		"slug" => "TEXT NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
