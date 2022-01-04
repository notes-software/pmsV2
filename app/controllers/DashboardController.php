<?php

namespace App\Controllers;

class DashboardController
{
    protected $pageTitle;

    public function index()
    {
        // abort_if(gate_denies('dashboard_access'), '403 Forbidden');

        $pageTitle = "Dashboard";

        return view('/dashboard', compact('pageTitle'));
    }
}
