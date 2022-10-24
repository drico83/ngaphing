<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Dashboard::index');
$routes->get('/', static function () {
    return redirect()->to('dashboard');
});
$routes->get('dashboard', 'Dashboard::index');
$routes->group('upt', static function ($routes) {
    $routes->add('/', 'Upt::index');
    $routes->add('basic', 'Upt::basic');
    $routes->add('getupt', 'Upt::getselect');
    $routes->add('getOne', 'Upt::getOne');
    $routes->add('add', 'Upt::add');
    $routes->add('edit', 'Upt::edit');
    $routes->add('remove', 'Upt::remove');
    $routes->add('getSelect', 'Upt::getSelect');
});
$routes->group('program', static function ($routes) {
    $routes->add('/', 'Program::index');
    $routes->add('dt', 'Program::dt');
    $routes->add('getprogram', 'Program::getprogram');
    $routes->add('getprogram2', 'Program::getprogram2');
    $routes->add('getOne', 'Program::getOne');
    $routes->add('add', 'Program::add');
    $routes->add('edit', 'Program::edit');
    $routes->add('remove', 'Program::remove');
});
$routes->group('pengajuan', static function ($routes) {
    $routes->add('/', 'Pengajuan::index');
    $routes->add('dt', 'Pengajuan::dt');
    $routes->add('basic', 'Pengajuan::basic');
    $routes->add('getprogram2', 'Pengajuan::getprogram2');
    $routes->add('getOne', 'Pengajuan::getOne');
    $routes->add('add', 'Pengajuan::add');
    $routes->add('edit', 'Pengajuan::edit');
    $routes->add('remove', 'Pengajuan::remove');
    $routes->add('tampil', 'Pengajuan::tampil');
    $routes->add('cetak1', 'Pengajuan::cetak1');
    $routes->add('cetak2', 'Pengajuan::cetak2');
    $routes->add('cetak3', 'Pengajuan::cetak3');
});
$routes->group('rincian', static function ($routes) {
    $routes->add('/', 'Rincian::index');
    $routes->add('dt', 'Rincian::dt');
    $routes->add('getrincian', 'Rincian::getrincian');
    $routes->add('getOne', 'Rincian::getOne');
    $routes->add('add', 'Rincian::add');
    $routes->add('edit', 'Rincian::edit');
    $routes->add('remove', 'Rincian::remove');
});
$routes->group('komponen', static function ($routes) {
    $routes->add('/', 'Komponen::index');
    $routes->add('dt', 'Komponen::dt');
    $routes->add('getkomponen', 'Komponen::getkomponen');
    $routes->add('getOne', 'Komponen::getOne');
    $routes->add('add', 'Komponen::add');
    $routes->add('edit', 'Komponen::edit');
    $routes->add('remove', 'Komponen::remove');
});
$routes->group('subkomponen', static function ($routes) {
    $routes->add('/', 'Subkomponen::index');
    $routes->add('dt', 'Subkomponen::dt');
    $routes->add('getsubkomponen', 'Subkomponen::getsubkomponen');
    $routes->add('getsubkomponen2', 'Subkomponen::getsubkomponen2');
    $routes->add('getOne', 'Subkomponen::getOne');
    $routes->add('add', 'Subkomponen::add');
    $routes->add('edit', 'Subkomponen::edit');
    $routes->add('remove', 'Subkomponen::remove');
});
$routes->group('subkomponen2', static function ($routes) {
    $routes->add('/', 'Subkomponen2::index');
    $routes->add('dt', 'Subkomponen2::dt');
});
$routes->group('kodrek', static function ($routes) {
    $routes->add('/', 'Kodrek::index');
    $routes->add('dt', 'Kodrek::dt');
    $routes->add('getkodrek', 'Kodrek::getkodrek');
    $routes->add('getOne', 'Kodrek::getOne');
    $routes->add('add', 'Kodrek::add');
    $routes->add('edit', 'Kodrek::edit');
    $routes->add('remove', 'Kodrek::remove');
});
$routes->group('kodebelanja', static function ($routes) {
    $routes->add('/', 'Kodebelanja::index');
    $routes->add('dt', 'Kodebelanja::dt');
    $routes->add('getkodebelanja', 'Kodebelanja::getkodebelanja');
    $routes->add('getOne', 'Kodebelanja::getOne');
    $routes->add('add', 'Kodebelanja::add');
    $routes->add('edit', 'Kodebelanja::edit');
    $routes->add('remove', 'Kodebelanja::remove');
});
$routes->group('penyusun', static function ($routes) {
    $routes->add('/', 'Penyusun::index');
    $routes->add('dt', 'Penyusun::dt');
    $routes->add('getobjek', 'Penyusun::getobjek');
    $routes->add('getOne', 'Penyusun::getOne');
    $routes->add('add', 'Penyusun::add');
    $routes->add('edit', 'Penyusun::edit');
    $routes->add('remove', 'Penyusun::remove');
});
$routes->group('satuan', static function ($routes) {
    $routes->add('/', 'Satuan::index');
    $routes->add('dt', 'Satuan::dt');
    $routes->add('getobjek', 'Satuan::getobjek');
    $routes->add('getOne', 'Satuan::getOne');
    $routes->add('add', 'Satuan::add');
    $routes->add('edit', 'Satuan::edit');
    $routes->add('remove', 'Satuan::remove');
    $routes->add('getsatuan', 'Satuan::getsatuan');
});
$routes->group('pengaturanuser', static function ($routes) {
    $routes->add('/', 'Pengaturanuser::index');
    $routes->add('dt', 'Pengaturanuser::dt');
    $routes->add('grup', 'Pengaturanuser::grup');
    $routes->add('puskesmas', 'Pengaturanuser::puskesmas');
    $routes->add('getOne', 'Pengaturanuser::getOne');
    $routes->add('add', 'Pengaturanuser::add');
    $routes->add('edit', 'Pengaturanuser::edit');
    $routes->add('resetpass', 'Pengaturanuser::resetpass');
});

$routes->group('cetakruk', static function ($routes) {
    $routes->add('/', 'Cetakruk::index');
    $routes->add('tampil', 'Cetakruk::tampil');
    $routes->add('cetak', 'Cetakruk::cetak');
    $routes->add('expo', 'Cetakruk::expo');
});
$routes->group('cetakrpk', static function ($routes) {
    $routes->add('/', 'Cetakrpk::index');
    $routes->add('tampil', 'Cetakrpk::tampil');
    $routes->add('cetak', 'Cetakrpk::cetak');
    $routes->add('expo', 'Cetakrpk::expo');
});
$routes->group('cetakkak', static function ($routes) {
    $routes->add('/', 'Cetakkak::index');
    $routes->add('tampil', 'Cetakkak::tampil');
    $routes->add('cetak', 'Cetakkak::cetak');
    $routes->add('getupt', 'Cetakkak::getupt');
});
$routes->group('cetakrab', static function ($routes) {
    $routes->add('/', 'Cetakrab::index');
    $routes->add('tampil', 'Cetakrab::tampil');
    $routes->add('cetak', 'Cetakrab::cetak');
    $routes->add('getupt', 'Cetakrab::getupt');
});
$routes->group('cetakrabp', static function ($routes) {
    $routes->add('/', 'Cetakrabp::index');
    $routes->add('tampil', 'Cetakrabp::tampil');
    $routes->add('cetak', 'Cetakrabp::cetak');
    $routes->add('getupt', 'Cetakrabp::getupt');
});
$routes->group('drincian', static function ($routes) {
    $routes->add('/', 'Drincian::index');
    $routes->add('getAll', 'Drincian::getAll');
});
$routes->group('rrincian', static function ($routes) {
    $routes->add('/', 'Rrincian::index');
    $routes->add('getAll', 'Rrincian::getAll');
});
$routes->group('pilihtahun', static function ($routes) {
    $routes->add('/', 'Pilihtahun::index');
    $routes->add('tahun', 'Pilihtahun::tahun');
});
$routes->group('dkomponen', static function ($routes) {
    $routes->add('/', 'Dkomponen::index');
    $routes->add('getAll', 'Dkomponen::getAll');
});
$routes->group('dsubkomponen', static function ($routes) {
    $routes->add('/', 'Dsubkomponen::index');
    $routes->add('getAll', 'Dsubkomponen::getAll');
});
$routes->group('cetakba', static function ($routes) {
    $routes->add('/', 'Cetakba::index');
    $routes->add('tampil', 'Cetakba::tampil');
    $routes->add('cetak', 'Cetakba::cetak');
    $routes->add('getupt', 'Cetakba::getupt');
});
$routes->group('cetakraball', static function ($routes) {
    $routes->add('/', 'Cetakraball::index');
    $routes->add('tampil', 'Cetakraball::tampil');
    $routes->add('cetak', 'Cetakraball::cetak');
    $routes->add('expo', 'Cetakraball::expo');
});
$routes->group('cetakraballp', static function ($routes) {
    $routes->add('/', 'Cetakraballp::index');
    $routes->add('tampil', 'Cetakraballp::tampil');
    $routes->add('cetak', 'Cetakraballp::cetak');
    $routes->add('expo', 'Cetakraballp::expo');
});
$routes->group('lembarkerja', static function ($routes) {
    $routes->add('/', 'Lembarkerja::index');
    $routes->add('tampil', 'Lembarkerja::tampil');
    $routes->add('cetak', 'Lembarkerja::cetak');
    $routes->add('expo', 'Lembarkerja::expo');
});
$routes->group('cetakrka', static function ($routes) {
    $routes->add('/', 'Cetakrka::index');
    $routes->add('tampil', 'Cetakrka::tampil');
    $routes->add('cetak', 'Cetakrka::cetak');
    $routes->add('getrka', 'Cetakrka::getrka');
    $routes->add('getupt', 'Cetakrka::getupt');
});
$routes->group('cetaksub', static function ($routes) {
    $routes->add('/', 'Cetaksub::index');
    $routes->add('tampil', 'Cetaksub::tampil');
    $routes->add('cetak/(:any)', 'Cetaksub::cetak/$1');
    $routes->add('expo', 'Cetaksub::expo');
});
$routes->group('cetakrak', static function ($routes) {
    $routes->add('/', 'Cetakrak::index');
    $routes->add('tampil', 'Cetakrak::tampil');
    $routes->add('getrka', 'Cetakrak::getrka');
    $routes->add('getupt', 'Cetakrak::getupt');
});
$routes->group('ruk', static function ($routes) {
    $routes->add('/', 'Ruk::index');
    $routes->add('getbanner', 'Ruk::getbanner');
    $routes->add('getpuskesmas', 'Ruk::getpuskesmas');
    $routes->add('getbanner2', 'Ruk::getbanner2');
    $routes->add('getbanner3', 'Ruk::getbanner3');
    $routes->add('getprogram', 'Ruk::getprogram');
    $routes->add('getprog', 'Ruk::getprog');
    $routes->add('getAll', 'Ruk::getAll');
    $routes->add('add', 'Ruk::add');
    $routes->add('getOne', 'Ruk::getOne');
    $routes->add('edit', 'Ruk::edit');
    $routes->add('edit2', 'Ruk::edit2');
    $routes->add('getobjek', 'Ruk::getobjek');
    $routes->add('getobj', 'Ruk::getobj');
    $routes->add('getruk', 'Ruk::getruk');
    $routes->add('getOneRuk', 'Ruk::getOneRuk');
    $routes->add('remove', 'Ruk::remove');
});
$routes->group('usulan', static function ($routes) {
    $routes->add('/', 'Usulan::index');
    $routes->add('getAll', 'Usulan::getAll');
    $routes->add('getOne', 'Usulan::getOne');
    $routes->add('add', 'Usulan::add');
    $routes->add('edit', 'Usulan::edit');
    $routes->add('remove', 'Usulan::remove');
});
$routes->group('usulanrpk', static function ($routes) {
    $routes->add('/', 'Usulanrpk::index');
    $routes->add('getAll', 'Usulanrpk::getAll');
    $routes->add('getOne', 'Usulanrpk::getOne');
    $routes->add('add', 'Usulanrpk::add');
    $routes->add('edit', 'Usulanrpk::edit');
    $routes->add('remove', 'Usulanrpk::remove');
});
$routes->group('usulanrpkp', static function ($routes) {
    $routes->add('/', 'Usulanrpkp::index');
    $routes->add('getAll', 'Usulanrpkp::getAll');
    $routes->add('getOne', 'Usulanrpkp::getOne');
    $routes->add('add', 'Usulanrpkp::add');
    $routes->add('edit', 'Usulanrpkp::edit');
    $routes->add('remove', 'Usulanrpkp::remove');
});
$routes->group('rpk', static function ($routes) {
    $routes->add('/', 'Rpk::index');
    $routes->add('getbanner', 'Rpk::getbanner');
    $routes->add('getpuskesmas', 'Rpk::getpuskesmas');
    $routes->add('getbanner2', 'Rpk::getbanner2');
    $routes->add('getbanner3', 'Rpk::getbanner3');
    $routes->add('getprogram', 'Rpk::getprogram');
    $routes->add('getprog', 'Rpk::getprog');
    $routes->add('getAll', 'Rpk::getAll');
    $routes->add('add', 'Rpk::add');
    $routes->add('getOne', 'Rpk::getOne');
    $routes->add('edit', 'Rpk::edit');
    $routes->add('edit2', 'Rpk::edit2');
    $routes->add('getobjek', 'Rpk::getobjek');
    $routes->add('getobj', 'Rpk::getobj');
    $routes->add('remove', 'Rpk::remove');
});
$routes->group('rpkperubahan', static function ($routes) {
    $routes->add('/', 'Rpkperubahan::index');
    $routes->add('getbanner', 'Rpkperubahan::getbanner');
    $routes->add('getpuskesmas', 'Rpkperubahan::getpuskesmas');
    $routes->add('getbanner2', 'Rpkperubahan::getbanner2');
    $routes->add('getbanner3', 'Rpkperubahan::getbanner3');
    $routes->add('getprogram', 'Rpkperubahan::getprogram');
    $routes->add('getprog', 'Rpkperubahan::getprog');
    $routes->add('getAll', 'Rpkperubahan::getAll');
    $routes->add('add', 'Rpkperubahan::add');
    $routes->add('getOne', 'Rpkperubahan::getOne');
    $routes->add('edit', 'Rpkperubahan::edit');
    $routes->add('edit2', 'Rpkperubahan::edit2');
    $routes->add('getobjek', 'Rpkperubahan::getobjek');
    $routes->add('getobj', 'Rpkperubahan::getobj');
});

$routes->group('belanja2', static function ($routes) {
    $routes->add('/', 'Belanja2::index');
    $routes->add('getAll', 'Belanja2::getAll');
    $routes->add('getOne', 'Belanja2::getOne');
    $routes->add('add', 'Belanja2::add');
    $routes->add('edit', 'Belanja2::edit');
    $routes->add('remove', 'Belanja2::remove');
});

$routes->group('pendapatanp', static function ($routes) {
    $routes->add('/', 'Pendapatanp::index');
    $routes->add('getAll', 'Pendapatanp::getAll');
    $routes->add('getOne', 'Pendapatanp::getOne');
    $routes->add('add', 'Pendapatanp::add');
    $routes->add('edit', 'Pendapatanp::edit');
    $routes->add('remove', 'Pendapatanp::remove');
});

$routes->group('belanja', static function ($routes) {
    $routes->add('/', 'Belanja::index');
    $routes->add('getAll', 'Belanja::getAll');
    $routes->add('getOne', 'Belanja::getOne');
    $routes->add('add', 'Belanja::add');
    $routes->add('edit', 'Belanja::edit');
    $routes->add('remove', 'Belanja::remove');
    $routes->add('jumlahpendapatan', 'Belanja::jumlahpendapatan');
});

$routes->group('raball', static function ($routes) {
    $routes->add('/', 'Raball::index');
    $routes->add('getAll', 'Raball::getAll');
    $routes->add('getOne', 'Raball::getOne');
    $routes->add('add', 'Raball::add');
    $routes->add('edit', 'Raball::edit');
    $routes->add('remove', 'Raball::remove');
    $routes->add('tampil', 'Raball::tampil');
});
$routes->group('realisasi', static function ($routes) {
    $routes->add('/', 'Realisasi::index');
    $routes->add('getAll', 'Realisasi::getAll');
    $routes->add('getOne', 'Realisasi::getOne');
    $routes->add('add', 'Realisasi::add');
    $routes->add('edit', 'Realisasi::edit');
    $routes->add('getrekening', 'Realisasi::getrekening');
    $routes->add('tampil', 'Realisasi::tampil');
});

$routes->group('rak', static function ($routes) {
    $routes->add('/', 'Rak::index');
    $routes->add('getAll', 'Rak::getAll');
    $routes->add('getOne', 'Rak::getOne');
    $routes->add('add', 'Rak::add');
    $routes->add('edit', 'Rak::edit');
    $routes->add('remove', 'Rak::remove');
    $routes->add('tampil', 'Rak::tampil');
});

$routes->group('bulan', static function ($routes) {
    $routes->add('/', 'Bulan::index');
    $routes->add('getAll', 'Bulan::getAll');
    $routes->add('getOne', 'Bulan::getOne');
    $routes->add('add', 'Bulan::add');
    $routes->add('edit', 'Bulan::edit');
    $routes->add('getSelect', 'Bulan::getSelect');
});

$routes->group('modul', static function ($routes) {
    $routes->add('/', 'Modul::index');
    $routes->add('getAll', 'Modul::getAll');
    $routes->add('getOne', 'Modul::getOne');
    $routes->add('add', 'Modul::add');
    $routes->add('edit', 'Modul::edit');
    $routes->add('remove', 'Modul::remove');
    $routes->add('basic', 'Modul::basic');
});

$routes->group('timeline', static function ($routes) {
    $routes->add('/', 'Timeline::index');
    $routes->add('getAll', 'Timeline::getAll');
    $routes->add('getOne', 'Timeline::getOne');
    $routes->add('add', 'Timeline::add');
    $routes->add('edit', 'Timeline::edit');
    $routes->add('remove', 'Timeline::remove');
    $routes->add('basic', 'Timeline::basic');
});

$routes->group('rrealisasi', static function ($routes) {
    $routes->add('/', 'Rrealisasi::index');
    $routes->add('tampil', 'Rrealisasi::tampil');
    $routes->add('cetak', 'Rrealisasi::cetak');
    $routes->add('expo', 'Rrealisasi::expo');
});

$routes->group('realisasidpa', static function ($routes) {
    $routes->add('/', 'Realisasidpa::index');
    $routes->add('tampil', 'Realisasidpa::tampil');
    $routes->add('cetak', 'Realisasidpa::cetak');
    $routes->add('expo', 'Realisasidpa::expo');
    $routes->add('getupt', 'Realisasidpa::getupt');
});

$routes->group('rsubkomponen', static function ($routes) {
    $routes->add('/', 'Rsubkomponen::index');
    $routes->add('getAll', 'Rsubkomponen::getAll');
});

$routes->group('rkomponen', static function ($routes) {
    $routes->add('/', 'Rkomponen::index');
    $routes->add('getAll', 'Rkomponen::getAll');
});

$routes->group('cetakrkap', static function ($routes) {
    $routes->add('/', 'Cetakrkap::index');
    $routes->add('tampil', 'Cetakrkap::tampil');
    $routes->add('cetak', 'Cetakrkap::cetak');
    $routes->add('getrka', 'Cetakrkap::getrka');
    $routes->add('getupt', 'Cetakrkap::getupt');
});
$routes->group('analisaruk', static function ($routes) {
    $routes->add('/', 'Analisaruk::index');
    $routes->add('getAll', 'Analisaruk::getAll');
});
$routes->group('pagu2023', static function ($routes) {
    $routes->add('/', 'Pagu2023::index');
    $routes->add('basic', 'Pagu2023::basic');
    $routes->add('getselect', 'Pagu2023::getSelect');
    $routes->add('getOne', 'Pagu2023::getOne');
    $routes->add('add', 'Pagu2023::add');
    $routes->add('edit', 'Pagu2023::edit');
    $routes->add('remove', 'Pagu2023::remove');
});
$routes->group('profil', static function ($routes) {
    $routes->add('/', 'Profil::index');
    $routes->add('edit', 'Profil::edit');
    $routes->add('editpass', 'Profil::editpass');
    $routes->add('upload', 'Profil::upload');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}