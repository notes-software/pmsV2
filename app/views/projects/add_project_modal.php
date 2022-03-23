<div class="modal fade" id="modal-add-project">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create a new project</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="">
                        <div class="form-group">
                            <label for="project_name_modal">Project name</label>
                            <input type="text" id="project_name_modal" class="form-control form-control-alternative" placeholder="My awesome project" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="project_cost">Cost Estimate</label>
                            <input type="number" id="project_cost" class="form-control form-control-alternative" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="project_deadline">Deadline</label>
                            <input type="date" id="project_deadline" class="form-control form-control-alternative" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="project_description_modal">Project description (optional)</label>
                            <textarea class="form-control form-control-alternative" id="project_description_modal" rows="4" placeholder="Description format" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="saveNewProject()">Create project</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>