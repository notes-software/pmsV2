<div class="modal fade" id="modal-add-member-to-project">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invite member to Project <?= $projectDetail["projectName"] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto; height: 430px;">
                <div class="row">

                    <div class="col-12">
                        <center><small>&mdash; Invite by Group &mdash;</small></center>
                        <div class="form-group">
                            <select id="project-group-select" class="form-control">
                                <option value="">-- select group --</option>
                                <?php
                                $loopgroup = getMyGroups($userID);
                                if (count($loopgroup) > 0) {
                                    foreach ($loopgroup as $grplist) {
                                ?>
                                        <option value="<?= $grplist['team_code'] ?>"><?= $grplist['team_name'] ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-success btn-sm float-right" data-dismiss="modal" onclick="inviteSelectedGroup()">invite selected group</button>
                        </div>
                    </div>
                    <div class="col-12 mt-1">
                        <center><small>&mdash; or &mdash;</small></center>
                        <div class="form-group">
                            <input type="text" id="proj-search-people" class="form-control" placeholder="type e-mail of user, hit enter to search" autocomplete="off">
                        </div>
                        <div class="row">
                            <div class="col msg_chat_scroll" style="overflow: auto; max-height: 200px; min-height: 60px;">
                                <ul class="list-group list-group-flush list my--3" id="proj-search-result">

                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>