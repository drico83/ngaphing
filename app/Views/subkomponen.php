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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Umum</a></li>
                            <li class="breadcrumb-item active">sub komponen</li>
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
                    <h5>Daftar Sub Komponen Kegiatan</h5>
                    <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="add()"
                        title="Tambah Penyedia">
                        <i class="fas fa-plus me-2"></i> Tambah Sub Komponen</button>

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

                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Add modal content -->
            <div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah data sub komponen</h5>
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
                                    <label for="id_prog" class="col-sm-2 col-form-label"> Program pelayanan: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <select id="id_prog" name="id_prog[]" class="custom-select" multiple="multiple"
                                            required></select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="id_rincian" class="col-sm-2 col-form-label"> Rincian Kegiatan: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <select id="id_rincian" name="id_rincian" class="custom-select"
                                            required></select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="id_kom" class="col-sm-2 col-form-label"> Komponen Kegiatan: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <select id="id_kom" name="id_kom" class="custom-select" required></select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="namaSubkomponen" class="col-sm-2 col-form-label"> Nama Sub Komponen:
                                        <span class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <textarea cols="40" rows="5" id="namaSubkomponen" name="namaSubkomponen"
                                            class="form-control" placeholder="Nama Sub Komponen" required></textarea>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="add-form-btn">Tambah</button>

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
                            <h5 class="modal-title" id="exampleModalLabel">Ubah data sub komponen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" class="pl-3 pr-3">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="id_prog" class="col-sm-3 col-form-label"> Program pelayanan: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-9">
                                        <select id="id_prog" name="id_prog" class="custom-select" required></select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="id_rincian" class="col-sm-3 col-form-label"> Rincian Kegiatan: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-9">
                                        <select id="id_rincian" name="id_rincian" class="custom-select"
                                            required></select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="id_kom" class="col-sm-3 col-form-label"> Komponen Kegiatan: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-9">
                                        <select id="id_kom" name="id_kom" class="custom-select" required></select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="namaSubkomponen" class="col-sm-3 col-form-label"> Nama Sub Komponen:
                                        <span class="text-danger">*</span> </label>
                                    <div class="col-sm-9">
                                        <textarea cols="40" rows="5" id="namaSubkomponen" name="namaSubkomponen"
                                            class="form-control" placeholder="Nama Sub Komponen" required></textarea>
                                    </div>
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
                rowGroup: {
                    dataSrc: ['nama_program', 'nama_rincian', 'nama_komponen']
                },

                rowCallback: function(row, data) {
                    $('td:eq(0)', row).html(data.dtindex);
                }
            });

        });

        function getprog() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('program/getprogram') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-form #id_prog').html(response.data);
                        $('#add-form #id_prog').select2({
                            width: '100%',
                            dropdownParent: $('#add-form'),
                        });
                    }

                }
            });
        }

        function getrincian() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('rincian/getrincian') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-form #id_rincian').html(response.data);
                        $('#add-form #id_rincian').select2({
                            width: '100%',
                            dropdownParent: $('#add-form'),
                        });
                    }

                }
            });
        }

        function getkom() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('komponen/getkomponen') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-form #id_kom').html(response.data);
                        $('#add-form #id_kom').select2({
                            width: '100%',
                            dropdownParent: $('#add-form'),
                        });
                    }

                }
            });
        }

        function add() {
            // reset the form 
            $("#add-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
            $('#add-modal').modal('show');
            getprog();
            getrincian();
            getkom();
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
                    $("#edit-form #namaSubkomponen").val(response.nama_subkomponen);

                    $.ajax({
                        type: "post",
                        data: {
                            prog: response.id_prog
                        },
                        url: "<?php echo base_url('program/getprogram2') ?>",
                        dataType: "json",
                        success: function(response) {
                            if (response.data) {
                                $('#edit-form #id_prog').html(response.data);
                                $('#edit-form #id_prog').select2({
                                    width: '100%',
                                    dropdownParent: $('#edit-form'),
                                });
                            }

                        }
                    });

                    $.ajax({
                        type: "post",
                        data: {
                            rincian: response.id_rincian
                        },
                        url: "<?php echo base_url('rincian/getrincian') ?>",
                        dataType: "json",
                        success: function(response) {
                            if (response.data) {
                                $('#edit-form #id_rincian').html(response.data);
                                $('#edit-form #id_rincian').select2({
                                    width: '100%',
                                    dropdownParent: $('#edit-form'),
                                });
                            }

                        }
                    });

                    $.ajax({
                        type: "post",
                        data: {
                            komponen: response.id_kom
                        },
                        url: "<?php echo base_url('komponen/getkomponen') ?>",
                        dataType: "json",
                        success: function(response) {
                            if (response.data) {
                                $('#edit-form #id_kom').html(response.data);
                                $('#edit-form #id_kom').select2({
                                    width: '100%',
                                    dropdownParent: $('#edit-form'),
                                });
                            }

                        }
                    });

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
                title: 'Hapus Sub Komponen ',
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