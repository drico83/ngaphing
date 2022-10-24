<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="ePlanning by PanataWebSolution" name="description" />
    <meta content="Design for Dinkes Kab. Garut" name="author" />
    <title>ePlanning-Login</title>
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.ico" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="<?= base_url('assets/css/') ?>/login.css" rel="stylesheet" type="text/css" />
    <?= $this->renderSection('pageStyles') ?>
</head>

<body>
    <div class="bg-wrapper">
        <p id="city-animate" class="city-animate" style="background: url(http://localhost:8080/assets/images/bg.png)">
        </p>
    </div>
    <div class="svg-section">

    </div>

    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <!-- <img src="<?= base_url('assets/images/logo-dark.png') ?>" id="icon" alt="User Icon" class="mt-5" /> -->
            </div>
            <br>
            <!-- Login Form -->
            <?= $this->renderSection('main') ?>
            <div class="fadeIn five">
                <small class="mb-5">Version 2.00-2022</small>
            </div>


        </div>
    </div>

    <div>
        <!-- Remind Passowrd -->




    </div>
    <script src="https://sasikapevo.bandungkab.go.id/assets/global/plugins/jquery-3.2.1.min.js" type="text/javascript">
    </script>
    <script src="https://sasikapevo.bandungkab.go.id/assets/global/plugins/bootstrap-4.0.0/js/bootstrap.min.js"
        type="text/javascript"></script>
    <script src="https://sasikapevo.bandungkab.go.id/js/jquery.jclock.js" type="text/javascript"></script>
    <?= $this->renderSection('pageScripts') ?>
</body>

</html>