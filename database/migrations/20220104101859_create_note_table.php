<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$create_note_table = [
	"mode" => "NEW",
	"table"	=> "notes",
	"primary_key" => "note_id",
	"up" => [
		"note_id" => "INT(11) NOT NULL AUTO_INCREMENT",
		"note_title" => "TEXT NOT NULL",
		"note_content" => "TEXT NOT NULL",
		"user_id" => "INT(11) NOT NULL"
	],
	"down" => [
		"" => ""
	]
];
