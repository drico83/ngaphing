<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('judul') ?>

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Dokumentasi</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">BOK</a></li>
                            <li class="breadcrumb-item active">Cetak RKAP SIPD</li>
                        </ol>
                    </div>
                    <!--end col-->
                    <div class="col-auto align-self-center">

                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <?= $this->endSection() ?>

    <?= $this->section("isi") ?>
    <!-- Main content -->
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-4">
                            <h5>Cetak RKAP SIPD</h5>
                        </div>
                        <div class="col-sm-8">
                            <form action="<?= base_url() ?>/cetakrkap/cetak" method="post" target="_blank">
                                <div class="row">
                                    <label for="horizontalInput1" class="col-sm-2 form-label align-self-center">Nama
                                        Puskesmas</label>
                                    <div class="col-sm-3">
                                        <select id="pkm" name="pkm" class="custom-select">
                                        </select>
                                    </div>
                                    <label for="horizontalInput1" class="col-sm-2 form-label align-self-center">Nama
                                        Sub Kegiatan</label>
                                    <div class="col-sm-3">
                                        <select id="prog" name="prog" class="custom-select mr-5">

                                        </select>
                                    </div>

                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary">Cetak RKAP</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- /.card-header -->
                <div class="card-body">

                    <div id="tampiltabel" class="table-responsive">

                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <!--  -->
            <!-- /ADD modal content -->
        </div><!-- container -->


        <?= $this->endSection() ?>
        <!-- /.content -->


        <!-- page script -->
        <?= $this->section("js") ?>
        <script src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js"></script>


        <script>
        $(document).ready(function() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url($controller . '/getupt') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#pkm').html(response.data);
                        $('#pkm').select2({
                            width: "100%",

                        });
                    }
                }
            });
            $.ajax({
                type: "get",
                url: "<?php echo base_url('cetakrkap/getrka') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#prog').html(response.data);
                        $('#prog').select2({
                            width: "100%",
                        });
                    }
                }
            });

            $('#pkm').on('select2:select', function(e) {
                preview();
            });

            $('#prog').on('select2:select', function(e) {
                preview();
            });

        });

        function preview() {
            $.ajax({
                type: "post",
                data: {
                    pkm: $('#pkm').val(),
                    prog: $('#prog').val()
                },
                url: "<?php echo base_url($controller . '/tampil') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#tampiltabel').html(response.data);
                        $('#judul').html('Rencana Anggaran Biaya Puskesmas ' + response.puskesmas);
                    }
                }
            });
        }
        </script>
        <?= $this->endSection() ?>