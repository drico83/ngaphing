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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Anggaran</a></li>
                            <li class="breadcrumb-item active">RAK</li>
                        </ol>
                    </div>
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
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h5>Cetak Rencana Pelaksanaan Kegiatan</h5>
                        </div>
                        <div class="col-sm-6">
                            <form action="<?= base_url() ?>/cetakrpk/cetak" method="post" target="_blank">
                                <div class="row">
                                    <label for="horizontalInput1" class="col-sm-3 form-label align-self-center">Nama
                                        Puskesmas</label>
                                    <div class="col-sm-9">
                                        <select id="pkm" name="pkm" class="custom-select">
                                        </select>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- /.card-header -->
                <div class="card-body">

                    <div id="tampiltabel" class="table-responsive">

                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah RAK (Rencana Anggaran Kas)</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" class="pl-3 pr-3">
                                <div class="row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b1"> Januari : </label>
                                            <input type="number" id="b1" name="b1" class="form-control" placeholder="B1"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b2"> Februari : </label>
                                            <input type="number" id="b2" name="b2" class="form-control" placeholder="B2"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b3"> Maret : </label>
                                            <input type="number" id="b3" name="b3" class="form-control" placeholder="B3"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b4"> April : </label>
                                            <input type="number" id="b4" name="b4" class="form-control" placeholder="B4"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b5"> Mei : </label>
                                            <input type="number" id="b5" name="b5" class="form-control" placeholder="B5"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b6"> Juni : </label>
                                            <input type="number" id="b6" name="b6" class="form-control" placeholder="B6"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b7"> Juli : </label>
                                            <input type="number" id="b7" name="b7" class="form-control" placeholder="B7"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b8"> Agustus : </label>
                                            <input type="number" id="b8" name="b8" class="form-control" placeholder="B8"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b9"> September: </label>
                                            <input type="number" id="b9" name="b9" class="form-control" placeholder="B9"
                                                maxlength="11" number="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b10"> Oktober : </label>
                                            <input type="number" id="b10" name="b10" class="form-control"
                                                placeholder="B10" maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b11"> November : </label>
                                            <input type="number" id="b11" name="b11" class="form-control"
                                                placeholder="B11" maxlength="11" number="true">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="b12"> Desember : </label>
                                            <input type="number" id="b12" name="b12" class="form-control"
                                                placeholder="B12" maxlength="11" number="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="jrak"> Jumlah Anggaran: </label>
                                            <input type="number" id="anggaran" name="anggaran" class="form-control"
                                                placeholder="Anggaran" maxlength="11" number="true" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="jrak"> Selisih: </label>
                                            <input type="number" id="selisih" name="selisih" class="form-control"
                                                placeholder="selisih" maxlength="11" number="true" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="jrak"> Jumlah RAK: </label>
                                            <input type="number" id="jrak" name="jrak" class="form-control"
                                                placeholder="Jrak" maxlength="11" number="true" readonly="readonly">
                                        </div>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="edit-form-btn" disabled>Simpan</button>
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
        $(document).ready(function() {

            $('#b1').keyup(function(e) {
                tambah();
            });
            $('#b2').keyup(function(e) {
                tambah();
            });
            $('#b3').keyup(function(e) {
                tambah();
            });
            $('#b4').keyup(function(e) {
                tambah();
            });
            $('#b5').keyup(function(e) {
                tambah();
            });
            $('#b6').keyup(function(e) {
                tambah();
            });
            $('#b7').keyup(function(e) {
                tambah();
            });
            $('#b8').keyup(function(e) {
                tambah();
            });
            $('#b9').keyup(function(e) {
                tambah();
            });
            $('#b10').keyup(function(e) {
                tambah();
            });
            $('#b11').keyup(function(e) {
                tambah();
            });
            $('#b12').keyup(function(e) {
                tambah();
            });


            $.ajax({
                type: "get",
                url: "<?php echo base_url('upt/getupt') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#pkm').html(response.data);
                        $('#pkm').select2({
                            width: "100%",

                        });
                    }
                }
            });

            $('#pkm').on('select2:select', function(e) {
                preview();
            });

        });

        function tambah() {

            var anggaran = $('#edit-form #anggaran').val();
            var b1 = $('#edit-form #b1').val();
            var b2 = $('#edit-form #b2').val();
            var b3 = $('#edit-form #b3').val();
            var b4 = $('#edit-form #b4').val();
            var b5 = $('#edit-form #b5').val();
            var b6 = $('#edit-form #b6').val();
            var b7 = $('#edit-form #b7').val();
            var b8 = $('#edit-form #b8').val();
            var b9 = $('#edit-form #b9').val();
            var b10 = $('#edit-form #b10').val();
            var b11 = $('#edit-form #b11').val();
            var b12 = $('#edit-form #b12').val();
            total = parseInt(b1) + parseInt(b2) + parseInt(b3) + parseInt(b4) + parseInt(
                b5) + parseInt(b6) + parseInt(b7) + parseInt(b8) + parseInt(b9) + parseInt(b10) + parseInt(
                b11) + parseInt(b12);
            $('#edit-form #jrak').val(total);
            $('#edit-form #selisih').val(total - anggaran);
            var selisih = total - anggaran;
            if (selisih != 0) {
                $('#edit-form-btn').prop('disabled', true)
            } else {
                $('#edit-form-btn').prop('disabled', false)
            }
        }

        function preview() {
            $.ajax({
                type: "post",
                data: {
                    pkm: $('#pkm').val()
                },
                url: "<?php echo base_url($controller . '/tampil') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#tampiltabel').html(response.data);
                        $('#judul').html('Rencana Anggaran Biaya Puskesmas ' + response.puskesmas);
                        $('#expo').attr("href", "<?= base_url($controller . '/expo') ?>?pkm=" + response
                            .pkm);

                    }
                }
            });
        }

        function edit(id) {
            $.ajax({
                url: '<?php echo base_url('rak/getOne') ?>',
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
                    $("#edit-form #b1").val(response.b1);
                    $("#edit-form #b2").val(response.b2);
                    $("#edit-form #b3").val(response.b3);
                    $("#edit-form #b4").val(response.b4);
                    $("#edit-form #b5").val(response.b5);
                    $("#edit-form #b6").val(response.b6);
                    $("#edit-form #b7").val(response.b7);
                    $("#edit-form #b8").val(response.b8);
                    $("#edit-form #b9").val(response.b9);
                    $("#edit-form #b10").val(response.b10);
                    $("#edit-form #b11").val(response.b11);
                    $("#edit-form #b12").val(response.b12);
                    $("#edit-form #jrak").val(response.jrak);
                    $("#edit-form #anggaran").val(response.harga_total);

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
                                url: '<?php echo base_url('rak/edit') ?>',
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
                                            preview();
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
        </script>

        <?= $this->endSection() ?>