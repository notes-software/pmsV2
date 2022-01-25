<div class="modal fade" id="task_info_modal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex" style="flex-direction: row;align-items: center;">
                <h6 class="modal-title" id="modal-title-default">
                    PROJECT : <span id="v_task_projectName"></span>
                </h6>
                <div class="butn">
                    <div class="btn-group">
                        <div id="markAsDone"></div>
                        <a href="#" class="btn btn-default btn-sm" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </div>

            <div class="modal-body py-0">
                <div class="row mt-2" style="height: 430px;">
                    <input type="hidden" id="task_v_id">
                    <input type="hidden" id="task_proj_code">
                    <div class="col-md-8 msg_chat_scroll" style="max-height: 410px;">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <h6 class="modal-title" id="modal-title-default">
                                    <span style="font-size: 16px; color: gray;" id="task-color"><i class="fas fa-circle" id="task-color-icon"></i></span>
                                    TASK : <span id="v_task_code"></span>
                                    <span class="ml-2">&mdash;<span id="v_task_status"></span></span>
                                </h6>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="task_v_date">Due date</label>
                                    <input type="date" id="task_v_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="v_task_prio_status">Priority status</label>
                                    <select name="v_task_prio_status" id="v_task_prio_status" class="form-control">
                                        <option value="0">Low</option>
                                        <option value="1">Medium</option>
                                        <option value="2">High</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="task_v_desc">Task description</label>
                                    <div style="font-size: 16px;font-family: myFirstFont;word-break: break-word;white-space: pre-wrap;color: #4e4e4e; margin-bottom: 5px;line-height: 22px;-webkit-user-modify: read-write-plaintext-only;outline: -webkit-focus-ring-color auto 0px;background-color: #e6e6e6;padding: 8px;border-radius: 4px;" contenteditable="true" id="v_task_desc"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="saveChangesBtn">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 msg_chat_scroll_steady" style="max-height: 365px;">

                        <div class="col-12" id="shareTaskBin">
                            <center><small>— share task to —</small></center>
                            <div class="form-group">
                                <input type="text" id="taskSearchMember" class="form-control" placeholder="enter e-mail of user" autocomplete="off">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <ul class="list-group list-group-flush list my--3 msg_chat_scroll" id="task-search-result" style="max-height: 100px;">

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 pt-1">
                            <center><small>&mdash; Task Members (<span id="member-count"></span>) &mdash;</small></center>
                            <ul class="list-group list-group-flush list" id="task_member_bin">

                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>