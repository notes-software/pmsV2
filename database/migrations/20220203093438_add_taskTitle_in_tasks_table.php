<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$add_taskTitle_in_tasks_table = [
	"mode" => "CHANGE",
	"table"	=> "tasks",
	"primary_key" => "",
	"up" => [
		"taskTitle" => "ADD COLUMN `taskTitle` VARCHAR(150) NOT NULL AFTER `projectCode`"
	],
	"down" => [
		"taskTitle" => "DROP COLUMN `taskTitle`"
	]
];
