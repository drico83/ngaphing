<?= $this->extend("layout/main") ?>

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
                        <h4 class="page-title">Pengaturan</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Umum</a></li>
                            <li class="breadcrumb-item active">Pengajuan Pencairan</li>
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
                <div class="card-header text-end">
                    <button type="button" class="btn btn-sm btn-soft-primary" onclick="save()" title="Tambah Penyedia">
                        <i class="fas fa-plus me-2"></i> Tambah Pengajuan Pencairan</button>

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Keterangan</th>
                                <th>Nomor Surat Pengajuan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Nomor SPP</th>
                                <th>Tanggal SPP</th>
                                <th>Nomor SPM</th>
                                <th>Tanggal SPM</th>
                                <th>Nama Verifikator</th>
                                <th>NIP Verifikator</th>
                                <th>Bulan awal</th>
                                <th>Bulan akhir</th>

                                <th>Nilai</th>


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
                            <h6 class="modal-title m-0" id="info-header-modalLabel">Pengajuan Pencairan</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            <label for="bulan_akhir"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nama Puskesmas :
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <select id="id_pkm" name="id_pkm" class="form-select" required>
                                                    <option value="select1">select1</option>
                                                    <option value="select2">select2</option>
                                                    <option value="select3">select3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="keterangan"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Keterangan: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <textarea cols="40" rows="5" id="keterangan" name="keterangan"
                                                    class="form-control" placeholder="Keterangan" minlength="0"
                                                    required></textarea>
                                            </div>
                                        </div>


                                    </div>



                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="no_pengajuan"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nomor Surat
                                                Pengajuan: <span class="text-danger">*</span> </label>
                                            <div class="col-sm-5">
                                                <input type="text" id="no_pengajuan" name="no_pengajuan"
                                                    class="form-control" placeholder="Nomor Surat Pengajuan"
                                                    minlength="0" maxlength="100" required>
                                            </div>

                                            <label for="tgl_pengajuan"
                                                class="col-sm-1 form-label align-self-center mb-lg-0">Tanggal:
                                            </label>
                                            <div class="col-sm-2">
                                                <input type="date" id="tgl_pengajuan" name="tgl_pengajuan"
                                                    class="form-control" dateISO="true" required>
                                            </div>

                                        </div>


                                    </div>

                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="no_spp"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nomor SPP:</label>
                                            <div class="col-sm-5">
                                                <input type="text" id="no_spp" name="no_spp" class="form-control"
                                                    placeholder="Nomor SPP" minlength="0" maxlength="100" required>
                                            </div>

                                            <label for="tgl_spp"
                                                class="col-sm-1 form-label align-self-center mb-lg-0">Tanggal:</label>
                                            <div class="col-sm-2">
                                                <input type="date" id="tgl_spp" name="tgl_spp" class="form-control"
                                                    dateISO="true" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="no_spm"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nomor SPM: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-5">
                                                <input type="text" id="no_spm" name="no_spm" class="form-control"
                                                    placeholder="Nomor SPM" minlength="0" maxlength="100" required>
                                            </div>

                                            <label for="tgl_spm"
                                                class="col-sm-1 form-label align-self-center mb-lg-0">Tanggal: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-2">
                                                <input type="date" id="tgl_spm" name="tgl_spm" class="form-control"
                                                    dateISO="true" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">

                                        <div class="mb-3 row">
                                            <label for="verifikator"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nama Verifikator:
                                            </label>
                                            <div class="col-sm-3">
                                                <input type="text" id="verifikator" name="verifikator"
                                                    class="form-control" placeholder="Nama Verifikator" minlength="0"
                                                    maxlength="100" required>
                                            </div>

                                            <label for="nip_verifikator"
                                                class="col-sm-1 form-label align-self-center mb-lg-0">NIP
                                            </label>
                                            <div class="col-sm-4">
                                                <input type="text" id="nip_verifikator" name="nip_verifikator"
                                                    class="form-control" placeholder="NIP Verifikator" minlength="0"
                                                    maxlength="50" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="bulan_awal"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Bulan awal: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-3">
                                                <select id="bulan_awal" name="bulan_awal" class="form-select" required>
                                                    <option value="select1">select1</option>
                                                    <option value="select2">select2</option>
                                                    <option value="select3">select3</option>
                                                </select>
                                            </div>
                                            <label for="bulan_akhir"
                                                class="col-sm-2 form-label align-self-center mb-lg-0">s.d. <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-3">
                                                <select id="bulan_akhir" name="bulan_akhir" class="form-select"
                                                    required>
                                                    <option value="select1">select1</option>
                                                    <option value="select2">select2</option>
                                                    <option value="select3">select3</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="nilai"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nilai: </label>
                                            <div class="col-sm-8">
                                                <input type="text" id="nilai" name="nilai" class="form-control"
                                                    placeholder="Nilai" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tampiltabel">

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
            $(".preloader").fadeOut();
            $.ajax({
                type: "get",
                url: "<?= base_url('bulan/getSelect') ?>",
                dataType: "json",
                success: function(response) {
                    $('#data-form #bulan_awal').html(response.data);
                    $('#data-form #bulan_awal').select2({
                        dropdownParent: $('#data-form'),
                        width: "100%",
                    });
                    $('#data-form #bulan_akhir').html(response.data);
                    $('#data-form #bulan_akhir').select2({
                        dropdownParent: $('#data-form'),
                        width: "100%",
                    });
                }
            });

            $.ajax({
                type: "get",
                url: "<?= base_url('upt/getSelect') ?>",
                dataType: "json",
                success: function(response) {
                    $('#data-form #id_pkm').html(response.data);
                    $('#data-form #id_pkm').select2({
                        dropdownParent: $('#data-form'),
                        width: "100%",
                    });
                }
            });

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
                "columnDefs": [{
                    "orderable": false,
                    "render": $.fn.dataTable.render.number('.', ',', 0),
                    "targets": [12],
                    "class-name": "text-end"
                }],
            });

            $('#bulan_awal').on('select2:select', function(e) {
                $('#bulan_akhir').val($('#bulan_awal').val());
                $('#bulan_akhir').change();
                preview();
            });
            $('#bulan_akhir').on('select2:select', function(e) {
                preview();
            });
            $('#id_pkm').on('select2:select', function(e) {
                preview();
            });

        });

        function preview() {
            $.ajax({
                type: "post",
                data: {
                    pkm: $('#id_pkm').val(),
                    awal: $('#bulan_awal').val(),
                    akhir: $('#bulan_akhir').val(),
                },
                url: "<?php echo base_url($controller . '/tampil') ?>",
                dataType: "json",
                beforeSend: function() {
                    $(".preloader").fadeIn();
                },
                success: function(response) {
                    if (response.data) {
                        $(".preloader").fadeOut();
                        $('.tampiltabel').html(response.data);
                        $("#data-form #nilai").val(response.jumlah.toLocaleString());



                    }
                }
            });
        }

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
                $("#info-header-modalLabel").text('<?= lang("App.add") ?> Pengajuan Pencairan');
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
                        $("#info-header-modalLabel").text('<?= lang("App.edit") ?> Pengajuan Pencairan');
                        $("#form-btn").text(submitText);
                        $('#data-modal').modal('show');
                        //insert data to form
                        $("#data-form #id").val(response.id);
                        $("#data-form #keterangan").val(response.keterangan);
                        $("#data-form #no_pengajuan").val(response.no_pengajuan);
                        $("#data-form #tgl_pengajuan").val(response.tgl_pengajuan);
                        $("#data-form #no_spp").val(response.no_spp);
                        $("#data-form #tgl_spp").val(response.tgl_spp);
                        $("#data-form #no_spm").val(response.no_spm);
                        $("#data-form #tgl_spm").val(response.tgl_spm);
                        $("#data-form #verifikator").val(response.verifikator);
                        $("#data-form #nip_verifikator").val(response.nip_verifikator);
                        $("#data-form #bulan_awal").val(response.bulan_awal);
                        $("#data-form #bulan_akhir").val(response.bulan_akhir);
                        $("#data-form #tahun").val(response.tahun);
                        $("#data-form #nilai").val(response.nilai);


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