<?php
header("Content-type: application/vnd-ms-excel");
header("Content-disposition: attachment; filename=rab-" . $puskesmas['pkm'] . "-" . date('d-m-Y') . ".xls")

?>


<!DOCTYPE html>
<html lang="en">

<body onload="window.print()">
    <div class="cetak">
        <h3 style="text-align: center;">RENCANA ANGGARAN BIAYA (RAB) </h3>
        <h3 style="text-align: center;"> BANTUAN OPERASIONAL KESEHATAN PUSKESMAS </h3>
        <h3 style="text-align: center;"> DANA ALOKASI KHUSUS NONFISIK BIDANG KESEHATAN TA <?= $tahun ?> </h3>
        <h3 style="text-align: center;"> DINAS KESEHATAN KABUPATEN GARUT</h3>
        <h3 style="text-align: center;">Nama Puskesmas : <?= $puskesmas['pkm']; ?></h3>


        <?= $result; ?>

        <table style="border: none !important;">
            <tr style="border: none;">
                <td style="width: 30%;border:none"></td>
                <td style="width: 40%;border:none"></td>
                <td style="width: 30%;border:none">Garut, 15 November 2021</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;">Kepala UPT Puskesmas <?= $puskesmas['pkm']; ?></td>
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
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"><?= $puskesmas['kapus']; ?></td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"><?= $puskesmas['nip_kapus']; ?></td>
            </tr>
        </table>
    </div>
</body>

</html>