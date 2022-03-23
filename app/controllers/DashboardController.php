<?php

namespace App\Controllers;

use App\Core\Auth;

class DashboardController
{
    protected $pageTitle;

    public function index()
    {
        abort_if(gate_denies('dashboard_access'), 403);

        $pageTitle = "Dashboard";

        $user_id = Auth::user('id');

        $projects = DB()->selectLoop("t1.CODE as project_code", "(SELECT pm.projectCode AS CODE FROM project_member AS pm, team_member AS tm WHERE pm.teamCode = tm.teamCode AND tm.user_id = '$user_id' AND pm.type = 1 GROUP BY pm.projectCode UNION ALL SELECT pjm.projectCode AS CODE FROM project_member AS pjm, projects AS p WHERE p.projectCode = pjm.projectCode AND pjm.user_id = '$user_id' AND pjm.type = 0 UNION ALL SELECT prj.projectCode as CODE FROM projects as prj WHERE prj.proj_pm = '$user_id') AS t1", "t1.CODE != '' GROUP BY t1.CODE")
            ->with([
                'projects' => ['project_code', 'projectCode']
            ])
            ->get();

        $totalProjects = count($projects);

        $totalInProgressTask = 0;
        foreach ($projects as $project) {
            $totalInProgressTask += getTotalInProgressTask($project['project_code'], $user_id);
        }

        $totalTask = 0;
        foreach ($projects as $project) {
            $totalTask += getTotalAllTask($project['project_code'], $user_id);
        }

        return view('/dashboard', compact('pageTitle', 'totalProjects', 'totalTask', 'totalInProgressTask'));
    }
}
