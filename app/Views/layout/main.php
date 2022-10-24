<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.ico">

    <!-- App css -->
    <link href="<?= base_url() ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" />


    <link href="<?= base_url() ?>/assets/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="<?= base_url() ?>/assets/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2-dark.min.css') ?>">
    <link href="<?= base_url() ?>/assets/select2/select2.min.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: rgba(240, 248, 255, 0.5);
    }

    .preloader .loading {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font: 14px arial;
    }
    </style>
    <?= $this->renderSection('css') ?>
</head>

<body>
    <!-- Left Sidenav -->
    <?= $this->include('layout/leftbar') ?>
    <!-- end left-sidenav-->


    <div class="page-wrapper">
        <!-- Top Bar Start -->
        <?= $this->include('layout/header') ?>
        <!-- Top Bar End -->

        <!-- Page Content-->
        <div class="page-content">
            <?= $this->renderSection('judul') ?>
            <?= $this->renderSection('isi') ?>

            <footer class="footer text-center text-sm-start">
                Database Aktif : <a href="<?= base_url('pilihtahun') ?>"><span
                        class="badge rounded-pill badge-outline-primary"><?= $tahun ?></span></a>
                <span class="text-muted d-none d-sm-inline-block float-end">Crafted with <i
                        class="mdi mdi-heart text-danger"></i> by @dennyrico</span>
            </footer>
            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- jQuery  -->
    <script src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/metismenu.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/waves.js"></script>
    <script src="<?= base_url() ?>/assets/js/feather.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/simplebar.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/moment.js"></script>
    <script src="<?= base_url() ?>/assets/daterangepicker/daterangepicker.js"></script>

    <script src="<?= base_url('assets/js/sweetalert2.all.min.js') ?>"></script>
    <!-- jquery-validation -->
    <script src="<?= base_url('assets/js/jquery.validate.min.js') ?>"></script>
    <!-- Required datatable js -->
    <script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/dataTables.bootstrap5.min.js"></script>
    <!-- Buttons examples -->
    <script src="<?= base_url() ?>/assets/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/buttons.bootstrap5.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/jszip.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/pdfmake.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/vfs_fonts.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/buttons.html5.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/buttons.print.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/buttons.colVis.min.js"></script>
    <script src="<?= base_url() ?>/assets/select2/select2.min.js"></script>
    <!-- Responsive examples -->
    <script src="<?= base_url() ?>/assets/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url() ?>/assets/datatables/responsive.bootstrap4.min.js"></script>
    <!-- App js -->
    <script src="<?= base_url() ?>/assets/js/app.js"></script>
    <script src="<?= base_url() ?>/assets/tippy/tippy.all.min.js"></script>
    <?= $this->renderSection('js') ?>

</body>

</html>