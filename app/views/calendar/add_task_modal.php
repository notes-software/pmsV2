<div class="modal fade" id="calendar_add_task_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto; height: 430px;">
                <input type="hidden" id="inputDate">
                <div class="row">

                    <div class="col">
                        <div class="form-group">
                            <label for="task_date"><i class="far fa-clock"></i> <span id="calendarDate"></span></label>
                        </div>
                        <div class="form-group">
                            <label for="task_date">Due date</label>
                            <input type="date" id="cal_task_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="cal_project">Project</label>
                            <select name="cal_project" id="cal_project" class="form-control">
                                <option value="PERSONAL">Personal</option>
                                <?php
                                // dd($projects);
                                foreach ($projects as $project) :
                                    $percentage = getProjectPercentage(getFinishTask($project['project_code']), getTotalTask($project['project_code']));

                                    if (!empty($project['projects'])) {
                                        $projData = (!empty($project['projects'][0])) ? $project['projects'][0] : $project['projects'];
                                ?>
                                        <option value="<?= $projData['projectCode'] ?>"><?= $projData['projectName'] ?></option>
                                <?php }
                                endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cal_task_status">Priority status</label>
                            <select name="cal_task_status" id="cal_task_status" class="form-control">
                                <option value="0">Low</option>
                                <option value="1">Medium</option>
                                <option value="2">High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="task_title">Title</label>
                            <input type="text" id="task_title" class="form-control" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="cal_task_description">Task description</label>
                            <!-- <textarea class="form-control" id="task_description" rows="4" placeholder="Description format" style="resize: none;"></textarea> -->
                            <div style="font-size: 16px;font-family: myFirstFont;word-break: break-word;white-space: pre-wrap;color: #4e4e4e; margin-bottom: 5px;line-height: 22px;-webkit-user-modify: read-write-plaintext-only;outline: -webkit-focus-ring-color auto 0px;background-color: #e6e6e6;padding: 8px;border-radius: 4px;min-height: 180px;" contenteditable="true" id="cal_task_description" placeholder="Description format"></div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="addNewCalendarTask()" class="btn btn-success">Create Task</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>