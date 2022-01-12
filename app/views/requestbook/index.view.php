<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= route('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active"><?= ucfirst($pageTitle) ?></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?= alert_msg(); ?>
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                <div class="card-tools">
                    <div class="align-left">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#log_a_request_modal">Add request</button>
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <table id="rb_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th>Requested by</th>
                        <th>Created date</th>
                        <th>Created by</th>
                        <th>Approve date</th>
                        <th>Approve by</th>
                        <th>Description</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($requests) > 0) {
                        $user_id = Auth::user('id');
                        foreach ($requests as $request) {
                            $status = ($request['status'] == 0) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>';

                            $appDate = ($request['approve_date'] == "0000-00-00 00:00:00") ? '<span style="color: orange;">pending</span>' : date("M d, Y", strtotime($request['approve_date']));

                            $appBy = ($request['approved_by'] == 0) ? '<span style="color: orange;">pending</span>' : getUserName($request['approved_by']);

                            $delBtn = ($user_id == $request['person_assigned']) ? '<a style="color: #605e5e;" onclick="deleteRequest(\'' . $request['request_id'] . '\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete"><i class="far fa-trash-alt"></i></a>' : '';

                            $approvalBtn = ($user_id == 19) ? '<a style="color: #605e5e;" onclick="approveRequest(\'' . $request['request_id'] . '\')" data-toggle="tooltip" data-placement="bottom" data-original-title="Approve"><i class=" far fa-check-circle"></i></a>' : '';
                    ?>
                            <tr>
                                <td class="no-sort text-center">
                                    <a style="color: #605e5e;" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit"><i class=" far fa-edit"></i></a>
                                </td>
                                <td class="no-sort text-center">
                                    <?= $approvalBtn ?>
                                    <?= $delBtn ?>
                                </td>
                                <td><?= $request['requested_by'] ?></td>
                                <td><?= date("M d, Y", strtotime($request['request_date'])) ?></td>
                                <td><?= getUserName($request['person_assigned']) ?></td>
                                <td><?= $appDate ?></td>
                                <td><?= $appBy ?></td>
                                <td style="white-space: pre-wrap;"><?= html_entity_decode($request['logs']) ?></td>
                                <td><?= html_entity_decode($request['remarks']) ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <!-- <div class="card-footer">
            Footer
        </div> -->
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</section>

<?php include_once __DIR__ . '/add_remarks.view.php'; ?>
<?php include_once __DIR__ . '/log_a_request.view.php'; ?>

<script type="text/javascript">
    $(function() {
        $('#rb_tbl').dataTable();
    });

    function saveNewRequestBook() {
        var requestedBy = $("#rb_requested_by").val();
        var description = $("#rb_description").val();
        if (requestedBy == "" || description == "") {
            alertMe("warning", "please fill all fields");
        } else {
            $.post(base_url + "/requestbook/save", {
                requestedBy: requestedBy,
                description: description
            }, function(data) {
                if (data == 1) {
                    alertMe("success", 'Request saved');
                    $("#log_a_request_modal").modal('hide');
                } else {
                    alertMe('danger', 'Error in saving your request.');
                }
                location.reload();
            });
        }
    }

    function approveRequest(requestid) {
        $("#rb_request_id").val(requestid);
        $("#modal-approve-remarks").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function deleteRequest(id) {
        var res = confirm("Are you sure you want to delete this request?");
        if (res) {
            $.post(base_url + "/requestbook/delete", {
                id: id
            }, function(data) {
                location.reload();
            });
        }
    }

    function completeApprovalRequest() {
        var request_id = $("#rb_request_id").val();
        var rb_remarks = $("#rb_remarks").val();
        $.post(base_url + "/requestbook/approve", {
            request_id: request_id,
            rb_remarks: rb_remarks
        }, function(data) {
            $("#modal-approve-remarks").modal('hide');
            location.reload();
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>