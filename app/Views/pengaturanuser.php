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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                            <li class="breadcrumb-item active">Atur User</li>
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
                    <h5>Daftar User</h5>
                    <!-- <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="add()"
                        title="Tambah Penyedia">
                        <i class="fas fa-plus me-2"></i> Tambah User</button> -->
                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Email</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Nomor Telepon</th>
                                <th>Jenis User</th>
                                <th>Nama Puskesmas</th>
                                <th>Status Aktivasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah Data Pengguna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" class="pl-3 pr-3">

                                <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                    maxlength="11" required>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="name"> Nama Lengkap: </label>
                                    <div class="col-md-9">
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Nama Lengkap" maxlength="100">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="username"> Username: </label>
                                    <div class="col-md-9">
                                        <input type="text" id="username" name="username" class="form-control"
                                            placeholder="Username" maxlength="30">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="puskesmas"> Nama Puskesmas: </label>
                                    <div class="col-md-9">
                                        <select id="puskesmas" name="puskesmas" class="custom-select">

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="phone"> Nomor Telepon: </label>
                                    <div class="col-md-9">
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            placeholder="Nomor Telepon" maxlength="100">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="active"> Status Aktivasi: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-md-9">
                                        <select id="active" name="active" class="custom-select" required>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="grup"> Grup: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <select id="grup" name="grup" class="custom-select" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">

                                    <button type="button" class="btn btn-outline-danger"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success" id="edit-form-btn">Simpan</button>
                                </div>
                            </form>

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
            dTable = $('#data_table').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                ajax: {
                    type: 'get',
                    url: '<?= site_url('pengaturanuser/dt') ?>'

                },
                columns: [{
                        data: 'iduser',
                        name: 'iduser',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: true
                    },
                    {
                        data: 'naleng',
                        name: 'naleng',
                        orderable: true
                    },
                    {
                        data: 'username',
                        name: 'username',
                        orderable: true
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        orderable: true
                    },
                    {
                        data: 'grup',
                        name: 'grup',
                        orderable: true
                    },
                    {
                        data: 'pkm',
                        name: 'pkm',
                        orderable: true
                    },
                    {
                        data: 'active',
                        name: 'active',
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
                "order": [
                    ['0', 'asc']
                ],

                rowCallback: function(row, data) {
                    $('td:eq(0)', row).html(data.dtindex);
                }
            });
            $('#edit-form #active').select2({
                dropdownParent: $('#edit-form'),
                width: "100%",
            });
        });



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
                    $("#edit-form #name").val(response.name);
                    $("#edit-form #username").val(response.username);
                    // $("#edit-form #puskesmas").val(response.puskesmas);
                    $("#edit-form #phone").val(response.phone);
                    // $("#edit-form #grup").val(response.group_id);
                    $("#edit-form #active option[value=" + response.active + "]").prop("selected",
                        "selected")
                    $.ajax({
                        type: "post",
                        url: "<?php echo base_url('pengaturanuser/puskesmas') ?>",
                        data: {
                            pkm: response.puskesmas
                        },

                        dataType: "json",
                        success: function(response) {
                            if (response.data) {
                                $('#edit-form #puskesmas').html(response.data);
                                $('#edit-form #puskesmas').select2({
                                    dropdownParent: $('#edit-form'),
                                    width: "100%",
                                });


                            }
                        }
                    });
                    $.ajax({
                        type: "post",
                        url: "<?php echo base_url('pengaturanuser/grup') ?>",
                        data: {
                            grup: response.group_id
                        },

                        dataType: "json",
                        success: function(response) {
                            if (response.data) {
                                $('#edit-form #grup').html(response.data);
                                $('#edit-form #grup').select2({
                                    dropdownParent: $('#edit-form'),
                                    width: "100%",
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
                                    $('#edit-form-btn').html('Update');
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
                title: 'Are you sure of the deleting process?',
                text: "You cannot back after confirmation",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
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

        function reset(id) {

            Swal.fire({
                title: 'Reset Password',
                text: "Yakin Kata Sandi akan di reset?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '<?php echo base_url($controller . '/resetpass') ?>',
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
                                    timer: 3000
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