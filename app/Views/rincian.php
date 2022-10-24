<?= $this->extend("layout/main") ?>

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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Umum</a></li>
                            <li class="breadcrumb-item active">Rincian</li>
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
                    <h5>Data Rincian Kegiatan</h5>
                    <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="add()"
                        title="Tambah Penyedia">
                        <i class="fas fa-plus me-2"></i> Tambah Rincian</button>

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Rincian</th>

                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /Main content -->
            <!-- ADD modal content -->

            <!-- Add modal content -->
            <div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah data UPT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add-form" class="pl-3 pr-3">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="4" required>
                                </div>

                                <div class="position-relative form-group">
                                    <label for="namaRincian"> Nama Rincian: <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="namaRincian" name="namaRincian" class="form-control"
                                        placeholder="Nama Rincian" maxlength="255" required>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="add-form-btn">Simpan</button>

                        </div>
                        </form>
                    </div><!-- /.modal-content -->

                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Add modal content -->
            <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah data UPT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" class="pl-3 pr-3">

                                <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                    maxlength="4" required>

                                <div class="position-relative form-group">
                                    <label for="namaRincian"> Nama Rincian: <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="namaRincian" name="namaRincian" class="form-control"
                                        placeholder="Nama Rincian" maxlength="255" required>
                                </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="edit-form-btn">Simpan</button>
                        </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!--end modal-->
            <!--  -->
            <!-- /ADD modal content -->
        </div><!-- container -->


        <?= $this->endSection() ?>
        <!-- /.content -->


        <!-- page script -->
        <?= $this->section("js") ?>
        <script>
        var dTable;
        $(function() {
            dTable = $('#data_table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    type: 'get',
                    url: '<?= site_url('rincian/dt') ?>'

                },
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_rincian',
                        name: 'nama_rincian',
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

        function add() {
            // reset the form 
            $("#add-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
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
                        data: form
                            .serialize(), // /converting the form data into array and sending it to server
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
                                    $('#add-modal').modal('hide');
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

        function edit(id) {
            $.ajax({
                url: '<?php echo base_url($controller . '/getOne') ?>',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // reset the form 
                    $("#edit-form")[0].reset();
                    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                    $('#edit-modal').modal('show');

                    $("#edit-form #id").val(response.id);
                    $("#edit-form #namaRincian").val(response.nama_rincian);

                    // submit the edit from 
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
                            var form = $('#edit-form');
                            $(".text-danger").remove();
                            $.ajax({
                                url: '<?php echo base_url($controller . '/edit') ?>',
                                type: 'post',
                                data: form.serialize(),
                                dataType: 'json',
                                beforeSend: function() {
                                    $('#edit-form-btn').html(
                                        '<i class="fa fa-spinner fa-spin"></i>');
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
                                            $('#data_table').DataTable()
                                                .ajax
                                                .reload(null, false).draw(
                                                    false);
                                            $('#edit-modal').modal('hide');
                                        })

                                    } else {

                                        if (response.messages instanceof Object) {
                                            $.each(response.messages, function(
                                                index,
                                                value) {
                                                var id = $("#" + index);

                                                id.closest('.form-control')
                                                    .removeClass(
                                                        'is-invalid')
                                                    .removeClass('is-valid')
                                                    .addClass(value.length >
                                                        0 ?
                                                        'is-invalid' :
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
                                    $('#edit-form-btn').html('Simpan');
                                }
                            });

                            return false;
                        }
                    });
                    $('#edit-form').validate();

                }
            });
        }

        function remove(id) {
            Swal.fire({
                title: 'Hapus Data Rincian ',
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