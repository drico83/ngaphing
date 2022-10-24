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
                            <li class="breadcrumb-item active">Input Belanja BLUD</li>
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
                            <h5>Proyeksi Belanja Perubahan</h5>
                            <button type="button" class="btn btn-sm btn-soft-primary text-end" onclick="add()"
                                title="Tambah Penyedia">
                                <i class="fas fa-plus me-2"></i> Tambah Proyeksi</button>

                        </div>
                    </div>

                    <div class="card-body">
                        <table id="data_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Puskesmas</th>
                                    <th>Pagu Belanja</th>
                                    <th>Belanja Pegawai</th>
                                    <th>Belanja Barang dan Jasa</th>
                                    <th>Belanja Modal Tanah</th>
                                    <th>Belanja Modal Gedung dan Bangunan</th>
                                    <th>belanja Modal Modal Peralatan dan Mesin</th>
                                    <th>Belanja Modal lainnya</th>
                                    <th>Jumlah Belanja</th>

                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="2"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Add modal content -->
                <div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title m-0" id="info-header-modalLabel">Tambah Data Proyeksi Perubahan
                                </h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="add-form" class="pl-3 pr-3">
                                    <div class="row">
                                        <input type="hidden" id="id" name="id" class="form-control" placeholder="Id"
                                            maxlength="11" required>
                                    </div>


                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Nama Puskesmas:
                                        </label>
                                        <div class="col-sm-10">
                                            <select id="idPkm" name="idPkm" class="custom-select" required>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Pagu Belanja:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="pagubelanja" name="pagubelanja"
                                                class="form-control" placeholder="Pagu Belanja" maxlength="11"
                                                number="true" readonly>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja
                                            Pegawai:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="bPegawai" name="bPegawai" class="form-control"
                                                placeholder="Belanja Pegawai" maxlength="11" number="true" value=0
                                                required>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Barang dan
                                            Jasa:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="bBarjas" name="bBarjas" class="form-control"
                                                placeholder="Belanja Barang dan Jasa" maxlength="11" number="true"
                                                value=0 required>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal
                                            Tanah:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalTanah" name="modalTanah" class="form-control"
                                                placeholder="Belanja Modal Tanah" maxlength="11" number="true" value=0
                                                required>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal Gedung
                                            dan
                                            Bangunan:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalBangunan" name="modalBangunan"
                                                class="form-control" placeholder="Belanja Modal Gedung dan Bangunan"
                                                maxlength="11" value=0 number="true" required>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal Modal
                                            Peralatan dan
                                            Mesin:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalAlat" name="modalAlat" class="form-control"
                                                placeholder="belanja Modal Modal Peralatan dan Mesin" maxlength="11"
                                                value=0 number="true" required>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal lainnya:
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalLain" name="modalLain" class="form-control"
                                                placeholder="Belanja Modal lainnya" maxlength="11" number="true" value=0
                                                required>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Jumlah Total: </label>
                                        <div class="col-sm-10">
                                            <input type="number" id="jml" name="jml" class="form-control"
                                                placeholder="Jumlah Total Belanja" maxlength="11" number="true"
                                                readonly>
                                        </div>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success" id="add-form-btn" disabled>Simpan</button>

                            </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <!-- Add modal content -->
                <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">

                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title m-0" id="info-header-modalLabel">Ubah Data Proyeksi Perubahan
                                </h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="edit-form" class="pl-3 pr-3">
                                    <div class="row">
                                        <input type="hidden" id="id" name="id" class="form-control" placeholder="Id"
                                            maxlength="11" required>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Nama Puskesmas:
                                        </label>
                                        <div class="col-sm-10">
                                            <select id="idPkm" name="idPkm" class="custom-select" required>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Pagu Belanja:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="pagubelanja" name="pagubelanja"
                                                class="form-control" placeholder="Pagu Belanja" maxlength="11"
                                                number="true" readonly>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja
                                            Pegawai:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="bPegawai" name="bPegawai" class="form-control"
                                                placeholder="Belanja Pegawai" maxlength="11" number="true" required>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Barang dan
                                            Jasa:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="bBarjas" name="bBarjas" class="form-control"
                                                placeholder="Belanja Barang dan Jasa" maxlength="11" number="true"
                                                required>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal
                                            Tanah:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalTanah" name="modalTanah" class="form-control"
                                                placeholder="Belanja Modal Tanah" maxlength="11" number="true" required>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal Gedung
                                            dan
                                            Bangunan:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalBangunan" name="modalBangunan"
                                                class="form-control" placeholder="Belanja Modal Gedung dan Bangunan"
                                                maxlength="11" number="true" required>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal Modal
                                            Peralatan dan
                                            Mesin:</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalAlat" name="modalAlat" class="form-control"
                                                placeholder="belanja Modal Modal Peralatan dan Mesin" maxlength="11"
                                                number="true" required>
                                        </div>
                                    </div>

                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Belanja Modal lainnya:
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="number" id="modalLain" name="modalLain" class="form-control"
                                                placeholder="Belanja Modal lainnya" maxlength="11" number="true"
                                                required>
                                        </div>
                                    </div>
                                    <div class="position-relative form-group row">
                                        <label for="exampleEmail" class="col-sm-2 col-form-label">Jumlah Total: </label>
                                        <div class="col-sm-10">
                                            <input type="number" id="jml" name="jml" class="form-control"
                                                placeholder="Jumlah Total Belanja" maxlength="11" number="true"
                                                readonly>
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success" id="edit-form-btn"
                                    disabled>Simpan</button>

                            </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

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

        $(document).ready(function() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('upt/getupt') ?>",
                dataType: "json",
                success: function(response) {
                    $('#idPkm').html(response.data);
                    $('#idPkm').select2({
                        width: '100%',
                        dropdownParent: $('#add-form')
                    });
                }
            });

            $('#idPkm').change(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: "<?= base_url('belanja/jumlahpendapatan') ?>",
                    data: {
                        id_pkm: $(this).val()
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#pagubelanja').val(response.jumlah);
                    }
                });

            });

            $('#bPegawai,#bBarjas, #modalTanah, #modalBangunan, #modalAlat, #modalLain').keyup(function(e) {
                jumlah();
            });
            $('#edit-form #bPegawai,#edit-form  #bBarjas, #edit-form #modalTanah, #edit-form #modalBangunan, #edit-form #modalAlat, #edit-form #modalLain')
                .keyup(function(e) {
                    jumlah2();
                });
        });

        function jumlah() {
            bPegawai = $('#bPegawai').val();
            bBarjas = $('#bBarjas').val();
            modalTanah = $('#modalTanah').val();
            modalBangunan = $('#modalBangunan').val();
            modalAlat = $('#modalAlat').val();
            modalLain = $('#modalLain').val();
            total = parseInt(bPegawai) + parseInt(bBarjas) + parseInt(modalTanah) + parseInt(modalBangunan) + parseInt(
                modalAlat) + parseInt(modalLain);

            $('#add-form #jml').val(total);
            $('#add-form #jml').val(total);
            if ($('#pagubelanja').val() == total) {
                $('#add-form-btn').prop('disabled', false)
            } else {
                $('#add-form-btn').prop('disabled', true)
            }

        };

        function jumlah2() {
            bPegawai = $('#edit-form #bPegawai').val();
            bBarjas = $('#edit-form #bBarjas').val();
            modalTanah = $('#edit-form #modalTanah').val();
            modalBangunan = $('#edit-form #modalBangunan').val();
            modalAlat = $('#edit-form #modalAlat').val();
            modalLain = $('#edit-form #modalLain').val();
            total = parseInt(bPegawai) + parseInt(bBarjas) + parseInt(modalTanah) + parseInt(modalBangunan) + parseInt(
                modalAlat) + parseInt(modalLain);

            $('#edit-form #jml').val(total);
            if ($('#edit-form #pagubelanja').val() == total) {
                $('#edit-form-btn').prop('disabled', false)
            } else {
                $('#edit-form-btn').prop('disabled', true)
            }

        };

        $(function() {
            $('#data_table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": {
                    "url": '<?php echo base_url($controller . '/getAll') ?>',
                    "type": "POST",
                    "dataType": "json",
                    async: "true"
                },
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                "columnDefs": [{
                    "orderable": false,
                    "render": $.fn.dataTable.render.number('.', ',', 0),
                    "targets": [1, 2, 3, 4, 5, 6, 7, 8]
                }],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // computing column Total of the complete result 
                    var jml1 = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml2 = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml3 = api
                        .column(4)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml4 = api
                        .column(5)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml5 = api
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml6 = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml7 = api
                        .column(8)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jml8 = api
                        .column(9)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var numFormat = $.fn.dataTable.render.number('.', ',', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Jumlah');

                    $(api.column(2).footer()).html(numFormat(jml1));
                    $(api.column(3).footer()).html(numFormat(jml2));
                    $(api.column(4).footer()).html(numFormat(jml3));
                    $(api.column(5).footer()).html(numFormat(jml4));
                    $(api.column(6).footer()).html(numFormat(jml5));
                    $(api.column(7).footer()).html(numFormat(jml6));
                    $(api.column(8).footer()).html(numFormat(jml7));
                    $(api.column(9).footer()).html(numFormat(jml8));

                },
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
                                    $('#add-modal').modal('hide');

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
                    // $("#edit-form #idPkm").val(response.id_pkm);
                    $("#edit-form #bPegawai").val(response.b_pegawai);
                    $("#edit-form #bBarjas").val(response.b_barjas);
                    $("#edit-form #modalTanah").val(response.modal_tanah);
                    $("#edit-form #modalBangunan").val(response.modal_bangunan);
                    $("#edit-form #modalAlat").val(response.modal_alat);
                    $("#edit-form #modalLain").val(response.modal_lain);

                    $.ajax({
                        type: "post",
                        url: "<?php echo base_url('upt/getupt') ?>",
                        data: {
                            upt: response.id_pkm
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#edit-form #idPkm').html(response.data);
                        }
                    });

                    $.ajax({
                        type: "post",
                        url: "<?= base_url('belanja/jumlahpendapatan') ?>",
                        data: {
                            id_pkm: response.id_pkm
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#edit-form #pagubelanja').val(response.jumlah);
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
                                            $('#edit-modal').modal('hide');

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
                title: 'Hapus Data',
                text: "Yakin akan dihapus?",
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