<?php
header("Content-type: application/vnd-ms-excel");
header("Content-disposition: attachment; filename=LK-" . $puskesmas['pkm'] . "-" . date('d-m-Y') . ".xls")

?>


<!DOCTYPE html>
<html lang="en">

<body onload="window.print()">
    <div class="cetak">
        <h3 style="text-align: center;">LEMBAR KERJA REVISI RK</h3>
        <h3 style="text-align: center;"> DAK NON FISIK BIDANG KESEHATAN TA 2022 </h3>
        <h3 style="text-align: center;"> BOK PUSKESMAS </h3>
        <h3 style="text-align: center;"> DINAS KESEHATAN KABUPATEN GARUT</h3>
        <h3 style="text-align: center;">Nama Puskesmas : <?= $puskesmas['pkm']; ?></h3>


        <?= $result; ?>

        <table style="border: none !important;">
            <tr style="border: none;">
                <td style="width: 30%;border:none"></td>
                <td style="width: 40%;border:none"></td>
                <td style="width: 30%;border:none">Garut, 23 Agustus 2022</td>
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