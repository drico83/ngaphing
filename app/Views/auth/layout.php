<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>NewPlanning - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Newplanning" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="<?= base_url() ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= base_url() ?>/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <?= $this->renderSection('pageStyles') ?>
</head>

<body class="account-body accountbg">

    <!-- Log In page -->
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <?= $this->renderSection('main') ?>
                <!--end row-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
    <!-- End Log In page -->




    <!-- jQuery  -->
    <script src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/waves.js"></script>
    <script src="<?= base_url() ?>/assets/js/feather.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/simplebar.min.js"></script>
    <?= $this->renderSection('pageScripts') ?>

</body>

</html>