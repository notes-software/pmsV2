<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_project_table = [
	"mode" => "NEW",
	"table"	=> "projects",
	"primary_key" => "project_id",
	"up" => [
		"project_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"projectCode" => "VARCHAR(100) NOT NULL",
		"projectName" => "TEXT NOT NULL",
		"projectDescription" => "TEXT NOT NULL",
		"proj_pm" => "INT(11) NOT NULL",
		"status" => "INT(1) NOT NULL COMMENT '0 = active, 1 = closed'"
	],
	"down" => [
		"" => ""
	]
];
