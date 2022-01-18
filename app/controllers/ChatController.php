<?php

namespace App\Controllers;

class ChatController
{
    protected $pageTitle;

    public function index()
    {
        $pageTitle = "Chat";

        return view('/chat/index');
    }
}
