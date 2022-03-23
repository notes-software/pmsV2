<div class="modal fade" id="modal-approve-remarks" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document" style="width: 30%;">
        <div class="modal-content">

            <div class="modal-header" style="padding-bottom: 0px;">
                <h6 class="modal-title" id="modal-title-default">Request remarks</h6>
            </div>

            <div class="modal-body" style="overflow-y: auto; height: 210px;">
                <div class="row">
                    <input type="hidden" id="rb_request_id">
                    <div class="col" style="">
                        <div class="form-group mb-0">
                            <label for="rb_remarks">Remarks:</label>
                            <textarea class="form-control form-control-alternative" id="rb_remarks" rows="5" placeholder="your remarks" style="resize: none;"></textarea>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="completeApprovalRequest()">Proceed approve</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>