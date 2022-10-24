<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<title>ePlanning - Blud</title>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap5.min.css">
<link href="<?= base_url('assets') ?>/animate/animate.css" rel="stylesheet" type="text/css">

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
                            <li class="breadcrumb-item active">Pendapatan BLUD Perubahan</li>
                        </ol>
                    </div>
                    <!--end col-->
                    <div class="col text-end">
                        <h4>Batas Waktu Input : </h4>
                    </div>
                    <div class="col-auto">

                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-purple rounded text-center" id="hari">12</span> <span
                                style="font-size: 12px;">Hari</span>
                        </div>
                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-pink rounded" id="jam">12</span> <span
                                style="font-size: 12px;">Jam</span>
                        </div>
                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-warning rounded" id="menit">12</span><span
                                style="font-size: 12px;">Menit</span>
                        </div>
                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-success rounded" id="detik">12</span><span
                                style="font-size: 12px;">Detik</span>
                        </div>
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
            <div class="div" id="tabel1">


                <!--end row-->
                <div class="card m4-2 card animated lightSpeedIn">
                    <div class="card-header">
                        <div class="card-header d-flex justify-content-between">
                            <h5>Proyeksi Pendapatan Perubahan</h5>
                            <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="save()"
                                title="Tambah Proyeksi">
                                <i class="fas fa-plus me-2"></i> Tambah Proyeksi</button>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>

                                        <th>Nama Puskesmas</th>
                                        <th>Pasien Umum</th>
                                        <th>Jampersal</th>
                                        <th>Kapitasi JKN</th>
                                        <th>Non kapitasi JKN</th>
                                        <th>Non kapitasi lain</th>
                                        <th>Jasa Giro</th>
                                        <th>Jumlah</th>
                                        <th>Pendapatan 2022</th>
                                        <th>kenaikan/Penurunan</th>
                                        <th>Silpa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>


                <!--end modal-dialog-->
            </div>


            <div id="data-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title m-0" id="info-header-modalLabel">Pendapatan Perubahan</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <!--end modal-header-->
                        <div class="modal-body">
                            <form id="data-form" class="pl-3 pr-3">
                                <div class="row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">


                                        <div class="mb-3 row">
                                            <label for="id_pkm"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nama
                                                Puskesmas:
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <select id="id_pkm" name="id_pkm" class="custom-select" required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="umum"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Pasien Umum:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="umum" name="umum" class="form-control"
                                                    placeholder="Pasien Umum" minlength="0" maxlength="11">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="jampersal"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Jampersal:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="jampersal" name="jampersal"
                                                    class="form-control" placeholder="Jampersal" minlength="0"
                                                    maxlength="11">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="kapitasi_jkn"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Kapitasi
                                                JKN:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="kapitasi_jkn" name="kapitasi_jkn"
                                                    class="form-control" placeholder="Kapitasi JKN" minlength="0"
                                                    maxlength="11">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="non_kapitasi"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Non
                                                kapitasi JKN:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="non_kapitasi" name="non_kapitasi"
                                                    class="form-control" placeholder="Non kapitasi JKN" minlength="0"
                                                    maxlength="11">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="non_kapitasi_lain"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Non
                                                kapitasi lain:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="non_kapitasi_lain" name="non_kapitasi_lain"
                                                    class="form-control" placeholder="Non kapitasi lain" minlength="0"
                                                    maxlength="11">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="non_kapitasi_lain"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Jasa Giro:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="jasa_giro" name="jasa_giro"
                                                    class="form-control" placeholder="jasa giro" minlength="0"
                                                    maxlength="11">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="non_kapitasi_lain"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Silpa 2021:
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="silpa" name="silpa" class="form-control"
                                                    placeholder="Silpa Tahun sebelumnya" minlength="0" maxlength="11">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <!--end modal-body-->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-soft-primary btn-sm" id="form-btn">Simpan</button>
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


        </div>
    </div><!-- container -->


    <?= $this->endSection() ?>
    <!-- /.content -->


    <!-- page script -->
    <?= $this->section("js") ?>
    <script src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js"></script>

    <script>
    //timer
    CountDownTimer("<?php echo $timer->waktu; ?>", 'hari', 'jam', 'menit', 'detik');

    function CountDownTimer(dt, id1, id2, id3, id4) {
        var end = new Date(dt);

        var _second = 1000;
        var _minute = _second * 60;
        var _hour = _minute * 60;
        var _day = _hour * 24;
        var timer;

        function showRemaining() {
            var now = new Date();
            var distance = end - now;
            var distance1 = now - end;
            if (distance1 > 0) {
                clearInterval(timer);

                document.getElementById(id1).innerHTML = 0;
                document.getElementById(id2).innerHTML = 0;
                document.getElementById(id3).innerHTML = 0;
                document.getElementById(id4).innerHTML = 0;
                return;
            }
            var days = Math.floor(distance / _day);
            var hours = Math.floor((distance % _day) / _hour);
            var minutes = Math.floor((distance % _hour) / _minute);
            var seconds = Math.floor((distance % _minute) / _second);

            document.getElementById(id1).innerHTML = days;
            document.getElementById(id2).innerHTML = hours;
            document.getElementById(id3).innerHTML = minutes;
            document.getElementById(id4).innerHTML = seconds;


        }

        timer = setInterval(showRemaining, 1000);
    }
    //endtimer

    $(function() {
        var table = $('#data_table').removeAttr('width').DataTable({

            responsive: true,
            ajax: {
                url: '<?php echo base_url($controller . "/getAll") ?>',
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
            submitText = '<?= 'Simpan' ?>';
            $('#modal-header').removeClass('bg-info').addClass('bg-success');
            $("#info-header-modalLabel").text('<?= lang("App.add") ?> Pendapatan Perubahan');
            $("#form-btn").text(submitText);
            $('#data-modal').modal('show');
            $.ajax({
                type: "get",
                url: "<?= base_url('upt/getupt') ?>",
                dataType: "json",
                success: function(response) {
                    $('#data-form #id_pkm').html(response.data);
                    $('#data-form #id_pkm').select2({
                        dropdownParent: $('#data-form'),
                        width: "100%",
                    });

                }
            });
        } else { //edit
            urlController = '<?= base_url($controller . "/edit") ?>';
            submitText = '<?= 'Simpan' ?>';
            $.ajax({
                url: '<?php echo base_url($controller . "/getOne") ?>',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    $('#model-header').removeClass('bg-success').addClass('bg-info');
                    $("#info-header-modalLabel").text('Ubah Pendapatan Perubahan');
                    $("#form-btn").text(submitText);
                    $('#data-modal').modal('show');
                    //insert data to form
                    $("#data-form #id").val(response.id);
                    $("#data-form #tahun").val(response.tahun);

                    $("#data-form #umum").val(response.umum);
                    $("#data-form #jampersal").val(response.jampersal);
                    $("#data-form #kapitasi_jkn").val(response.kapitasi_jkn);
                    $("#data-form #non_kapitasi").val(response.non_kapitasi);
                    $("#data-form #non_kapitasi_lain").val(response.non_kapitasi_lain);
                    $("#data-form #jasa_giro").val(response.jasa_giro);
                    $("#data-form #silpa").val(response.silpa);
                    $.ajax({
                        type: "post",
                        url: "<?= base_url('upt/getupt') ?>",
                        data: {
                            upt: response.id_pkm
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#data-form #id_pkm').html(response.data);
                            $('#data-form #id_pkm').select2({
                                dropdownParent: $('#data-form'),
                                width: "100%",
                            });
                        }
                    });


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