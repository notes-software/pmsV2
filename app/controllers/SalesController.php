<?php

namespace App\Controllers;

use App\Core\Request;

class SalesController
{
    public function index()
    {
        abort_if(gate_denies('sales_access'), '403 Forbidden');

        $pageTitle = "Sales";
        $branch = $_SESSION['system']['branch_id'];

        $branches = DB()->selectLoop("*", "branch", "id > 0 ORDER BY id DESC")->get();

        $purchases = DB()->selectLoop("*", "purchase_header", "branch = '$branch' ORDER BY status ASC, id DESC")
            ->with([
                "purchase_detail" => [
                    'id',
                    'po_header_id'
                ],
                "supplier" => [
                    'supplier_id',
                    'id'
                ]
            ])
            ->get();

        return view('/sales/index', compact('pageTitle', 'purchases', 'branches'));
    }

    public function reportIndex()
    {
        abort_if(gate_denies('sales_report_access'), '403 Forbidden');

        $pageTitle = "Sales Report";
        $branch = $_SESSION['system']['branch_id'];

        $prodCategories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY id DESC")->get();

        return view('/sales/report/index', compact('pageTitle', 'prodCategories'));
    }
}
