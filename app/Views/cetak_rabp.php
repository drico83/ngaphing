<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak RAB</title>

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
            width: 330mm;
            height: 210mm;
        }

        /*.footer { position: fixed; bottom: 0; font-size:11px; display:block; }
        .pagenum:after { counter-increment: page; content: counter(page); }*/
    }
    </style>
</head>

<body onload="window.print()">
    <div class="cetak">
        <h2 style="text-align: center;">RENCANA ANGGARAN BIAYA PUSKESMAS TAHUN ANGGARAN 2022</h2>
        <h3 style="text-align: center;">Nama Puskesmas : <?= $puskesmas; ?></h3>
        <h3 style="text-align: left;">Nama Program : <?= $program; ?></h3>
        <?= $result; ?>

        <table style="border: none !important;">
            <tr style="border: none;">
                <td style="width: 30%;border:none"></td>
                <td style="width: 40%;border:none"></td>
                <td style="width: 30%;border:none">Garut, 15 November 2021</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">Kepala Puskesmas <?= $puskesmas; ?></td>
                <td style="border: none;"></td>
                <td style="border: none;">Pengelola Program </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"><?= $program; ?> </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;">NIP.</td>
                <td style="border: none;"></td>
                <td style="border: none;">NIP.</td>
            </tr>
        </table>
    </div>
</body>

</html>