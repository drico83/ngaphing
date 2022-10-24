<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak BA</title>

    <script src="<?= base_url('assets/AutoNumeric.js') ?>"></script>
    <style type="text/css">
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    .cetak {
        font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        padding: 0;
        margin: 0;
        font-size: 13px;
    }

    @media print {
        @page {
            size: auto;
            margin: 11mm 15mm 15mm 15mm;
        }

        body {
            width: 210mm;
            height: 330mm;
        }

        /*.footer { position: fixed; bottom: 0; font-size:11px; display:block; }
        .pagenum:after { counter-increment: page; content: counter(page); }*/
    }
    </style>
</head>

<body onload="window.print()">
    <div class="cetak">
        <h3 style="text-align: center;">BERITA ACARA VERIFIKASI USULAN KEGIATAN</h3>
        <h3 style="text-align: center;">PELAKSANAAN BANTUAN OPERASIONAL KESEHATAN PUSKESMAS</h3>
        <h3 style="text-align: center;">DANA ALOKASI KHUSUS (DAK) NONFISIK BIDANG KESEHATAN</h3>
        <h3 style="text-align: center;">TAHUN ANGGARAN 2022</h3>
        </br>
        <h4 style="text-align: left;">Nama Puskesmas : <?= $puskesmas; ?></h4>
        <h4 style="text-align: left;">Nama Program : <?= $program; ?></h4>
        </br>

        <?= $result; ?>
        <table style="border: none !important;">
            <tr style="border: none;">
                <td style="width: 30%;border:none"></td>
                <td style="width: 40%;border:none"></td>
                <td style="width: 30%;border:none">Garut, <?= date('d F Y') ?></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Kepala Sub Bagian Perencanaan, Evaluasi dan Pelaporan</td>
                <td style="border: none;"></td>
                <td style="border: none;">Pejabat Pelaksana Teknis Kegiatan </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Dinas Kesehatan Kab. Garut</td>
                <td style="border: none;"></td>
                <td style="border: none;">Puskesmas <?= $puskesmas; ?></td>
            </tr>

            <tr style="border: none;">
                <div>
                    <td style="border: none; text-align: center; vertical-align: middle;"><img
                            src="<?= base_url() ?>/assets/entis.jpg" width="40%" style="position: relative;">
                    </td>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                </div>
            </tr>

            <tr style="border: none;">
                <td style="border: none;">Entis Sutisna, SKM</td>
                <td style="border: none;"></td>
                <td style="border: none;"><?= $pptk; ?></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">NIP. 19710918 199101 1 001</td>
                <td style="border: none;"></td>
                <td style="border: none;">NIP. <?= $nip; ?></td>
            </tr>
        </table>
    </div>
</body>

</html>