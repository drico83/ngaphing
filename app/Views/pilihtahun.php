<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>ePlanning - pilihtahun</title>
    <style>
    body,
    html {
        height: 100%;
        margin: 0;
    }

    .bg {
        /* The image used */
        background-image: url("../assets/images/health3.jpg");

        /* Full height */
        width: 100%;
        height: 100vh;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    </style>
</head>

<body class="bg">
    <div class="col-md-12 col-sm-12 col-xs-12 mt-10 text-center">
        <img src="../assets/images/logogarut.png" alt="logo" class="img-shadow mt-5 mb-2" width="80">
    </div>
    <div class="row justify-content-center mb-3">
        <div class="border-radius bg-warning col-md-4 rounded-lg" style="border:1px solid white">
            <h4 class="text-center text-white text-shadow"><span class="">Aplikasi ePLANNING<br>Dinas Kesehatan
                    Kabupaten Garut </span></h4>
        </div>
    </div>
    <div class="row d-flex justify-content-center" style="margin-top:40px;">
        <?= $ops; ?>

    </div>


    <!-- jQuery -->
    <script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>
    <script>
    function tahun(id) {
        $.ajax({
            type: "post",
            url: "<?php echo base_url('pilihtahun/tahun') ?>",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                window.location.href = "<?= base_url('dashboard'); ?>";
            }
        });
    }
    </script>
</body>

</html>