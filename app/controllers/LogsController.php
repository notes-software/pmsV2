<?php

namespace App\Controllers;

use App\Core\Auth;

class LogsController
{
    protected $pageTitle;

    public function logsIndex()
    {
        abort_if(gate_denies('activity_log_access'), 403);

        $pageTitle = "Activity Logs";

        $_logs = DB()->selectLoop('*', 'activity_logs', 'log_id > 0 ORDER BY log_id DESC')
            ->paginate(20);

        $logs = $_logs->get();
        $links = $_logs->links();

        return view('/logs/index', compact('pageTitle', 'logs', 'links'));
    }
}
