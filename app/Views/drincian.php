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
                            <li class="breadcrumb-item active">Rincian Kegiatan</li>
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
                            <h5>Daftar Rincian Kegiatan</h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label for="horizontalInput1" class="col-sm-4 form-label align-self-center">Nama
                                    Rincian</label>
                                <div class="col-sm-8">
                                    <select id="idRincian" name="idRincian" class="custom-select" required>

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
                                <th>RUK</th>
                                <th>RPK</th>

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
                url: "<?php echo base_url('rincian/getrincian') ?>",
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

            // table = $('#data_table').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: {
            //         url: '<?= base_url($controller) ?>/custom_filter',
            //         data: function(d) {
            //             d.country = $('#idRincian').val();
            //         }
            //     },
            // });

            // // $('#country').change(function(event) {
            // //     table.ajax.reload();
            // // });


            $('#idRincian').on('select2:select', function(e) {
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
                        id: $('#idRincian').val()
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