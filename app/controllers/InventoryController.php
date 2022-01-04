<?php

namespace App\Controllers;

use App\Core\Request;

class InventoryController
{
    public function index()
    {
        abort_if(gate_denies('inventory_report_access'), '403 Forbidden');

        $pageTitle = "Inventory Report";
        $branch = $_SESSION['system']['branch_id'];

        $prodCategories = DB()->selectLoop("*", "product_category", "id > 0 ORDER BY id DESC")->get();

        return view('/inventory/index', compact('pageTitle', 'prodCategories'));
    }

    public function generate()
    {
        $body = "";
        $branch = $_SESSION['system']['branch_id'];
        $units = DB()->selectLoop("*", "product_unit", "category = '$_REQUEST[product_cat]' ORDER BY qty ASC")->get();

        $products = DB()->selectLoop("*", "products", "category = '$_REQUEST[product_cat]' AND branch = '$branch' ORDER BY name ASC")->get();

        $body .= " <thead><tr><th>PRODUCT</th><th class='text-right' style='width: 75px;'>COST</th>";

        foreach ($units as $unit) {
            $body .= "<th class='text-right' style='width: 75px;'>" . $unit['name'] . "</th>";
        }

        $body .= "<th class='text-right' style='width: 100px;'>BALANCE</th>";
        $body .= "</tr></thead><tbody>";

        foreach ($products as $product) {
            $body .= "<tr>";
            $body .= "<td>" . $product['name'] . "</td>";
            $body .= "<td class='text-right'>" . $product['selling_price'] . "</td>";
            $balance = 0;
            foreach ($units as $unit) {
                $perUnit = $this->getInvPerUnit($_REQUEST['date'], $branch, $product['id'], $unit['id']);
                $body .= "<td class='text-right'>" . $perUnit . "</td>";
                $balance += ($perUnit * $unit['qty']) * $product['selling_price'];
            }

            $body .= "<td class='text-right'>" . $balance . "</td>";
            $body .= "</tr>";
        }

        $body .= "</tbody>";

        echo $body;
    }

    public function getInvPerUnit($invdate, $branch, $product, $unit)
    {
        $inResult = $this->inINV($invdate, $branch, $product, $unit);
        $outResult = $this->outINV($invdate, $branch, $product, $unit);

        return $inResult - $outResult;
    }

    public function inINV($invdate, $branch, $product, $unit)
    {
        $amout = 0;

        $rrResult = DB()->query("SELECT rrd.`product_id`, rrd.`unit`, SUM(rrd.`qty`) AS inv_in_qty FROM `receiving_header` AS rrh, `receiving_detail` AS rrd WHERE rrh.`id` = rrd.`receiving_header_id` AND rrh.`finish_date` <= '$invdate' AND rrh.`branch` = '$branch' AND rrd.`product_id` = '$product' AND rrd.`unit` = '$unit' AND rrh.`status` = 1 GROUP BY rrd.`unit`", "Y")->get();
        foreach ($rrResult as $rr) {
            $amout += $rr['inv_in_qty'];
        }

        $repackResult = DB()->query("SELECT to_item AS product_id, to_unit AS unit, SUM(to_qty) AS inv_in_qty FROM product_repack WHERE `date` <= '$invdate' AND branch = '$branch' AND to_item = '$product' AND to_unit = '$unit' AND `status` = 1 GROUP BY to_unit", "Y")->get();
        foreach ($repackResult as $rpck) {
            $amout += $rpck['inv_in_qty'];
        }

        return $amout;
    }

    public function outINV($invdate, $branch, $product, $unit)
    {
        $amout = 0;
        $repackResult = DB()->query("SELECT from_item AS product_id, from_unit AS unit, SUM(from_qty) AS inv_in_qty FROM product_repack WHERE `date` <= '$invdate' AND branch = '$branch' AND from_item = '$product' AND from_unit = '$unit' AND `status` = 1 GROUP BY from_unit", "Y")->get();
        foreach ($repackResult as $rpck) {
            $amout += $rpck['inv_in_qty'];
        }

        return $amout;
    }
}
