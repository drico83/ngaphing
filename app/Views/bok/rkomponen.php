<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap5.min.css">
<title>ePlanning - Realisasi Komponen</title>
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
                            <li class="breadcrumb-item active">Realisasi Komponen</li>
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
                    <div class="row d-flex justify-content-between">
                        <div class="col-sm-4">
                            <h5>Realisasi Komponen Kegiatan</h5>
                        </div>
                        <div class="col-sm-8">
                            <div class="row">
                                <label for="horizontalInput1" class="col-sm-2 form-label align-self-center">Nama
                                    Komponen</label>
                                <div class="col-sm-4">
                                    <select id="idRincian" name="idRincian" class="custom-select" required>

                                    </select>
                                </div>

                                <label for="horizontalInput1"
                                    class="col-sm-1 form-label align-self-center">Bulan</label>
                                <div class="col-sm-2">
                                    <select id="bulan" name="bulan" class="custom-select">
                                    </select>
                                </div>
                                <label for="horizontalInput1" class="col-sm-1 form-label align-self-center">s.d.
                                    Bulan</label>
                                <div class="col-sm-2">
                                    <select id="bulan2" name="bulan2" class="custom-select">
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
                                <th>RPK</th>
                                <th>REALISASI</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2"></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
                url: "<?php echo base_url('komponen/getkomponen') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#idRincian').html(response.data);
                        $('#idRincian').select2({
                            width: "100%",
                        });

                    }

                }
            });


            $('#idRincian').on('select2:select', function(e) {
                if ($('#bulan').val() != 0) {
                    $('#data_table').DataTable().clear().destroy();
                    tabel();
                };

            });

            $.ajax({
                type: "get",
                url: "<?php echo base_url('bulan/getSelect') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#bulan').html(response.data);
                        $('#bulan').select2({
                            width: "100%",

                        });
                        $('#bulan2').html(response.data);
                        $('#bulan2').select2({
                            width: "100%",

                        });
                    }
                }
            });


            $('#bulan').on('select2:select', function(e) {
                $('#bulan2').val($('#bulan').val());
                $('#bulan2').change();
                $('#data_table').DataTable().clear().destroy();
                tabel()
            });

            $('#bulan2').on('select2:select', function(e) {
                $('#data_table').DataTable().clear().destroy();
                tabel();
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
                        id: $('#idRincian').val(),
                        bulan: $('#bulan').val(),
                        bulan2: $('#bulan2').val()
                    }
                },

                "columnDefs": [
                    // {
                    //     "targets": [1],
                    //     "visible": false
                    // },
                    {
                        "targets": [2, 3],
                        "render": $.fn.dataTable.render.number('.', ',', 0),
                        "className": 'text-right'
                    }
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // computing column Total of the complete result 

                    var realisasi = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var realisasi2 = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var numberRenderer = $.fn.dataTable.render.number('.', '.', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Total');
                    $(api.column(2).footer()).html(numberRenderer(realisasi));
                    $(api.column(3).footer()).html(numberRenderer(realisasi2));


                },
            });
        }
        </script>

        <?= $this->endSection() ?>