<div class="modal fade bd-example-modal-md" id="edit_note_modal">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <input type="hidden" id="note_id" name="note_id">
            <div class="modal-body">

                <div class="card-title note-title note-content mb-2" rows="1" contenteditable="false" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 20px; font-family: inherit;white-space: pre-wrap;outline: none;-webkit-user-modify: read-write-plaintext-only;" id="update_note_title"></div>

                <div class="card-text note-content mb-2" rows="1" contenteditable="true" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 14px; font-family: inherit;white-space: pre-wrap;outline: none;-webkit-user-modify: read-write-plaintext-only;" id="update_note_content"></div>

                <!-- <div class="col-md-12 mt-1">
                    <div class="row"><span class="badge badge-secondary">work</span></div>
                </div> -->

                <div class="col-md-12">
                    <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">
                        <div class="btn-group" id="modal_btns">

                        </div>

                        <div class="btn-group ml-1">
                            <a class="btn btn-default btn-xs" data-dismiss="modal">Close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>