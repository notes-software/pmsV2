<a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
</a>

</div>

<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
    </div>
    <!-- Default to the left -->
    <strong>&copy; 2021-<?= date('Y'); ?> <a href="https://sprnva.space/">Sprnva</a>.</strong> All rights reserved
</footer>

</div>

<div id="modalLoader" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style='border-radius: 15px;'>
            <div class="modal-body">
                <h4 style="margin: 0px;">
                    <center><span class='fa fa-spin fa-spinner'></span> <span id='page_loader_content'></span></center>
                </h4>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap 4 -->
<script src="<?= public_url('/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="<?= public_url('/assets/sprnva/js/multiple-select.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/datatables/jquery.dataTables.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/toastr/toastr.min.js') ?>"></script>

<!-- AdminLTE App -->
<script src="<?= public_url('/assets/adminlte/js/adminlte.min.js') ?>"></script>

<script type="text/javascript">
    $('.select2').select2();
</script>
</body>

</html>