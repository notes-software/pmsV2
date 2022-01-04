<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_request_logs_table = [
	"mode" => "NEW",
	"table"	=> "request_logs",
	"primary_key" => "request_id",
	"up" => [
		"request_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"request_date" => "DATETIME NOT NULL",
		"logs" => "TEXT NOT NULL",
		"requested_by" => "TEXT NOT NULL",
		"person_assigned" => "INT(11) NOT NULL",
		"approved_by" => "INT(11) NOT NULL",
		"status" => "INT(1) NOT NULL",
		"approve_date" => "DATETIME NOT NULL",
		"remarks" => "TEXT NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
