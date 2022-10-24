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
                        <h4 class="page-title">Menu Input</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Perencanaan</a></li>
                            <li class="breadcrumb-item active">Analisa Belanja RUK</li>
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
                    <div class="row d-flex justify-content-between">
                        <div class="col-sm-6">
                            <h5>Analisa Belanja RUK</h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="horizontalInput1" class="col-sm-4 form-label align-self-center">Nama
                                    Kode Rekening</label>
                                <div class="col-sm-8">
                                    <select id="kodrek" name="kodrek" class="custom-select" required>

                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Puskesmas</th>
                                <th>Jumlah Volume</th>
                                <th>Hari Kerja 1 tahun</th>
                                <th>Kebutuhan Sumberdaya per hari</th>

                            </tr>
                        </thead>
                    </table>
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
                url: "<?php echo base_url('kodrek/getkodrek') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#kodrek').html(response.data);
                        $('#kodrek').select2({
                            width: "100%",
                        });

                    }

                }
            });




            $('#kodrek').on('select2:select', function(e) {
                // table.ajax.reload();
                $('#rincian').val($('#idRincian').val());
                $('#data_table').DataTable().clear().destroy();
                tabel()
                // dTable.ajax.reload(null, false).draw(true);
                // $('#data_table').DataTable().ajax.reload().draw(false);
            });

        });

        function tabel() {
            $('#data_table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                lengthMenu: [
                    [50, 100, -1],
                    ['50 ', '100 ', 'Semua']
                ],
                "ajax": {
                    "url": '<?php echo base_url($controller . '/getAll') ?>',
                    "type": "post",
                    "dataType": "json",
                    "data": {
                        id: $('#kodrek').val()
                    }
                },

                "columnDefs": [{
                        "targets": [2, 3],
                        "render": $.fn.dataTable.render.number('.', ',', 0),
                        "className": 'text-end'
                    },
                    {
                        "targets": [4],
                        "render": $.fn.dataTable.render.number('.', ',', 2),
                        "className": 'text-end'
                    }
                ],
            });
        }
        </script>

        <?= $this->endSection() ?>