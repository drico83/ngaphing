<div class="left-sidenav">
    <!-- LOGO -->
    <div class="brand">
        <a href="index.html" class="logo">
            <span>
                <img src="<?= base_url() ?>/assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
            </span>
            <span>
                <img src="<?= base_url() ?>/assets/images/logo.png" alt="logo-large" class="logo-lg logo-light">
                <img src="<?= base_url() ?>/assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    <!--end logo-->
    <div class="menu-content h-100" data-simplebar>
        <ul class="metismenu left-sidenav-menu">
            <li class="menu-label mt-0">Main</li>
            <li>
                <a href="<?= base_url('dashboard') ?>"><i data-feather="home"
                        class="align-self-center menu-icon"></i><span>Dashboard</span></a>
            </li>
            <hr class="hr-dashed hr-menu">
            <li class="menu-label mt-0">Menu Input</li>
            <li>
                <a href="javascript: void(0);"><i data-feather="edit-3"
                        class="align-self-center menu-icon"></i><span>Perencanaan</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('ruk') ?>"><i
                                class="ti-control-record"></i>Input RUK</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('analisaruk') ?>"><i
                                class="ti-control-record"></i>Analisa Belanja RUK</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('rpk') ?>"><i
                                class="ti-control-record"></i>Input RPK</a></li>
                    <?php if (in_groups('admin') || in_groups('programmer')) { ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('pagu2023') ?>"><i
                                class="ti-control-record"></i>Input Pagu BOK 2023</a></li>
                    <?php } ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('rpkperubahan') ?>"><i
                                class="ti-control-record"></i>Input RPK Perubahan</a></li>
                    <li>
                        <a href="javascript: void(0);"><i class="ti-control-record"></i>Input Pagu BLUD<span
                                class="menu-arrow left-has-menu"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url('belanja2') ?>">Proyeksi Belanja BLUD</a></li>
                            <li><a href="<?= base_url('pendapatanp') ?>">Pendapatan Perubahan</a></li>
                            <li><a href="<?= base_url('belanja') ?>">Belanja Perubahan</a></li>
                        </ul>
                    </li>

                </ul>
            </li>
            <li>
                <a href="javascript: void(0);"><i data-feather="dollar-sign"
                        class="align-self-center menu-icon"></i><span>Penganggaran</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('raball') ?>"><i
                                class="ti-control-record"></i>Input RAK</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('') ?>"><i
                                class="ti-control-record"></i>Hitung Insentif UKM</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('') ?>"><i
                                class="ti-control-record"></i>Hitung Jasa Pelayanan BLUD</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);"><i data-feather="dollar-sign"
                        class="align-self-center menu-icon"></i><span>Penatausahaan</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('realisasi') ?>"><i
                                class="ti-control-record"></i>Input Realisasi BOK</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('pengajuan') ?>"><i
                                class="ti-control-record"></i>Input Pengajuan BOK</a></li>
                </ul>
            </li>
            <hr class="hr-dashed hr-menu">
            <li class="menu-label mt-0">Referensi
            </li>
            <li>
            <li>
                <a href="<?= base_url('subkomponen2') ?>"><i data-feather="layers"
                        class="align-self-center menu-icon"></i><span>Menu berdasarkan Program</span></a>
            </li>
            </li>
            <hr class="hr-dashed hr-menu">
            <li class="menu-label mt-0">Dokumentasi</li>
            <li>
                <a href="javascript: void(0);"><i data-feather="folder"
                        class="align-self-center menu-icon"></i><span>RUK</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakruk') ?>"><i
                                class="ti-control-record"></i>Cetak RUK</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);"><i data-feather="folder-plus"
                        class="align-self-center menu-icon"></i><span>RPK</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakrpk') ?>"><i
                                class="ti-control-record"></i>Cetak RPK</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakkak') ?>"><i
                                class="ti-control-record"></i>Cetak Draft KAK</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakrab') ?>"><i
                                class="ti-control-record"></i>Cetak Draft RAB</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakrabp') ?>"><i
                                class="ti-control-record"></i>Cetak Draft RABP</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);"><i data-feather="lock"
                        class="align-self-center menu-icon"></i><span>BOK</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('drincian') ?>"><i
                                class="ti-control-record"></i>Daftar Rincian</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('dkomponen') ?>"><i
                                class="ti-control-record"></i>Daftar Komponen</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('dsubkomponen') ?>"><i
                                class="ti-control-record"></i>Daftar Sub Komponen</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakba') ?>"><i
                                class="ti-control-record"></i>Cetak BA</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakraball') ?>"><i
                                class="ti-control-record"></i>Cetak RAB</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakraballp') ?>"><i
                                class="ti-control-record"></i>Cetak RAB Perubahan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('lembarkerja') ?>"><i
                                class="ti-control-record"></i>Cetak LK Perubahan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakrka') ?>"><i
                                class="ti-control-record"></i>Cetak RKA SIPD</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetaksub') ?>"><i
                                class="ti-control-record"></i>Cetak RKA 2.2 SIPD</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakrak') ?>"><i
                                class="ti-control-record"></i>Cetak RAK SIPD</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('cetakrkap') ?>"><i
                                class="ti-control-record"></i>Cetak RKAP SIPD</a></li>
                    <li>
                        <a href="javascript: void(0);"><i class="ti-control-record"></i>Realisasi BOK<span
                                class="menu-arrow left-has-menu"><i class="mdi mdi-chevron-right"></i></span></a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="<?= base_url('rrealisasi') ?>">Realisasi Kumulatif</a></li>
                            <li><a href="<?= base_url('realisasidpa') ?>">Realisasi DPA</a></li>
                            <li><a href="<?= base_url('rrincian') ?>">Rincian</a></li>
                            <li><a href="<?= base_url('rkomponen') ?>">Komponen</a></li>
                            <li><a href="<?= base_url('rsubkomponen') ?>">Sub Komponen</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);"><i data-feather="unlock"
                        class="align-self-center menu-icon"></i><span>BLUD</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('') ?>"><i
                                class="ti-control-record"></i>RBA Pendapatan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('') ?>"><i
                                class="ti-control-record"></i>RBA Belanja</a></li>

                </ul>
            </li>
            <hr class="hr-dashed hr-menu">
            <li class="menu-label mt-0">Pengaturan</li>
            <?php
            if (in_groups('admin') || in_groups('programmer')) { ?>
            <li>
                <a href="javascript: void(0);"><i data-feather="codepen"
                        class="align-self-center menu-icon"></i><span>Aplikasi</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('upt') ?>"><i
                                class="ti-control-record"></i> UPT</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('program') ?>"><i
                                class="ti-control-record"></i> Program</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('rincian') ?>"><i
                                class="ti-control-record"></i> Rincian</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('komponen') ?>"><i
                                class="ti-control-record"></i> Komponen</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('subkomponen') ?>"><i
                                class="ti-control-record"></i> Sub Komponen</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('kodrek') ?>"><i
                                class="ti-control-record"></i> Kode Rekening</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('kodebelanja') ?>"><i
                                class="ti-control-record"></i> Kode Belanja</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('penyusun') ?>"><i
                                class="ti-control-record"></i>Penyusun Kode Belanja</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('satuan') ?>"><i
                                class="ti-control-record"></i>Satuan Belanja</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="<?= base_url('modul') ?>"><i
                                class="ti-control-record"></i> Pengaturan Modul</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('timeline') ?>"><i
                                class="ti-control-record"></i> Pengaturan Jadwal</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);"><i data-feather="user"
                        class="align-self-center menu-icon"></i><span>User</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('profil') ?>"><i
                                class="ti-control-record"></i> User Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('pengaturanuser') ?>"><i
                                class="ti-control-record"></i> Pengaturan User</a></li>

                </ul>
            </li>
            <li>
                <a href="<?= base_url('billing') ?>"><i data-feather="airplay"
                        class="align-self-center menu-icon a-animate-blink"></i><span>Billing Payment</span></a>
            </li>
            <?php } else { ?>
            <li>
                <a href="javascript: void(0);"><i data-feather="codepen"
                        class="align-self-center menu-icon"></i><span>Aplikasi</span><span class="menu-arrow"><i
                            class="mdi mdi-chevron-right"></i></span></a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('upt') ?>"><i
                                class="ti-control-record"></i> UPT</a></li>
                </ul>
            </li>
            <li>
                <a href="<?= base_url('billing') ?>"><i data-feather="airplay"
                        class="align-self-center menu-icon a-animate-blink"></i><span>Billing Payment</span></a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>