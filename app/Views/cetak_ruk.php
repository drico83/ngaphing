<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak RUK</title>

    <script src="<?= base_url('assets/AutoNumeric.js') ?>"></script>
    <style type="text/css">
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        vertical-align: baseline;
    }

    th {
        border: 1px solid #dddddd;
        text-align: center;
        padding: 8px;
        vertical-align: baseline;
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
        <h2 style="text-align: center;">RENCANA USULAN KEGIATAN PUSKESMAS TAHUN ANGGARAN <?= $tahun ?></h2>
        <h3 style="text-align: center;">NAMA PUSKESMAS : <?= strtoupper($puskesmas) ?></h3>
        <table width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kegiatan</th>
                    <th>Rincian Kegiatan</th>
                    <th>Tujuan</th>
                    <th>Sasaran</th>
                    <th>Target</th>
                    <th>Penanggungjawab</th>
                    <th>Sumber daya</th>
                    <th>Mitra kerja</th>
                    <th>Waktu</th>
                    <th>Biaya</th>
                    <th>Indikator Kinerja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $jumlah = 0;
                foreach ($result as $row) {
                    // $jumlah .= intval($row['harga_total']);
                    $biaya_bongkar[] = $row['harga_total'];
                    $total_biaya = array_sum($biaya_bongkar);
                ?>
                <tr>
                    <td class="align-baseline"><?= $no++ ?></td>

                    <td class="align-baseline"><?= $row['nama_subkomponen'] ?></td>
                    <td><?= $row['keterangan'] ?></td>
                    <td><?= $row['tujuan'] ?></td>
                    <td><?= $row['sasaran'] ?></td>
                    <td><?= $row['target'] ?></td>
                    <td><?= $row['tgjawab'] ?></td>
                    <td><?= $row['sumberdaya'] ?></td>
                    <td><?= $row['mitra'] ?></td>
                    <td><?= $row['waktu'] ?></td>
                    <td><?= ($row['harga_total'] != null) ? number_format($row['harga_total'], 0, ",", ".") : '0' ?>
                    </td>
                    <td><?= $row['indikator'] ?></td>
                </tr>

                <?php
                }
                ?>
                <tr>
                    <td style="text-align: center;" colspan="10"><b>JUMLAH</b></td>
                    <td><?= number_format($total_biaya); ?></td>
                    <td></td>
                </tr>
            </tbody>



        </table>
    </div>
</body>
<script>
$(document).ready(function() {
    $('.nomor').autoNumeric('init', {
        aSep: '.',
        aDec: ',',
        mDec: '0'
    });
});
</script>

</html>