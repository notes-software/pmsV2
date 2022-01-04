<script>
    function printDiv(container) {
        var printContents = document.getElementById(container).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<style media="print" type="text/css">
    .page {
        background-color: red !important;
        color: #fff !important;
    }
</style>
<style type="text/css">
    @media print {
        .btn {
            display: none !important;
        }

        #hr_sep {
            display: none !important;
        }

        @page {
            size: 9in 14in;
            padding: 0px;
            margin: 0px;
            font-size: 12px;

        }
    }
</style>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='icon' href='<?= public_url('/favicon.ico') ?>' type='image/ico' />
    <title>Print PO</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/css/highlighter.css') ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/toastr/toastr.min.css') ?>">
    <link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/sweetalert2/bootstrap-4.min.css') ?>">

    <style>
        @font-face {
            font-family: Nunito;
            src: url("<?= public_url('/assets/adminlte/fonts/Nunito-Regular.ttf') ?>");
        }

        body {
            font-weight: 300;
            font-size: 14px;
            font-family: Nunito;
            color: #26425f;
            background: #eef1f4;
        }

        .select2-container .select2-selection--single {
            height: 40px;
        }

        .nav-sidebar.nav-child-indent .nav-treeview {
            transition: padding .3s ease-in-out;
            padding-left: 1rem;
        }

        .table td,
        .table th {
            font-size: 14px;
            padding: 9px;
        }

        .content-wrapper {
            height: auto;
        }
    </style>

    <!-- jQuery -->
    <script src="<?= public_url('/assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
</head>

<body>
    <div class="container mt-4" id="pobin">
        <div class="col-md-12" style="padding-top: 5%;">
            <h2 class="text-center">PURCHASE ORDER</h2>
            <div class="col-md-12 d-flex" style="flex-direction: row;justify-content: space-between;">
                <div>
                    <p>Supplier: <?= $purchase['supplier']['name'] ?></p>
                    <p>Address: <?= $purchase['supplier']['address'] ?></p>
                    <p>Remarks: <?= $purchase['remarks'] ?></p>
                </div>
                <div>
                    <p>Date: <?= date('Y-m-d', strtotime($purchase['date'])) ?></p>
                    <p>PO no: <?= $purchase['ref_code'] ?></p>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="no-sort" style="width: 15px;"></th>
                            <th>PRODUCT</th>
                            <th style="width: 90px;">UNIT</th>
                            <th class='text-right' style="width: 90px;">QTY</th>
                            <th class='text-right' style="width: 100px;">COST</th>
                            <th class='text-right' style="width: 100px;">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $skin_body = "";
                        $count = 1;
                        $grandTotal = 0;
                        if (!empty($purchase['purchase_detail'][0])) {
                            foreach ($purchase['purchase_detail'] as $item) {
                                $skin_body .= '<tr>';
                                $skin_body .= '<td class="no-sort text-center" style="width: 15px;">' . $count++ . '</td>';
                                $skin_body .= "<td>" . getProductName($item['product']) . "</td>";
                                $skin_body .= "<td>" . $item['unit'] . "</td>";
                                $skin_body .= "<td class='text-right'>" . number_format($item['qty'], 2) . "</td>";
                                $skin_body .= "<td class='text-right'>" . number_format($item['price'], 2) . "</td>";
                                $skin_body .= "<td class='text-right'>" . number_format($item['qty'] * $item['price'], 2) . "</td>";
                                $skin_body .= "</tr>";

                                $grandTotal += $item['qty'] * $item['price'];
                            }
                        } else {
                            $skin_body .= '<tr>';
                            $skin_body .= '<td class="no-sort text-center" style="width: 15px;">' . $count++ . '</td>';
                            $skin_body .= "<td>" . getProductName($purchase['purchase_detail']['product']) . "</td>";
                            $skin_body .= "<td>" . $purchase['purchase_detail']['unit'] . "</td>";
                            $skin_body .= "<td class='text-right'>" . number_format($purchase['purchase_detail']['qty'], 2) . "</td>";
                            $skin_body .= "<td class='text-right'>" . number_format($purchase['purchase_detail']['price'], 2) . "</td>";
                            $skin_body .= "<td class='text-right'>" . number_format($purchase['purchase_detail']['qty'] * $purchase['purchase_detail']['price'], 2) . "</td>";
                            $skin_body .= "</tr>";

                            $grandTotal += $purchase['purchase_detail']['qty'] * $purchase['purchase_detail']['price'];
                        }

                        $skin_body .= '<tr><td colspan="5" style="text-align: right;"><b>TOTAL:</b></td><td style="text-align: right;">' . number_format($grandTotal, 2) . '</td></tr>';

                        echo $skin_body;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            printDiv('pobin');
        });
    </script>
</body>

</html>