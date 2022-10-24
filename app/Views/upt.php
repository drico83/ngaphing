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
                            <li class="breadcrumb-item active">Setting UPT</li>
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
                    <h5>Data UPT</h5>
                    <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="save()"
                        title="Tambah Penyedia">
                        <i class="fas fa-plus me-2"></i> Tambah Setting UPT</button>

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode UPT</th>
                                <th>Nama UPT</th>
                                <th>Kepala</th>
                                <th>NIP Kepala</th>
                                <th>Kasubag TU</th>
                                <th>NIP Kasubag TU</th>

                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /Main content -->
            <!-- ADD modal content -->

            <div id="data-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="info-header-modalLabel">Setting UPT</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!--end modal-header-->
                        <div class="modal-body">
                            <form id="data-form" class="pl-3 pr-3">
                                <div class="row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="4" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="kode" class="col-sm-4 form-label align-self-center mb-lg-0">Kode
                                                UPT: <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="kode" name="kode" class="form-control"
                                                    placeholder="Kode UPT" minlength="0" maxlength="75" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="pkm" class="col-sm-4 form-label align-self-center mb-lg-0">Nama
                                                UPT: <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="pkm" name="pkm" class="form-control"
                                                    placeholder="Nama UPT" minlength="0" maxlength="255" required>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="kapus"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Kepala: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="kapus" name="kapus" class="form-control"
                                                    placeholder="Kepala" minlength="0" maxlength="100" required>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="nip_kapus"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">NIP Kepala: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="nip_kapus" name="nip_kapus" class="form-control"
                                                    placeholder="NIP Kepala" minlength="0" maxlength="20" required>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="katu"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Kasubag TU: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="katu" name="katu" class="form-control"
                                                    placeholder="Kasubag TU" minlength="0" maxlength="100" required>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="nip_katu"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">NIP Kasubag TU:
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="nip_katu" name="nip_katu" class="form-control"
                                                    placeholder="NIP Kasubag TU" minlength="0" maxlength="20" required>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                        </div>
                        <!--end modal-body-->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-soft-primary btn-sm"
                                id="form-btn"><?= lang("App.save") ?></button>
                            <button type="button" class="btn btn-soft-secondary btn-sm"
                                data-bs-dismiss="modal">Batal</button>
                        </div>
                        <!--end modal-footer-->
                        </form>
                    </div>
                    <!--end modal-content-->
                </div>
                <!--end modal-dialog-->
            </div>
            <!--end modal-->
            <!--  -->
            <!-- /ADD modal content -->
        </div><!-- container -->


        <?= $this->endSection() ?>
        <!-- /.content -->


        <!-- page script -->
        <?= $this->section("js") ?>
        <!-- SweetAlert2 -->
        <script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>
        <!-- jquery-validation -->
        <script src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>

        <script>
        // dataTables
        $(function() {
            var table = $('#data_table').removeAttr('width').DataTable({

                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '<?php echo base_url($controller . "/basic") ?>',
                    method: 'POST'
                },
                columnDefs: [{
                        targets: -1,
                        orderable: false

                    }, {
                        targets: 0,
                        orderable: false
                    }, //target -1 means last column
                ],
                lengthChange: true,

                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                lengthMenu: [
                    [10, 50, 100, -1],
                    ['10', '50 ', '100 ', 'Semua']
                ],
            });
        });

        var urlController = '';
        var submitText = '';

        function getUrl() {
            return urlController;
        }

        function getSubmitText() {
            return submitText;
        }

        function save(id) {
            // reset the form 
            $("#data-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
            if (typeof id === 'undefined' || id < 1) { //add
                urlController = '<?= base_url($controller . "/add") ?>';
                submitText = '<?= lang("App.save") ?>';
                $('#model-header').removeClass('bg-info').addClass('bg-success');
                $("#info-header-modalLabel").text('<?= lang("App.add") ?> Setting UPT');
                $("#form-btn").text(submitText);
                $('#data-modal').modal('show');
            } else { //edit
                urlController = '<?= base_url($controller . "/edit") ?>';
                submitText = '<?= lang("App.update") ?>';
                $.ajax({
                    url: '<?php echo base_url($controller . "/getOne") ?>',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#model-header').removeClass('bg-success').addClass('bg-info');
                        $("#info-header-modalLabel").text('<?= lang("App.edit") ?> Setting UPT');
                        $("#form-btn").text(submitText);
                        $('#data-modal').modal('show');
                        //insert data to form
                        $("#data-form #id").val(response.id);
                        $("#data-form #kode").val(response.kode);
                        $("#data-form #pkm").val(response.pkm);
                        $("#data-form #kapus").val(response.kapus);
                        $("#data-form #nip_kapus").val(response.nip_kapus);
                        $("#data-form #katu").val(response.katu);
                        $("#data-form #nip_katu").val(response.nip_katu);


                    }
                });
            }
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
                    var form = $('#data-form');
                    $(".text-danger").remove();
                    $.ajax({
                        // fixBug get url from global function only
                        // get global variable is bug!
                        url: getUrl(),
                        type: 'post',
                        data: form.serialize(),
                        cache: false,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(response) {
                            if (response.success === true) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    $('#data_table').DataTable().ajax.reload(null,
                                            false)
                                        .draw(
                                            false);
                                    $('#data-modal').modal('hide');
                                })
                            } else {
                                if (response.messages instanceof Object) {
                                    $.each(response.messages, function(index, value) {
                                        var ele = $("#" + index);
                                        ele.closest('.form-control')
                                            .removeClass('is-invalid')
                                            .removeClass('is-valid')
                                            .addClass(value.length > 0 ? 'is-invalid' :
                                                'is-valid');
                                        ele.after('<div class="invalid-feedback">' +
                                            response
                                            .messages[index] + '</div>');
                                    });
                                } else {
                                    Swal.fire({
                                        toast: false,
                                        position: 'bottom-end',
                                        icon: 'error',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 3000
                                    })

                                }
                            }
                            $('#form-btn').html(getSubmitText());
                        }
                    });
                    return false;
                }
            });

            $('#data-form').validate({

                //insert data-form to database

            });
        }



        function remove(id) {
            Swal.fire({
                title: "<?= lang("App.remove-title") ?>",
                text: "<?= lang("App.remove-text") ?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?= lang("App.confirm") ?>',
                cancelButtonText: '<?= lang("App.cancel") ?>'
            }).then((result) => {

                if (result.value) {
                    $.ajax({
                        url: '<?php echo base_url($controller . "/remove") ?>',
                        type: 'post',
                        data: {
                            id: id
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.success === true) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
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
                                    toast: false,
                                    position: 'bottom-end',
                                    icon: 'error',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 3000
                                })
                            }
                        }
                    });
                }
            })
        }
        </script>


        <?= $this->endSection() ?>