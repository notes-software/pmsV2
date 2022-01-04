<?php

/**
 * Always remember:
 * "up" is for run migration
 * "down" is for the rollback, reverse the migration
 * 
 */
$update_users_table = [
	"mode" => "CHANGE",
	"table"	=> "users",
	"primary_key" => "",
	"up" => [
		"status" => "ADD COLUMN `status` varchar(3) NOT NULL",
		"bio" => "ADD COLUMN `bio` varchar(200) NOT NULL",
		"slug" => "ADD COLUMN `slug` text NOT NULL",
	],
	"down" => [
		"status" => "DROP COLUMN `status`",
		"bio" => "DROP COLUMN `bio`",
		"slug" => "DROP COLUMN `slug`",
	]
];
