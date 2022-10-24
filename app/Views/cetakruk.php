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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">RUK</a></li>
                            <li class="breadcrumb-item active">Cetak RUK</li>
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
                        <div class="col-sm-6">
                            <h5>Cetak Rencana Usulan Kegiatan</h5>
                        </div>
                        <div class="col-sm-6">
                            <form action="<?= base_url() ?>/cetakruk/cetak" method="post" target="_blank">
                                <div class="row">
                                    <label for="horizontalInput1" class="col-sm-3 form-label align-self-center">Nama
                                        Puskesmas</label>
                                    <div class="col-sm-5">
                                        <select id="pkm" name="pkm" class="custom-select">
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary">Cetak RUK</button>
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
                url: "<?php echo base_url('upt/getupt') ?>",
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
            $('#pkm').on('select2:select', function(e) {
                preview();
            });

            $('#cetak').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "post",
                    data: {
                        pkm: $('#pkm').val()
                    },
                    url: "<?php echo base_url($controller . '/cetak') ?>",
                    dataType: "json"

                });
            });
        });

        function cetak() {
            $.ajax({
                type: "post",
                data: {
                    pkm: $('#pkm').val()
                },
                url: "<?php echo base_url($controller . '/cetak') ?>",
                dataType: "json"

            });
        }

        function preview() {
            $.ajax({
                type: "post",
                data: {
                    pkm: $('#pkm').val()
                },
                url: "<?php echo base_url($controller . '/tampil') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#tampiltabel').html(response.data);
                        $('#judul').html('Rencana Usulan Kegiatan Puskesmas ' + response.puskesmas);
                    }
                }
            });
        }
        </script>

        <?= $this->endSection() ?>