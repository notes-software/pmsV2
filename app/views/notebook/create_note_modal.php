<div class="modal fade bd-example-modal-md" id="create_note_modal">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <input type="hidden" id="note_id" name="note_id">
            <div class="modal-body">

                <div class="card-title note-title note-content mb-2 noteEditor" rows="1" contenteditable="false" placeholder="Title" dir="ltr" id="create_note_title"></div>

                <div class="card-text note-content mb-2 noteEditor" rows="1" contenteditable="true" placeholder="Take a note…" dir="ltr" id="create_note_content" style="font-size: 14px;"></div>

                <!-- <div class="col-md-12 mt-1">
                    <div class="row"><span class="badge badge-secondary">work</span></div>
                </div> -->

                <div class="col-md-12">
                    <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">
                        <div class="btn-group" id="add_modal_btns">

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