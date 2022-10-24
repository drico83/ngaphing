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
                        <h4 class="page-title">Referensi</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">sub komponen</li>
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
                <div class="card-header d-flex justify-content-between">
                    <h5>Daftar Sub Komponen Kegiatan berdasarkan Program</h5>
                    <!-- <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="add()"
                        title="Tambah Penyedia">
                        <i class="fas fa-plus me-2"></i> Tambah Sub Komponen</button> -->

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Program</th>
                                <th>Nama Rincian</th>
                                <th>Nama Komponen</th>
                                <th>Nama Sub Komponen</th>

                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!--  -->
            <!-- /ADD modal content -->
        </div><!-- container -->


        <?= $this->endSection() ?>
        <!-- /.content -->


        <!-- page script -->
        <?= $this->section("js") ?>
        <script src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js"></script>

        <script>
        var dTable;
        $(function() {
            dTable = $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    type: 'get',
                    url: '<?php echo base_url($controller . '/dt') ?>'

                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_program',
                        name: 'nama_program',
                        orderable: true,
                        visible: false
                    },
                    {
                        data: 'nama_rincian',
                        name: 'nama_rincian',
                        orderable: true,
                        visible: false
                    },
                    {
                        data: 'nama_komponen',
                        name: 'nama_komponen',
                        orderable: true,
                        visible: false
                    },
                    {
                        data: 'nama_subkomponen',
                        name: 'nama_subkomponen',
                        orderable: true
                    }
                ],
                order: [
                    ['0', 'asc']
                ],

                rowGroup: {
                    dataSrc: ['nama_program', 'nama_rincian', 'nama_komponen']
                },

                rowCallback: function(row, data) {
                    $('td:eq(0)', row).html(data.dtindex);
                }
            });

        });
        </script>


        <?= $this->endSection() ?>