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
                            <li class="breadcrumb-item active">Pagu 2023</li>
                        </ol>
                    </div>
                    <!--end col-->
                    <div class="col-auto align-self-center">

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
                        <i class="fas fa-plus me-2"></i> Tambah Pagu 2023</button>

                </div>

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="data_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Puskesmas</th>
                                <th>Pagu UKM</th>
                                <th>Pagu Insentif</th>
                                <th>Pagu Manajemen</th>
                                <th>Pagu Total</th>
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
                            </tr>
                        </tfoot>
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
                            <h6 class="modal-title m-0" id="info-header-modalLabel">Pagu 2023</h6>
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
                                            <label for="id_pkm"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Nama Puskesmas:
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
                                            <label for="pagu_ukm"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Pagu UKM: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="pagu_ukm" name="pagu_ukm" class="form-control"
                                                    placeholder="Pagu UKM" minlength="0" maxlength="11" value="0"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="pagu_insentif"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Pagu Insentif:
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="pagu_insentif" name="pagu_insentif"
                                                    class="form-control" placeholder="Pagu Insentif" value="0"
                                                    minlength="0" maxlength="11" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="pagu_manajemen"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Pagu Manajemen:
                                                <span class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="pagu_manajemen" name="pagu_manajemen"
                                                    class="form-control" placeholder="Pagu Manajemen" value="0"
                                                    minlength="0" maxlength="11" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3 row">
                                            <label for="pagu_total"
                                                class="col-sm-4 form-label align-self-center mb-lg-0">Pagu Total: <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-sm-8">
                                                <input type="number" id="pagu_total" name="pagu_total"
                                                    class="form-control" placeholder="Pagu Total" minlength="0"
                                                    maxlength="11" readonly>
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

            $('#pagu_ukm, #pagu_insentif, #pagu_manajemen').keyup(function(e) {
                hitung();
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
                    "targets": [2, 3, 4, 5],
                    "className": "text-end"
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
                    var jumlah1 = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jumlah2 = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jumlah3 = api
                        .column(4)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jumlah4 = api
                        .column(5)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var numFormat = $.fn.dataTable.render.number('.', ',', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Jumlah');
                    $(api.column(2).footer()).html(numFormat(jumlah1));
                    $(api.column(3).footer()).html(numFormat(jumlah2));
                    $(api.column(4).footer()).html(numFormat(jumlah3));
                    $(api.column(5).footer()).html(numFormat(jumlah4));
                },
            });
        });

        function hitung() {
            var ukm = $('#pagu_ukm').val();
            var insentif = $('#pagu_insentif').val();
            var manajemen = $('#pagu_manajemen').val();

            var total = 0;
            total = parseInt(ukm) + parseInt(insentif) + parseInt(manajemen);
            $('#pagu_total').val(total);
        };
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
                $("#info-header-modalLabel").text('<?= lang("App.add") ?> Pagu 2023');
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
                        $("#info-header-modalLabel").text('<?= lang("App.edit") ?> Pagu 2023');
                        $("#form-btn").text(submitText);
                        $('#data-modal').modal('show');
                        //insert data to form
                        $("#data-form #id").val(response.id);
                        $.ajax({
                            type: "post",
                            url: "<?= base_url('upt/getupt') ?>",
                            data: {
                                upt: response.id_pkm,
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
                        $("#data-form #pagu_ukm").val(response.pagu_ukm);
                        $("#data-form #pagu_insentif").val(response.pagu_insentif);
                        $("#data-form #pagu_manajemen").val(response.pagu_manajemen);
                        $("#data-form #pagu_total").val(response.pagu_total);


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