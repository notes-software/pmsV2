<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_task_table = [
	"mode" => "NEW",
	"table"	=> "tasks",
	"primary_key" => "task_id",
	"up" => [
		"task_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"projectCode" => "TEXT NOT NULL",
		"taskDescription" => "TEXT NOT NULL",
		"taskDueDate" => "DATETIME NOT NULL",
		"taskCreateDate" => "DATETIME NOT NULL",
		"status" => "INT(1) NOT NULL COMMENT '0=todo, 1=on progress, 2=done'",
		"finishDate" => "DATETIME NOT NULL",
		"priority_stats" => "INT(1) NOT NULL COMMENT '0=low, 1=medium, 2=high'",
		"task_code" => "VARCHAR(50) NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
