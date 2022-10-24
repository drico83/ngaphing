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
                            <li class="breadcrumb-item active">Cetak RKA 2.2 </li>
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
                            <h5>Cetak RKA 2.2 </h5>
                        </div>
                        <div class="col-sm-8 text-end">

                            <a id="expo" name="expo" class="btn btn-success"><i
                                    class="fa fa-file-excel mr-1"></i>Export</a>


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

            preview();
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
                        $('#expo').attr("href", "<?= base_url($controller . '/expo') ?>");
                    }
                }
            });
        }
        </script>

        <?= $this->endSection() ?>