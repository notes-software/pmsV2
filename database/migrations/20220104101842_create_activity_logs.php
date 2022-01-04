<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_activity_logs = [
	"mode" => "NEW",
	"table"	=> "activity_logs",
	"primary_key" => "log_id",
	"up" => [
		"log_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"log" => "TEXT NOT NULL",
		"module" => "TEXT NOT NULL",
		"date" => "TEXT NOT NULL",
		"user_id" => "INT(11) NOT NULL",
		"task_code" => "VARCHAR(50) NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
