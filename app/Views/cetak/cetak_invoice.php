<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>invoice-<?= $hasil->invoice ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/invoice') ?>/style.css" media="all" />
</head>

<body onload="print()">
    <header class="clearfix">
        <div id="logo">
            <img src="<?= base_url('assets/invoice') ?>/logo.png" style="width: 30%;">
        </div>
        <h1>INVOICE NOMOR : <?= strtoupper($hasil->invoice) ?></h1>
        <div id="company" class="clearfix">
            <div>CV. PANATA NUMASAGI</div>
            <div>Jl. Pacet (Andir) No. 83 RT. 03/08 Desa Pakutandang Kec. Ciparay Kab. Bandung</div>
            <div><a href="mailto:panata.numasagi@gmail.com">panata.numasagi@gmail.com</a></div>
        </div>
        <div id="project">
            <div><span>Untuk</span><?= $hasil->pkm ?></div>
            <div><span>Alamat</span> Garut</div>
            <div><span>email</span> <a href="mailto:john@example.com"><?= user()->email ?></a></div>
            <div><span>Tanggal Bayar</span><?= $hasil->tgl_bayar ?></div>
        </div>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th class="service">LAYANAN</th>
                    <th class="desc">DESKRIPSI</th>
                    <th>HARGA</th>
                    <th>BANYAKNYA</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="service">Aplikasi</td>
                    <td class="desc">Maintenance Aplikasi ePlanning Tahun 2022</td>
                    <td class="unit">2.000.000</td>
                    <td class="qty">1</td>
                    <td class="total">2.000.000</td>
                </tr>
                <tr>
                    <td colspan="4">SUBTOTAL</td>
                    <td class="total">2.000.000</td>
                </tr>
                <tr>
                    <td colspan="4">Pajak</td>
                    <td class="total">0.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="grand total">GRAND TOTAL</td>
                    <td class="grand total">Rp. 2.000.000</td>
                </tr>
            </tbody>
        </table>
    </main>
    <img src="<?= base_url('assets/invoice/qr') . '/' . $hasil->invoice ?>.png" alt="qrcode" style="width: 10%;">
    <footer>
        Invoice dibuat secara sistem dan dinyatakan valid tanpa tandatangan dan cap.
    </footer>
</body>

</html>