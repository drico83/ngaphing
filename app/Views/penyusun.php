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
                        <h4 class="page-title">Pengaturan</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
                            <li class="breadcrumb-item active">Penyusun Kode Belanja</li>
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
                <div class="card-header d-flex justify-content-between">
                    <h5>Daftar Penyusun Kode Belanja</h5>

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Sub Komponen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Komponen Belanja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add-form" class="pl-3 pr-3">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="idSubkomponen" class="col-sm-2 col-form-label"> Nama Sub Komponen: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <input id="namaSubkomponen" name="namaSubkomponen" class="form-control"
                                            disabled>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="idKodeBelanja" class="col-sm-2 col-form-label"> Kode Komponen Belanja:
                                        <span class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <select id="idKodeBelanja" name="idKodeBelanja" class="custom-select" required>

                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success" id="add-form-btn">Simpan</button>
                                </div>
                            </form>
                            <hr>
                            <div class="table-responsive pl-3 pr-3">
                                <table id="data_table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode Rekening</th>
                                            <th>Objek Belanja</th>
                                            <th>Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

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
            dTable = $('#data_table2').DataTable({
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
                        data: 'nama_subkomponen',
                        name: 'nama_subkomponen',
                        orderable: true


                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-center nowrap'
                    }
                ],
                order: [
                    ['0', 'asc']
                ],

                lengthChange: true,

                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                lengthMenu: [
                    [10, 50, 100],
                    ['10', '50 ', '100 ']
                ],

                rowCallback: function(row, data) {
                    $('td:eq(0)', row).html(data.dtindex);
                }
            });

        });



        function getkodrek() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('kodebelanja/getkodebelanja') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-form #idKodeBelanja').html(response.data);
                        $('#add-form #idKodeBelanja').select2({
                            dropdownParent: $('#add-form'),
                            width: "100%",

                        });
                    }
                }
            });
        }

        function getsubkomponen() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('subkomponen/getsubkomponen') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-form #idSubkomponen').html(response.data);
                        $('#add-form #idSubkomponen').select2({
                            dropdownParent: $('#add-form'),
                            width: "100%",

                        });
                    }
                }
            });
        }

        function add(id) {
            var sub = id;
            // reset the form 
            $("#add-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
            getkodrek();

            $('#data_table').DataTable().destroy();
            $('#data_table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": {
                    "url": '<?php echo base_url($controller . '/getobjek') ?>',
                    "type": "post",
                    "data": {
                        sub: sub
                    },
                    "dataType": "json",
                    async: "true"
                }
            });

            $.ajax({
                type: "post",
                url: "<?php echo base_url('subkomponen/getOne') ?>",
                data: {
                    id: sub
                },
                dataType: "json",
                success: function(response) {
                    $("#namaSubkomponen").val(response.nama_subkomponen);
                }
            });

            $('#add-modal').modal('show');
            // submit the add from 
            $.validator.setDefaults({
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorElement: 'div ',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if ($(element).is('.select')) {
                        element.next().after(error);
                    } else if (element.hasClass('select2')) {
                        //error.insertAfter(element);
                        error.insertAfter(element.next());
                    } else if (element.hasClass('selectpicker')) {
                        error.insertAfter(element.next());
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function(form) {

                    var form = $('#add-form');
                    // remove the text-danger
                    $(".text-danger").remove();

                    $.ajax({
                        url: '<?php echo base_url($controller . '/add') ?>',
                        type: 'post',
                        data: {
                            idSubkomponen: sub,
                            idKodeBelanja: $('#add-form #idKodeBelanja').val()
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(response) {

                            if (response.success === true) {

                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'success',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    $('#data_table').DataTable().ajax.reload(null,
                                            false)
                                        .draw(false);

                                })

                            } else {

                                if (response.messages instanceof Object) {
                                    $.each(response.messages, function(index, value) {
                                        var id = $("#" + index);

                                        id.closest('.form-control')
                                            .removeClass('is-invalid')
                                            .removeClass('is-valid')
                                            .addClass(value.length > 0 ? 'is-invalid' :
                                                'is-valid');

                                        id.after(value);

                                    });
                                } else {
                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'error',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })

                                }
                            }
                            $('#add-form-btn').html('Simpan');
                        }
                    });

                    return false;
                }
            });
            $('#add-form').validate();
        }


        function remove(id) {
            Swal.fire({
                title: 'Hapus Kode Rekening ',
                text: "Yakin akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.value) {
                    $.ajax({
                        url: '<?php echo base_url($controller . '/remove') ?>',
                        type: 'post',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.success === true) {
                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'success',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    $('#data_table').DataTable().ajax.reload(null, false)
                                        .draw(
                                            false);
                                })
                            } else {
                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'error',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                })


                            }
                        }
                    });
                }
            })
        }
        </script>


        <?= $this->endSection() ?>