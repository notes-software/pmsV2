<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$update_project_table_add_cost = [
	"mode" => "CHANGE",
	"table"	=> "projects",
	"primary_key" => "",
	"up" => [
		"projectCost" => "ADD COLUMN `projectCost` decimal(12,3) DEFAULT NULL",
		"projectDeadline" => "ADD COLUMN `projectDeadline` datetime DEFAULT NULL",
	],
	"down" => [
		"projectCost" => "DROP COLUMN `projectCost`",
		"projectDeadline" => "DROP COLUMN `projectDeadline`",
	]
];
