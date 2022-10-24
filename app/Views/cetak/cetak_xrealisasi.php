<?php
header("Content-type: application/vnd-ms-excel");
header("Content-disposition: attachment; filename=Realisasi-" . $puskesmas['pkm'] . "-" . date('d-m-Y') . ".xls")

?>


<!DOCTYPE html>
<html lang="en">

<body onload="window.print()">
    <div class="cetak">
        <h2 style="text-align: center;">REALISASI ANGGARAN BOK TAHUN ANGGARAN <?= $tahun ?></h2>
        <h3 style="text-align: center;">Bulan: <?= $bulan1; ?> s.d. <?= $bulan2; ?></h3>
        <h3 style="text-align: center;">Nama Puskesmas : <?= $puskesmas; ?></h3>

        <?= $result; ?>


    </div>
</body>

</html>