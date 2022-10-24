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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">RPK</a></li>
                            <li class="breadcrumb-item active">Cetak KAK</li>
                        </ol>
                    </div>
                    <!--end col-->
                    <div class="col-auto align-self-center">
                        <a href="#" class="btn btn-sm btn-outline-primary" id="Dash_Date">
                            <span class="day-name" id="Day_Name">Today:</span>&nbsp;
                            <span class="" id="Select_date">Jan 11</span>
                            <i data-feather="calendar" class="align-self-center icon-xs ms-1"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i data-feather="download" class="align-self-center icon-xs"></i>
                        </a>
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
                            <h5>Cetak Kerangka Acuan Kegiatan</h5>
                        </div>
                        <div class="col-sm-8">

                            <div class="row">
                                <label for="horizontalInput1" class="col-sm-2 form-label align-self-center">Nama
                                    Puskesmas</label>
                                <div class="col-sm-3">
                                    <select id="pkm" name="pkm" class="custom-select">
                                    </select>
                                </div>
                                <label for="horizontalInput1" class="col-sm-2 form-label align-self-center">Nama
                                    Program</label>
                                <div class="col-sm-3">
                                    <select id="prog" name="prog" class="custom-select mr-5">

                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <button onclick="cetakkak()" class="btn btn-primary">Cetak KAK</button>
                                </div>
                            </div>

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
            <div class="modal fade" id="cetak" tabindex="-1" aria-labelledby="cetak" style="display: none;"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="cetak">Cetak KAK</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!--end modal-header-->
                        <form action="<?= base_url() ?>/cetakkak/cetak" method="post" target="_blank">
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="idpkm" id="idpkm">
                                    <input type="hidden" name="program" id="program">
                                    <label for="horizontalInput1" class="col-sm-3 form-label align-self-center">Tanggal
                                        RPK</label>
                                    <div class="col-sm-9">
                                        <input type="date" name="tanggal" id="tanggal" class="form-control">
                                    </div>

                                </div>
                                <!--end row-->
                            </div>
                            <!--end modal-body-->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-soft-primary btn-sm">Cetak</button>
                                <button type="button" class="btn btn-soft-secondary btn-sm"
                                    data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                        <!--end modal-footer-->
                    </div>
                    <!--end modal-content-->
                </div>
                <!--end modal-dialog-->
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
                url: "<?php echo base_url('program/getprogram') ?>",
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

        function cetakkak() {
            $('#cetak').modal('show');
            $('#program').val($('#prog').val());
            $('#idpkm').val($('#pkm').val());

        }

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