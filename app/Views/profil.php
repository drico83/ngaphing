<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<title>Eplanning-Profil</title>

<link href="<?= base_url() ?>/assets/leaflet/leaflet.css" rel="stylesheet">
<link href="<?= base_url() ?>/assets/lightpick/lightpick.css" rel="stylesheet" />

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
                            <li class="breadcrumb-item active">Profil</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div id="user_map" class="pro-map" style="height: 220px"></div>
                </div>
                <!--end card-body-->
                <div class="card-body">
                    <div class="dastone-profile">
                        <div class="row">
                            <div class="col-lg-6 align-self-center mb-3 mb-lg-0">
                                <div class="dastone-profile-main">
                                    <div class="dastone-profile-main-pic">
                                        <img src="<?= base_url('/assets/images/users/' . $avatar) ?>" alt=""
                                            height="110" class="rounded-circle">
                                        <span class="dastone-profile_main-pic-change">
                                            <form enctype="multipart/form-data" method="post" id="formulir">
                                                <label for="avatar" style="cursor: pointer;"><i
                                                        class="fas fa-camera"></i></label>
                                                <input type="file" name="avatar" id="avatar"
                                                    style="display: none; visibility:none"
                                                    accept="image/png, image/gif, image/jpeg">
                                            </form>
                                        </span>
                                    </div>
                                    <div class="dastone-profile_user-detail">
                                        <h5 class="dastone-user-name"><?= user()->name ?></h5>
                                        <p class="mb-0 dastone-user-name-post"><?= $grup . ' - ' . $puskesmas ?></p>
                                    </div>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-lg-6 ms-auto align-self-center">
                                <ul class="list-unstyled personal-detail mb-0">
                                    <li class=""><i class="ti ti-mobile me-2 text-secondary font-16 align-middle"></i>
                                        <b> phone </b> : <?= user()->phone ?>
                                    </li>
                                    <li class="mt-2"><i
                                            class="ti ti-email text-secondary font-16 align-middle me-2"></i> <b> Email
                                        </b> : <?= user()->email ?></li>

                                </ul>

                            </div>
                            <!--end col-->

                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end f_profile-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <div class="pb-4">
        <ul class="nav-border nav nav-pills mb-0" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="settings_detail_tab" data-bs-toggle="pill"
                    href="#Profile_Settings">Settings</a>
            </li>
        </ul>
    </div>
    <!--end card-body-->
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="Profile_Settings" role="tabpanel"
                    aria-labelledby="settings_detail_tab">
                    <div class="row">
                        <div class="col-lg-6 col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h4 class="card-title">Personal Information</h4>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-header-->
                                <div class="card-body">
                                    <form id="data-form" action="<?= base_url('profil/edit') ?>" method="post">
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Nama
                                                Lengkap</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" name="name" id="name" type="text"
                                                    value="<?= user()->name ?>">
                                                <input class="form-control" name="id" id="id" type="hidden"
                                                    value="<?= user_id() ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Username</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" name="username" id="username" type="text"
                                                    value="<?= user()->username ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Nama
                                                Puskesmas</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" type="text" value="<?= $puskesmas ?>"
                                                    disabled>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Nomor
                                                Telp.</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="las la-phone"></i></span>
                                                    <input type="text" name="phone" id="phone" class="form-control"
                                                        value="<?= user()->phone ?>" placeholder="Phone"
                                                        aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Alamat
                                                Email</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="las la-at"></i></span>
                                                    <input type="text" name="email" id="email" class="form-control"
                                                        value="<?= user()->email ?>" placeholder="Email"
                                                        aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Ubah
                                                    Profil</button>
                                                <button type="reset"
                                                    class="btn btn-sm btn-outline-danger">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-6 col-xl-6">
                            <div class="swal" data-swal="<?= session()->get('pesan') ?>"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Ubah Kata Sandi</h4>
                                </div>
                                <!--end card-header-->
                                <div class="card-body">
                                    <form action="<?= base_url('profil/editpass') ?>" method="post" id="editpass">
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Kata
                                                sandi
                                                baru</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" name="id" id="idx" type="hidden"
                                                    value="<?= user_id() ?>">
                                                <input class="form-control" type="password" placeholder="New Password"
                                                    name="password_hash" id="password_hash">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 text-end mb-lg-0 align-self-center">Ulangi
                                                kata
                                                sandi baru</label>
                                            <div class="col-lg-9 col-xl-8">
                                                <input class="form-control" type="password" placeholder="Re-Password"
                                                    name="repassword" id="repassword">
                                                <span class="form-text text-muted font-12">jangan membagikan kata sandi
                                                    anda</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-9 col-xl-8 offset-lg-3">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Ubah kata
                                                    sandi</button>
                                                <button type="reset"
                                                    class="btn btn-sm btn-outline-danger">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->

                        </div> <!-- end col -->
                    </div>
                    <!--end row-->
                </div>
                <!--end tab-pane-->
            </div>
            <!--end tab-content-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <?= $this->endSection() ?>
    <!-- /.content -->


    <!-- page script -->
    <?= $this->section("js") ?>
    <!-- SweetAlert2 -->
    <script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>
    <!-- jquery-validation -->
    <script src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('assets/leaflet/leaflet.js') ?>"></script>
    <script src="<?= base_url('assets/lightpick/lightpick.js') ?>"></script>

    <script>
    $(document).ready(function() {
        $('#avatar').change(function(e) {
            e.preventDefault();
            var form_data = new FormData($('#formulir')[0]);
            form_data.append('id', $('#idx').val());
            $.ajax({
                type: "post",
                url: "<?= base_url('profil/upload') ?>",
                data: form_data,
                contentType: false,
                processData: false,
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
                            window.location.reload();
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
        });

        const swal = $('.swal').data('swal');
        if (swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: swal,
                showConfirmButton: false,
                timer: 1500
            })
        }
    });
    var urlController = '';
    var submitText = '';

    function getUrl() {
        return urlController;
    }

    function getSubmitText() {
        return submitText;
    }

    function edit() {
        var form = $('#data-form');
        $(".text-danger").remove();
        $.ajax({
            // fixBug get url from global function only
            // get global variable is bug!
            url: '<?= base_url($controller . "/edit") ?>',
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
                        window.location.reload();
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

    var cities = L.layerGroup();

    L.marker([<?= $lat ?>, <?= $long ?>]).bindPopup('Kamu berada di <?= $kota ?>').addTo(cities);


    var mbAttr = 'Website - <a href="https://mannatthemes.com/" target="_blank">Mannatthemes</a> '
    mbUrl =
        'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

    var grayscale = L.tileLayer(mbUrl, {
            id: 'mapbox/light-v9',
            tileSize: 512,
            zoomOffset: -1,
            attribution: mbAttr
        }),
        streets = L.tileLayer(mbUrl, {
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            attribution: mbAttr
        });


    var map = L.map('user_map', {
        center: [<?= $lat ?>, <?= $long ?>],
        zoom: 10,
        layers: [grayscale, cities]
    });

    var baseLayers = {
        "Grayscale": grayscale,
        "Streets": streets
    };

    var overlays = {
        "Cities": cities
    };

    L.control.layers(baseLayers, overlays).addTo(map);




    // light_datepick
    new Lightpick({
        field: document.getElementById('light_datepick'),
        inline: true,
    });
    </script>


    <?= $this->endSection() ?>