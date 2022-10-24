<?php
header("Content-type: application/vnd-ms-excel");
header("Content-disposition: attachment; filename=cetaksub-" . date('d-m-Y') . ".xls")

?>


<!DOCTYPE html>
<html lang="en">

<body onload="window.print()">
    <div class="cetak">
        <h3 style="text-align: center;">DAFTAR SUB KEGIATAN </h3>
        <h3 style="text-align: center;"> BANTUAN OPERASIONAL KESEHATAN PUSKESMAS </h3>
        <h3 style="text-align: center;"> TAHUN ANGGARAN </h3>



        <?= $result; ?>

    </div>
</body>

</html>