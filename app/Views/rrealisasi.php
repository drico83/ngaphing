<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap5.min.css">
<title>ePlanning - Realisasi</title>
<?= $this->endSection() ?>

<?= $this->section('judul') ?>
<div class="preloader">
    <div class="loading">
        <img src="<?= base_url('assets/images') ?>/loading81.gif" width="80">
        <p>Harap Tunggu ya..<br><?= user()->name ?></p>
    </div>
</div>
<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Dokumentasi</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Realisasi BOK</a></li>
                            <li class="breadcrumb-item active">Realisasi Kumulatif</li>
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
                        <div class="col-sm-4">
                            <h5>Cetak Realisasi BOK Kumulatif</h5>
                        </div>
                        <div class="col-sm-8">
                            <form action="<?= base_url() ?>/rrealisasi/cetak" method="post" target="_blank">
                                <div class="row">
                                    <label for="horizontalInput1" class="col-sm-2 form-label align-self-center">Nama
                                        Puskesmas</label>
                                    <div class="col-sm-2">
                                        <select id="pkm" name="pkm" class="custom-select">
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
                                    <div class="col-sm-2 d-grid gap-2 d-md-block">
                                        <button type="submit" class="btn btn-primary">Cetak</button>

                                        <a id="expo" name="expo" class="btn btn-success" style="display:none ;"><i
                                                class="fa fa-file-excel me-1"></i>Export</a>
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
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" class="pl-3 pr-3">
                                <div class="row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                    <input type="hidden" id="id_rpk" name="id_rpk" class="form-control"
                                        placeholder="id_rpk" maxlength="11">
                                    <input type="hidden" id="bln" name="bln" class="form-control" placeholder="bln"
                                        maxlength="11">
                                    <input type="hidden" id="tahun" name="tahun" class="form-control"
                                        placeholder="tahun" value="<?= $tahun ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="kode" class="col-sm-4 form-label align-self-center mb-lg-0">Kode
                                                Rekening <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="kodrek" name="kodrek" class="form-control"
                                                    placeholder="Kode Rekening" minlength="0" maxlength="75" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="kode"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Rincian Kegiatan
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <textarea type="text" id="rincian" name="rincian" class="form-control"
                                                    placeholder="Rincian" rows="2" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="kode"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nilai RPK
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="rpk" name="rpk" class="form-control"
                                                    placeholder="RPK" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="kode"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Jumlah Pengajuan /
                                                Realisasi
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="jumlah" name="jumlah" class="form-control"
                                                    placeholder="Jumlah Pengajuan / Realisasi" required>
                                            </div>
                                        </div>
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
        $(document).ready(function() {
            $(".preloader").fadeOut();


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

            $('#pkm').on('select2:select', function(e) {
                if ($('#bulan').val() != 0) {
                    preview();
                };

            });
            $('#bulan').on('select2:select', function(e) {
                $('#bulan2').val($('#bulan').val());
                $('#bulan2').change();
                preview();
            });

            $('#bulan2').on('select2:select', function(e) {
                preview();
            });

        });


        function preview() {
            $.ajax({
                type: "post",
                data: {
                    pkm: $('#pkm').val(),
                    bulan: $('#bulan').val(),
                    bulan2: $('#bulan2').val()
                },
                url: "<?php echo base_url($controller . '/tampil') ?>",
                dataType: "json",
                beforeSend: function() {
                    $(".preloader").fadeIn();
                },
                success: function(response) {
                    if (response.data) {
                        $(".preloader").fadeOut();
                        $('#tampiltabel').html(response.data);
                        $('#judul').html('Rencana Anggaran Biaya Puskesmas ' + response.puskesmas);
                        $('#expo').attr("href", "<?= base_url($controller . '/expo') ?>?pkm=" + response
                            .pkm + "&bulan=" + response.bulan + "&bulan2=" + response.bulan2);

                    }
                }
            });
        }

        function save(id) {
            $("#edit-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
            $('#edit-modal').modal('show');
            $('#id_rpk').val(id);
            $('#bln').val($('#bulan').val());

            $('#id_rpk').val(id);

            $.ajax({
                type: "post",
                url: "<?= base_url('realisasi/getrekening') ?>",
                data: {
                    id: id,
                    bulan: $('#bulan').val()
                },
                dataType: "json",
                success: function(response) {
                    $('#kodrek').val(response.kode + " - " + response.nama_belanja);
                    $('#rincian').val(response.keterangan);
                    $('#rpk').val(response.bulanan);
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
                        url: '<?php echo base_url('realisasi/add') ?>',
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

        function edit(id) {
            $.ajax({
                url: '<?php echo base_url('realisasi/getOne') ?>',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // reset the form 
                    $("#edit-form")[0].reset();
                    $(".form-control").removeClass('is-invalid').removeClass('is-valid');


                    $("#edit-form #id").val(response.id);
                    $("#edit-form #id_rpk").val(response.id_rpk);
                    $("#edit-form #tahun").val(response.tahun);
                    $("#edit-form #bln").val(response.bulan);
                    $("#edit-form #jumlah").val(response.jumlah);

                    $.ajax({
                        type: "post",
                        url: "<?= base_url('realisasi/getrekening') ?>",
                        data: {
                            id: response.id_rpk,
                            bulan: response.bulan
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#kodrek').val(response.kode + " - " + response.nama_belanja);
                            $('#rincian').val(response.keterangan);
                            $('#rpk').val(response.bulanan);
                        }
                    });

                    $('#edit-modal').modal('show');

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
                                url: '<?php echo base_url('realisasi/edit') ?>',
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