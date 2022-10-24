<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<title>ePlanning - RPK</title>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap5.min.css">
<link href="<?= base_url('assets') ?>/animate/animate.css" rel="stylesheet" type="text/css">

<?= $this->endSection() ?>

<?= $this->section('judul') ?>

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Menu Input</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Perencanaan</a></li>
                            <li class="breadcrumb-item active">Input RPK</li>
                        </ol>
                    </div>
                    <!--end col-->
                    <div class="col text-end">
                        <h4>Batas Waktu Input : </h4>
                    </div>
                    <div class="col-auto">

                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-purple rounded text-center" id="hari">12</span> <span
                                style="font-size: 12px;">Hari</span>
                        </div>
                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-pink rounded" id="jam">12</span> <span
                                style="font-size: 12px;">Jam</span>
                        </div>
                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-warning rounded" id="menit">12</span><span
                                style="font-size: 12px;">Menit</span>
                        </div>
                        <div class="avatar-box thumb-lg text-center me-2">
                            <span class="avatar-title bg-soft-success rounded" id="detik">12</span><span
                                style="font-size: 12px;">Detik</span>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <?= $this->endSection() ?>

    <?= $this->section("isi") ?>
    <!-- Main content -->
    <div class="row">
        <div class="col-12">

            <div class="div" id="tabel1">
                <!-- banner -->
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Belum Dinilai</p>
                                        <h3 class="m-0" id="total1">0</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="ctotal">0</i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Rejected</p>
                                        <h3 class="m-0" id="reject1">00:18</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="creject1"><i class="mdi mdi-trending-up"></i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="clock" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Discuss</p>
                                        <h3 class="m-0" id="discuss1">$2400</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-danger"
                                                id="cdiscuss1"><i class="mdi mdi-trending-down"></i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="activity" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Approved</p>
                                        <h3 class="m-0" id="approve1">85000</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="capprove1"><i class="mdi mdi-trending-up"></i>10.5%</span> Kegiatan
                                        </p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="briefcase"
                                                class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
                <!-- endbanner -->

                <!--end row-->
                <div class="main-card mb-3 card animated lightSpeedIn">
                    <div class="card-header">Daftar Unit Pelaksana
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_table1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No.</th>
                                        <th rowspan="2">Nama UPT</th>
                                        <th rowspan="2">Pagu</th>
                                        <th colspan="5">Anggaran BOK</th>
                                        <th rowspan="2" class="text-center">Status Approved</th>
                                        <th rowspan="2" class="text-center">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>UKM</th>
                                        <th>% UKM</th>
                                        <th>COVID-19</th>
                                        <th>% COVID-19</th>
                                        <th>Jumlah Usulan</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Jumlah</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="pkm" id="pkm">

            <div id="tabel2">
                <!-- banner2 -->
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Belum Dinilai</p>
                                        <h3 class="m-0" id="total2">0</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="ctotal2">0</span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Rejected</p>
                                        <h3 class="m-0" id="reject2">00:18</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="creject2"><i class="mdi mdi-trending-up"></i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="clock" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Discuss</p>
                                        <h3 class="m-0" id="discuss2">$2400</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-danger"
                                                id="cdiscuss2"><i class="mdi mdi-trending-down"></i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="activity" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Approved</p>
                                        <h3 class="m-0" id="approve2">85000</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="capprove2"><i class="mdi mdi-trending-up"></i>10.5%</span> Kegiatan
                                        </p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="briefcase"
                                                class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
                <!-- endbanner -->

                <div class="main-card mb-3 card animated lightSpeedIn">
                    <div class="card-header d-flex justify-content-between">
                        <div class="col">
                            <h5>Daftar Program Pelayanan</h5>
                            <h6 id="puskes1">Puskesmas</h6>
                        </div>

                        <div>
                            <button class='btn btn-warning text-white' onclick="back1()" title='Back'><i
                                    class='fas fa-backward me-1'></i>Kembali</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_table2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Program Pelayanan</th>
                                        <th>Jumlah Usulan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Jumlah</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <input type="hidden" name="prog" id="prog">
            <input type="hidden" name="ruk" id="ruk">
            <input type="hidden" name="men" id="men">
            <input type="hidden" name="status" id="status">


            <div id="tabel3">

                <!-- banner3 -->
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Belum Dinilai</p>
                                        <h3 class="m-0" id="total3">0</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="ctotal3">0</i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Rejected</p>
                                        <h3 class="m-0" id="reject3">00:18</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="creject3"><i class="mdi mdi-trending-up"></i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="clock" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Discuss</p>
                                        <h3 class="m-0" id="discuss3">$2400</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-danger"
                                                id="cdiscuss3"><i class="mdi mdi-trending-down"></i></span> Kegiatan</p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="activity" class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                    <div class="col-md-6 col-lg-3">
                        <div class="card report-card">
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col">
                                        <p class="text-dark mb-0 fw-semibold">Approved</p>
                                        <h3 class="m-0" id="approve3">85000</h3>
                                        <p class="mb-0 text-truncate text-muted"><span class="text-success"
                                                id="capprove3"><i class="mdi mdi-trending-up"></i>10.5%</span> Kegiatan
                                        </p>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="report-main-icon bg-light-alt">
                                            <i data-feather="briefcase"
                                                class="align-self-center text-muted icon-sm"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end card-body-->
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
                <!-- endbanner -->

                <div class="main-card mb-3 card animated lightSpeedIn">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 id='judul'>Rencana</h5>
                            <h5 id='puskes2'>Puskesmas</h5>
                        </div>
                        <div class="justify-content-end">
                            <?php if (in_groups('admin')) { ?>
                            <button class="btn btn-info me-1" onclick="add()" title="Add">
                                <i class="fa fa-business-time fa-w-20"></i>
                                Tambah RUK
                            </button>
                            <?php
                            } ?>
                            <button class='btn btn-warning text-white' onclick="back2()" title='Kembali'><i
                                    class='fas fa-backward'></i>Kembali</button>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_table3" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kegiatan</th>
                                        <th>Keterangan</th>
                                        <th>Tujuan</th>
                                        <th>Sasaran</th>
                                        <th>Target Sasaran</th>
                                        <th>Penanggungjawab</th>
                                        <th>Jadwal Pelaksanaan</th>
                                        <th>Lokasi Pelaksanaan</th>
                                        <th>Anggaran</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th colspan="9"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- modal -->
            <div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Pelaksaan Kegiatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add-form" class="pl-3 pr-3">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>
                                <div class="form-row">
                                    <input type="hidden" id="id_ruk" name="id_ruk" class="form-control"
                                        placeholder="No." maxlength="11" required>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="exampleEmail" class="col-sm-2 col-form-label">Kegiatan:</label>
                                    <div class="col-sm-10">
                                        <select id="idMenu" name="idMenu" class="custom-select" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="exampleEmail" class="col-sm-2 col-form-label">Keterangan:</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="keterangan" name="keterangan" class="form-control"
                                            placeholder="Keterangan" maxlength="255">
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="tujuan" class="col-sm-2 col-form-label"> Tujuan: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tujuan" name="tujuan" class="form-control"
                                            placeholder="Tujuan" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="sasaran" class="col-sm-2 col-form-label"> Sasaran: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="sasaran" name="sasaran" class="form-control"
                                            placeholder="Sasaran" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="target" class="col-sm-2 col-form-label"> Target Sasaran: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="target" name="target" class="form-control"
                                            placeholder="Target Sasaran" maxlength="255" required>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="tgjawab" class="col-sm-2 col-form-label"> Penanggungjawab: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tgjawab" name="tgjawab" class="form-control"
                                            placeholder="Penanggungjawab" maxlength="255" required>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="jadwal" class="col-sm-2 col-form-label"> Jadwal Pelaksanaan: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="jadwal" name="jadwal" rows="3"
                                            placeholder="Jadwal Pelaksanaan "></textarea>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="lokasi" class="col-sm-2 col-form-label"> Lokasi Pelaksanaan : <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="lokasi" name="lokasi" rows="3"
                                            placeholder="Lokasi Pelaksanaan "></textarea>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="add-form-btn">Tambah</button>

                        </div>
                        </form>
                    </div><!-- /.modal-content -->

                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Add modal content -->
            <div id="edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ubah data sub komponen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-form" class="pl-3 pr-3">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>
                                <div class="form-row">
                                    <input type="hidden" id="id_ruk" name="id_ruk" class="form-control"
                                        placeholder="No." maxlength="11" required>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="exampleEmail" class="col-sm-2 col-form-label">Kegiatan:</label>
                                    <div class="col-sm-10">
                                        <select id="idMenu" name="idMenu" class="custom-select" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="exampleEmail" class="col-sm-2 col-form-label">Keterangan:</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="keterangan" name="keterangan" class="form-control"
                                            placeholder="Keterangan" maxlength="255">
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="tujuan" class="col-sm-2 col-form-label"> Tujuan: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tujuan" name="tujuan" class="form-control"
                                            placeholder="Tujuan" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="sasaran" class="col-sm-2 col-form-label"> Sasaran: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="sasaran" name="sasaran" class="form-control"
                                            placeholder="Sasaran" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="target" class="col-sm-2 col-form-label"> Target Sasaran: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="target" name="target" class="form-control"
                                            placeholder="Target Sasaran" maxlength="255" required>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="tgjawab" class="col-sm-2 col-form-label"> Penanggungjawab: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="tgjawab" name="tgjawab" class="form-control"
                                            placeholder="Penanggungjawab" maxlength="255" required>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="jadwal" class="col-sm-2 col-form-label"> Jadwal Pelaksanaan: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="jadwal" name="jadwal" rows="3"
                                            placeholder="Jadwal Pelaksanaan "></textarea>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="lokasi" class="col-sm-2 col-form-label"> Lokasi Pelaksanaan : <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="lokasi" name="lokasi" rows="3"
                                            placeholder="Lokasi Pelaksanaan "></textarea>
                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="edit-form-btn">Simpan</button>
                        </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div id="edit2-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Verivikasi dan Validasi RUK</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="edit2-form" class="pl-3 pr-3">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                        maxlength="11" required>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="exampleEmail" class="col-sm-2 col-form-label">Kegiatan:</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="keterangan" name="keterangan" class="form-control"
                                            placeholder="Keterangan" maxlength="255" disabled>

                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status:</label>
                                    <div class="col-sm-10">
                                        <select id="status" name="status" class="custom-select" required>
                                            <option value="Approve">Approve</option>
                                            <option value="Discuss">Discuss</option>
                                            <option value="Reject">Reject</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="catatan" class="col-sm-2 col-form-label"> Catatan: <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="catatan" name="catatan" rows="3"
                                            placeholder="Tanggal : Catatan : Petugas : "></textarea>

                                    </div>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="edit-form-btn">Simpan</button>
                        </div>
                        </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div id="objek-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Rincian Objek</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add-objek-form" class="pl-3 pr-3">

                                <input type="hidden" id="id" name="id" class="form-control" placeholder="No."
                                    maxlength="11">
                                <div class="position-relative form-group row">
                                    <label for="idBelanja" class="col-sm-2 col-form-label"> Komponen Belanja: <span
                                            class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <select id="idBelanja" name="idBelanja" class="custom-select" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="harga" class="col-sm-2 col-form-label"> Harga : <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" id="harga" name="harga" class="form-control"
                                            placeholder="harga" maxlength="255" disabled>
                                    </div>
                                    <label for="satuan" class="col-sm-2 col-form-label"> Satuan : <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-4">
                                        <input type="text" id="satuan" name="satuan" class="form-control"
                                            placeholder="satuan" maxlength="255" disabled>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="ket" class="col-sm-2 col-form-label"> Keterangan : <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" id="ket" name="ket" class="form-control"
                                            placeholder="keterangan" maxlength="255" required>
                                    </div>

                                </div>
                                <div class="position-relative form-group row">
                                    <label for="idBelanja" class="col-sm-2 col-form-label"> Koefisien (perkalian) <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-5">

                                        <input type="number" id="vol1" name="vol1" class="form-control"
                                            placeholder="Volume 1" maxlength="11" number="true" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <select id="sat1" name="sat1" class="custom-select" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="idBelanja" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-5">
                                        <input type="number" id="vol2" name="vol2" class="form-control"
                                            placeholder="Volume 2" maxlength="11" number="true">
                                    </div>
                                    <div class="col-sm-5">
                                        <select id="sat2" name="sat2" class="custom-select">
                                        </select>
                                    </div>
                                </div>

                                <div class="position-relative form-group row">
                                    <label for="idBelanja" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-5">
                                        <input type="number" id="vol3" name="vol3" class="form-control"
                                            placeholder="Volume 3" maxlength="11" number="true">
                                    </div>
                                    <div class="col-sm-5">
                                        <select id="sat3" name="sat3" class="custom-select">
                                        </select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="idBelanja" class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-5">
                                        <input type="number" id="vol4" name="vol4" class="form-control"
                                            placeholder="Volume 4" maxlength="11" number="true">
                                    </div>
                                    <div class="col-sm-5">
                                        <select id="sat4" name="sat4" class="custom-select">
                                        </select>
                                    </div>
                                </div>
                                <div class="position-relative form-group row">
                                    <label for="vol_total" class="col-sm-2 col-form-label"> Jumlah Volume : <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="number" id="vol_total" name="vol_total" class="form-control"
                                            placeholder="Jumlah Volume" maxlength="11" number="true" disabled>
                                    </div>
                                    <label for="harga_total" class="col-sm-2 col-form-label"> Jumlah Harga : <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="number" id="harga_total" name="harga_total" class="form-control"
                                            placeholder="Jumlah Harga" maxlength="11" number="true" disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success" id="objek-form-btn">Simpan</button>
                                </div>
                            </form>
                            <hr>
                            <div class="table-responsive">
                                <table id="data_table_modal" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode Rekening</th>
                                            <th>Komponen Belanja</th>
                                            <th>Keterangan</th>
                                            <th>Harga Satuan</th>
                                            <th>Volume</th>
                                            <th>Volume Total</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>


            <!-- /ADD modal content -->
        </div><!-- container -->


        <?= $this->endSection() ?>
        <!-- /.content -->


        <!-- page script -->
        <?= $this->section("js") ?>
        <script src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js"></script>

        <script>
        //timer
        CountDownTimer("<?php echo $timer->waktu; ?>", 'hari', 'jam', 'menit', 'detik');

        function CountDownTimer(dt, id1, id2, id3, id4) {
            var end = new Date(dt);

            var _second = 1000;
            var _minute = _second * 60;
            var _hour = _minute * 60;
            var _day = _hour * 24;
            var timer;

            function showRemaining() {
                var now = new Date();
                var distance = end - now;
                var distance1 = now - end;
                if (distance1 > 0) {
                    clearInterval(timer);

                    document.getElementById(id1).innerHTML = 0;
                    document.getElementById(id2).innerHTML = 0;
                    document.getElementById(id3).innerHTML = 0;
                    document.getElementById(id4).innerHTML = 0;
                    return;
                }
                var days = Math.floor(distance / _day);
                var hours = Math.floor((distance % _day) / _hour);
                var minutes = Math.floor((distance % _hour) / _minute);
                var seconds = Math.floor((distance % _minute) / _second);

                document.getElementById(id1).innerHTML = days;
                document.getElementById(id2).innerHTML = hours;
                document.getElementById(id3).innerHTML = minutes;
                document.getElementById(id4).innerHTML = seconds;


            }

            timer = setInterval(showRemaining, 1000);
        }
        //endtimer

        function status1() {
            $.ajax({
                type: "get",
                url: "<?php echo base_url('rpk/getbanner') ?>",
                dataType: "json",
                success: function(response) {

                    $('#reject1').html(response.data.reject1.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#total1').html(response.data.disclaim1.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#pagu1').html(response.pagu.anggaran.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#approve1').html(response.data.approve1.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));

                    $('#creject1').html(response.count.creject1 + ' kegiatan');
                    $('#ctotal1').html(response.count.cdisclaim1 + ' kegiatan');
                    $('#cdiscuss1').html(response.pagu.anggaran + ' kegiatan');
                    $('#capprove1').html(response.count.capprove1 + ' kegiatan');
                }
            });
        };

        function status2() {
            $.ajax({
                type: "post",
                url: "<?php echo base_url('rpk/getbanner2') ?>",
                data: {
                    pkm: $('#pkm').val()


                },
                dataType: "json",
                success: function(response) {

                    $('#reject2').html(response.data.reject2.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#total2').html(response.data.disclaim2.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#pagu2').html(response.pagu.anggaran.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#approve2').html(response.data.approve2.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));

                    $('#creject2').html(response.count.creject2 + ' kegiatan');
                    $('#ctotal2').html(response.count.cdisclaim2 + ' kegiatan');
                    $('#cdiscuss2').html(response.count.cdiscuss2 + ' kegiatan');
                    $('#capprove2').html(response.count.capprove2 + ' kegiatan');

                }
            });
        };

        function status3() {
            $.ajax({
                type: "post",
                url: "<?php echo base_url('rpk/getbanner3') ?>",
                data: {
                    pkm: $('#pkm').val(),
                    prog: $('#prog').val(),
                },
                dataType: "json",
                success: function(response) {
                    $('#reject3').html(response.data.reject3.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#total3').html(response.data.disclaim3.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#discuss3').html(response.data.discuss3.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));
                    $('#approve3').html(response.data.approve3.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                        "$1."));

                    $('#creject3').html(response.count.creject3 + ' kegiatan');
                    $('#ctotal3').html(response.count.cdisclaim3 + ' kegiatan');
                    $('#cdiscuss3').html(response.count.cdiscuss3 + ' kegiatan');
                    $('#capprove3').html(response.count.capprove3 + ' kegiatan');


                }
            });
        };
        var dTable1;
        var dTable2;
        var dTable3;

        $(function() {
            $('#data_table1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                "ajax": {
                    "url": '<?php echo base_url($controller . '/getpuskesmas') ?>',
                    "type": "get",
                    "dataType": "json",
                    async: "true"
                },
                "columnDefs": [{
                    "orderable": false,
                    "render": $.fn.dataTable.render.number('.', ',', 0),
                    "targets": [2, 3, 4, 5, 6, 7]
                }],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // computing column Total of the complete result 
                    var jumlah1 = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jumlah2 = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jumlah3 = api
                        .column(5)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var jumlah4 = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var numFormat = $.fn.dataTable.render.number('.', ',', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Jumlah');

                    $(api.column(2).footer()).html(numFormat(jumlah1));
                    $(api.column(3).footer()).html(numFormat(jumlah2));
                    $(api.column(5).footer()).html(numFormat(jumlah3));
                    $(api.column(7).footer()).html(numFormat(jumlah4));
                },
            });

        });


        function subkom() {
            $.ajax({
                type: "post",
                data: {
                    prog: $('#prog').val(),
                    pkm: $('#pkm').val()
                },
                url: "<?php echo base_url('ruk/getruk') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-form #idMenu').html(response.data);
                        $('#add-form #idMenu').select2({
                            dropdownParent: $('#add-form'),
                            width: "100%",

                        });
                    }
                }
            });
        }

        function program(id) {
            $('#pkm').val(id);
            $('#tabel2').show();
            $('#tabel1').hide();
            status2();

            $.ajax({
                type: "post",
                url: "<?= base_url('upt/getOne') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    $('#puskes1, #puskes2').html('Puskesmas : ' + response.pkm);
                }
            });

            $('#data_table2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                "ajax": {
                    "url": '<?php echo base_url($controller . '/getprogram') ?>',
                    "type": "POST",
                    "data": {
                        pkm: $('#pkm').val(),

                    },
                    "dataType": "json",
                    async: "true"
                },
                "columnDefs": [{
                    "orderable": false,
                    "render": $.fn.dataTable.render.number('.', ',', 0),
                    "targets": [2]
                }],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // computing column Total of the complete result 
                    var jumlah1 = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var numFormat = $.fn.dataTable.render.number('.', ',', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Jumlah');

                    $(api.column(2).footer()).html(numFormat(jumlah1));
                },
            });

        }

        function rukchange() {
            $.ajax({
                type: "post",
                url: "<?php echo base_url('ruk/getOneRuk') ?>",
                data: {
                    id: $('#add-form #idMenu').val(),
                    pkm: $('#pkm').val()
                },
                dataType: "json",
                success: function(response) {
                    $("#add-form #tujuan").val(response.tujuan);
                    $("#add-form #sasaran").val(response.sasaran);
                    $("#add-form #target").val(response.target);
                    $("#add-form #tgjawab").val(response.tgjawab);
                    $("#add-form #keterangan").val(response.keterangan);
                    $("#add-form #id_ruk").val(response.id);
                }
            });
        }

        function usulan(prog) {
            $('#prog').val(prog);
            $.ajax({
                url: '<?php echo base_url($controller . '/getprog') ?>',
                type: 'post',
                data: {
                    prog: $('#prog').val(),

                },
                dataType: 'json',
                success: function(response) {

                    $("#judul").html("RPK " + response.nama_program);

                }
            });

            status3();
            $('#tabel3').show();
            $('#tabel2').hide();
            $('#data_table3').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                "ajax": {
                    "url": '<?php echo base_url($controller . '/getAll') ?>',
                    "type": "POST",
                    "data": {
                        pkm: $('#pkm').val(),
                        prog: $('#prog').val()
                    },
                    "dataType": "json",
                    async: "true"
                },
                "columnDefs": [{
                    "orderable": false,
                    "render": $.fn.dataTable.render.number('.', ',', 0),
                    "targets": [9]
                }],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // computing column Total of the complete result 
                    var jumlah = api
                        .column(9)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var numFormat = $.fn.dataTable.render.number('.', ',', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Jumlah');

                    $(api.column(9).footer()).html(numFormat(jumlah));
                },
            });

        }

        $(document).ready(function() {
            $('#tabel2').hide();
            $('#tabel3').hide();
            status1();
            $('#edit2-form #status').select2({
                dropdownParent: $('#edit2-form'),
                width: '100%'
            });
        });

        function back1() {
            $('#tabel1').show();
            $('#data_table1').DataTable().ajax.reload(null, false)
                .draw(false);
            status1();
            $('#tabel2').hide();
            $('#data_table2').DataTable().destroy();
        }

        function back2() {
            $('#tabel2').show();
            status2();
            $('#tabel3').hide();
            $('#data_table3').DataTable().destroy();
        }

        function isi() {
            $.ajax({
                type: "post",
                data: objek = $('#prog').val(),
                url: "<?php echo base_url($controller . '/getobjek') ?>",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-objek-form #idBelanja').html(response.data);
                        $('#add-objek-form #idBelanja').select2({
                            dropdownParent: $('#add-form'),
                            width: "100%",

                        });
                    }
                }
            });

        }

        function belanja(ruk, menu) {


            $("#ruk").val(ruk);
            $("#men").val(menu);
            $.ajax({
                type: "post",
                url: "<?php echo base_url($controller . '/getOne') ?>",
                data: {
                    id: $("#ruk").val()
                },
                dataType: "json",
                success: function(response) {
                    $("#status").val(response.status);
                }
            });

            $("#add-objek-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
            $.ajax({
                type: "post",
                url: "<?php echo base_url($controller . '/getobjek') ?>",
                data: {
                    objek: $("#men").val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('#add-objek-form #idBelanja').html(response.data);
                        $('#add-objek-form #idBelanja').select2({
                            dropdownParent: $('#add-objek-form'),
                            width: "100%",

                        });
                    }
                }
            });
            $.ajax({
                type: "get",
                url: "<?php echo base_url('satuan/getsatuan') ?>",
                dataType: "json",
                success: function(response) {
                    $('#add-objek-form #sat1, #add-objek-form #sat2, #add-objek-form #sat3, #add-objek-form #sat4')
                        .html(response.data);
                    $('#add-objek-form #sat1, #add-objek-form #sat2, #add-objek-form #sat3, #add-objek-form #sat4')
                        .select2({
                            dropdownParent: $('#add-objek-form'),
                            width: "100%",

                        });
                }
            });
            var idPkm = $('#pkm').val();
            var idProgram = $('#prog').val();
            $('#data_table_modal').DataTable().destroy();
            $('#data_table_modal').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" + "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'copy', 'excel', 'pdf', 'print',
                ],
                "ajax": {
                    "url": '<?php echo base_url('usulanrpk/getAll') ?>',
                    "type": "POST",
                    "data": {
                        objek: $('#ruk').val(),
                        idPkm: $('#pkm').val(),
                        idProgram: $('#prog').val()
                    },
                    "dataType": "json",
                    async: "true"
                },
                "columnDefs": [{
                    "orderable": false,
                    "render": $.fn.dataTable.render.number('.', ',', 0),
                    "targets": [4, 6, 7]
                }],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // computing column Total of the complete result 
                    var jumlah = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var numFormat = $.fn.dataTable.render.number('.', ',', 0).display;
                    // Update footer by showing the total with the reference of the column index 
                    $(api.column(0).footer()).html('Jumlah');

                    $(api.column(7).footer()).html(numFormat(jumlah));
                },
            });

            $('#objek-modal').modal('show');

            $.validator.setDefaults({
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorElement: 'div ',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if ($(element).is('.select')) {
                        element.next().after(error);
                    } else if (element.hasClass('select2')) {
                        //error.insertAfter(element);
                        error.insertAfter(element.next());
                    } else if (element.hasClass('selectpicker')) {
                        error.insertAfter(element.next());
                    } else {
                        error.insertAfter(element);
                    }
                },


                submitHandler: function(form) {

                    var form = $('#add-objek-form');
                    var status = $("#status").val();
                    // remove the text-danger
                    $(".text-danger").remove();
                    var idPkm = $('#pkm').val();
                    var idProgram = $('#prog').val();
                    var idSub = $('#ruk').val();
                    var vol_total = $('#vol_total').val();
                    var harga_total = $('#harga_total').val();
                    var idBelanja = $('#idBelanja').val();
                    var end = new Date('<?= $timer->waktu ?>');
                    var now = new Date();
                    var distance = end - now;
                    var distance1 = now - end;

                    if (distance1 > 0 && status !== 'Discuss') {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'error',
                            title: 'Maaf, Waktu Habis atau Kegiatan sudah di Approved/Rejected',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    } else {
                        $.ajax({
                            url: '<?php echo base_url('usulanrpk/add') ?>',
                            type: 'post',
                            data: {
                                idPkm: idPkm,
                                idProgram: idProgram,
                                idSub: idSub,
                                vol_total: vol_total,
                                harga_total: harga_total,
                                idBelanja: idBelanja,
                                vol1: $('#vol1').val(),
                                vol2: $('#vol2').val(),
                                vol3: $('#vol3').val(),
                                vol4: $('#vol4').val(),
                                sat1: $('#sat1').val(),
                                sat2: $('#sat2').val(),
                                sat3: $('#sat3').val(),
                                sat4: $('#sat4').val(),
                                keterangan: $('#add-objek-form #ket').val(),
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                $('#objek-form-btn').html(
                                    '<i class="fa fa-spinner fa-spin"></i>');
                            },
                            success: function(response) {

                                if (response.success === true) {

                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'success',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        $('#data_table_modal').DataTable().ajax.reload(
                                                null,
                                                false)
                                            .draw(false);
                                        $('#data_table2').DataTable().ajax.reload(null,
                                                false)
                                            .draw(false);
                                        $('#data_table3').DataTable().ajax.reload(null,
                                                false)
                                            .draw(false);
                                        $("#add-objek-form")[0].reset();
                                        $(".form-control").removeClass('is-invalid')
                                            .removeClass(
                                                'is-valid');
                                    })

                                } else {

                                    if (response.messages instanceof Object) {
                                        $.each(response.messages, function(index, value) {
                                            var id = $("#" + index);

                                            id.closest('.form-control')
                                                .removeClass('is-invalid')
                                                .removeClass('is-valid')
                                                .addClass(value.length > 0 ?
                                                    'is-invalid' :
                                                    'is-valid');

                                            id.after(value);

                                        });
                                    } else {
                                        Swal.fire({
                                            position: 'bottom-end',
                                            icon: 'error',
                                            title: response.messages,
                                            showConfirmButton: false,
                                            timer: 1500
                                        })

                                    }
                                }
                                $('#objek-form-btn').html('Simpan');
                            }
                        });
                    };
                    return false;

                }

            });
            $('#add-objek-form').validate();
        }

        //select pada tambah

        $('#add-form #idMenu').on('select2:select', function(e) {
            rukchange();
        });

        $('#add-objek-form #idBelanja').on('select2:select', function(e) {
            var obj = $(this).val();
            $.ajax({
                type: "post",
                url: "<?php echo base_url($controller . '/getobj') ?>",
                data: {
                    obj: obj
                },
                dataType: "json",
                success: function(response) {
                    if (response.harga) {
                        $('#add-objek-form #harga').val(response.harga);
                        $('#add-objek-form #satuan').val(response.satuan);
                    } else {
                        $('#add-objek-form #harga').val(0);
                        $('#add-objek-form #satuan').val("");
                    }

                }
            });
        });

        $('#vol1, #vol2, #vol3, #vol4').keyup(function() {
            koef();
        });


        function koef() {
            harga = parseInt($('#harga').val())
            vol1 = parseInt($('#vol1').val());
            if ($('#vol2').val() != "") {
                vol2 = parseInt($('#vol2').val());
            } else {
                vol2 = 1;
            };
            if ($('#vol3').val() != "") {
                vol3 = parseInt($('#vol3').val());
            } else {
                vol3 = 1;
            };
            if ($('#vol4').val() != "") {
                vol4 = parseInt($('#vol4').val());
            } else {
                vol4 = 1;
            }
            kali1 = vol1 * vol2 * vol3 * vol4;
            kali2 = harga * kali1;
            $('#vol_total').val(kali1);
            $('#harga_total').val(kali2);
        }

        function add() {

            // reset the form 
            $("#add-form")[0].reset();
            $(".form-control").removeClass('is-invalid').removeClass('is-valid');
            subkom();
            $('#add-modal').modal('show');
            // submit the add from 
            $.validator.setDefaults({
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                },
                errorElement: 'div ',
                errorClass: 'invalid-feedback',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if ($(element).is('.select')) {
                        element.next().after(error);
                    } else if (element.hasClass('select2')) {
                        //error.insertAfter(element);
                        error.insertAfter(element.next());
                    } else if (element.hasClass('selectpicker')) {
                        error.insertAfter(element.next());
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function(form) {
                    var pkm = $('#pkm').val();
                    var form = $('#add-form');
                    // remove the text-danger
                    $(".text-danger").remove();
                    $.ajax({
                        url: '<?php echo base_url($controller . '/add') ?>',
                        type: 'post',
                        data: $('#add-form').serialize() + "&pkm=" + pkm,
                        dataType: 'json',
                        beforeSend: function() {
                            $('#add-form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success: function(response) {

                            if (response.success === true) {

                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'success',
                                    title: response.messages,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    $('#data_table3').DataTable().ajax.reload(null,
                                            false)
                                        .draw(false);
                                    $('#add-modal').modal('hide');
                                })

                            } else {

                                if (response.messages instanceof Object) {
                                    $.each(response.messages, function(index, value) {
                                        var id = $("#" + index);

                                        id.closest('.form-control')
                                            .removeClass('is-invalid')
                                            .removeClass('is-valid')
                                            .addClass(value.length > 0 ? 'is-invalid' :
                                                'is-valid');

                                        id.after(value);

                                    });
                                } else {
                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'error',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })

                                }
                            }
                            $('#add-form-btn').html('Simpan');
                        }
                    });

                    return false;
                }
            });
            $('#add-form').validate();
        }

        function edit(id) {
            $.ajax({
                type: "post",
                url: "<?php echo base_url($controller . '/getOne') ?>",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    $("#status").val(response.status);

                }
            });
            var status = $("#status").val();
            var end = new Date('<?= $timer->waktu ?>');
            var now = new Date();
            var distance = end - now;
            var distance1 = now - end;
            if (distance1 > 0 && status !== 'Discuss') {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    title: 'Maaf, Waktu Habis',
                    showConfirmButton: false,
                    timer: 1500
                })

            } else {

                $.ajax({
                    url: '<?php echo base_url($controller . '/getOne') ?>',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        // reset the form 
                        $("#edit-form")[0].reset();
                        $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                        $('#edit-modal').modal('show');

                        $("#edit-form #id").val(response.id);
                        // $("#edit-form #idMenu").val(response.id_menu);
                        $("#edit-form #tujuan").val(response.tujuan);
                        $("#edit-form #sasaran").val(response.sasaran);
                        $("#edit-form #target").val(response.target);
                        $("#edit-form #tgjawab").val(response.tgjawab);
                        $("#edit-form #sumberdaya").val(response.sumberdaya);
                        $("#edit-form #jadwal").val(response.jadwal);
                        $("#edit-form #lokasi").val(response.lokasi);
                        $("#edit-form #keterangan").val(response.keterangan);

                        $.ajax({
                            type: "post",
                            data: {
                                subkomponen: response.id_menu
                            },
                            url: "<?php echo base_url('subkomponen/getsubkomponen') ?>",
                            dataType: "json",
                            success: function(response) {
                                if (response.data) {
                                    $('#edit-form #idMenu').html(response.data);
                                    $('#edit-form #idMenu').select2({
                                        dropdownParent: $('#edit-form'),
                                        width: "100%",
                                    });
                                }
                            }
                        });

                        // submit the edit from 
                        $.validator.setDefaults({
                            highlight: function(element) {
                                $(element).addClass('is-invalid').removeClass('is-valid');
                            },
                            unhighlight: function(element) {
                                $(element).removeClass('is-invalid').addClass('is-valid');
                            },
                            errorElement: 'div ',
                            errorClass: 'invalid-feedback',
                            errorPlacement: function(error, element) {
                                if (element.parent('.input-group').length) {
                                    error.insertAfter(element.parent());
                                } else if ($(element).is('.select')) {
                                    element.next().after(error);
                                } else if (element.hasClass('select2')) {
                                    //error.insertAfter(element);
                                    error.insertAfter(element.next());
                                } else if (element.hasClass('selectpicker')) {
                                    error.insertAfter(element.next());
                                } else {
                                    error.insertAfter(element);
                                }
                            },

                            submitHandler: function(form) {
                                var form = $('#edit-form');
                                $(".text-danger").remove();
                                $.ajax({
                                    url: '<?php echo base_url($controller . '/edit') ?>',
                                    type: 'post',
                                    data: form.serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        $('#edit-form-btn').html(
                                            '<i class="fa fa-spinner fa-spin"></i>'
                                        );
                                    },
                                    success: function(response) {

                                        if (response.success === true) {

                                            Swal.fire({
                                                position: 'bottom-end',
                                                icon: 'success',
                                                title: response.messages,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(function() {
                                                $('#data_table3')
                                                    .DataTable()
                                                    .ajax
                                                    .reload(null, false)
                                                    .draw(
                                                        false);
                                                $('#edit-modal').modal(
                                                    'hide');
                                            })

                                        } else {

                                            if (response
                                                .messages instanceof Object) {
                                                $.each(response.messages, function(
                                                    index,
                                                    value) {
                                                    var id = $("#" + index);

                                                    id.closest(
                                                            '.form-control')
                                                        .removeClass(
                                                            'is-invalid')
                                                        .removeClass(
                                                            'is-valid')
                                                        .addClass(value
                                                            .length >
                                                            0 ?
                                                            'is-invalid' :
                                                            'is-valid');

                                                    id.after(value);

                                                });
                                            } else {
                                                Swal.fire({
                                                    position: 'bottom-end',
                                                    icon: 'error',
                                                    title: response
                                                        .messages,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                })

                                            }
                                        }
                                        $('#edit-form-btn').html('Simpan');
                                    }
                                });

                                return false;
                            }
                        });
                        $('#edit-form').validate();

                    }
                });
            };
        }

        function edit2(id) {
            $.ajax({
                url: '<?php echo base_url($controller . '/getOne') ?>',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // reset the form 
                    $("#edit2-form")[0].reset();
                    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
                    $('#edit2-modal').modal('show');

                    $("#edit2-form #id").val(response.id);
                    // $("#edit-form #idMenu").val(response.id_menu);
                    $("#edit2-form #status").val(response.status);
                    $("#edit2-form #keterangan").val(response.keterangan);
                    $("#edit2-form #catatan").val(response.catatan);

                    // submit the edit from 
                    $.validator.setDefaults({
                        highlight: function(element) {
                            $(element).addClass('is-invalid').removeClass('is-valid');
                        },
                        unhighlight: function(element) {
                            $(element).removeClass('is-invalid').addClass('is-valid');
                        },
                        errorElement: 'div ',
                        errorClass: 'invalid-feedback',
                        errorPlacement: function(error, element) {
                            if (element.parent('.input-group').length) {
                                error.insertAfter(element.parent());
                            } else if ($(element).is('.select')) {
                                element.next().after(error);
                            } else if (element.hasClass('select2')) {
                                //error.insertAfter(element);
                                error.insertAfter(element.next());
                            } else if (element.hasClass('selectpicker')) {
                                error.insertAfter(element.next());
                            } else {
                                error.insertAfter(element);
                            }
                        },

                        submitHandler: function(form) {
                            var form = $('#edit2-form');
                            $(".text-danger").remove();
                            $.ajax({
                                url: '<?php echo base_url($controller . '/edit2') ?>',
                                type: 'post',
                                data: form.serialize(),
                                dataType: 'json',
                                beforeSend: function() {
                                    $('#edit2-form-btn').html(
                                        '<i class="fa fa-spinner fa-spin"></i>');
                                },
                                success: function(response) {

                                    if (response.success === true) {

                                        Swal.fire({
                                            position: 'bottom-end',
                                            icon: 'success',
                                            title: response.messages,
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(function() {
                                            $('#data_table3').DataTable()
                                                .ajax
                                                .reload(null, false).draw(
                                                    false);
                                            status3();
                                            $('#edit2-modal').modal('hide');
                                        })

                                    } else {

                                        if (response.messages instanceof Object) {
                                            $.each(response.messages, function(
                                                index,
                                                value) {
                                                var id = $("#" + index);

                                                id.closest('.form-control')
                                                    .removeClass(
                                                        'is-invalid')
                                                    .removeClass('is-valid')
                                                    .addClass(value.length >
                                                        0 ?
                                                        'is-invalid' :
                                                        'is-valid');

                                                id.after(value);

                                            });
                                        } else {
                                            Swal.fire({
                                                position: 'bottom-end',
                                                icon: 'error',
                                                title: response.messages,
                                                showConfirmButton: false,
                                                timer: 1500
                                            })

                                        }
                                    }
                                    $('#edit2-form-btn').html('Simpan');
                                }
                            });

                            return false;
                        }
                    });
                    $('#edit2-form').validate();

                }
            });
        }

        function remove(id) {
            var end = new Date('<?= $timer->waktu ?>');
            var now = new Date();
            var distance = end - now;
            var distance1 = now - end;
            if (distance1 > 0) {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    title: 'Maaf, Waktu Habis',
                    showConfirmButton: false,
                    timer: 1500
                })

            } else {
                Swal.fire({
                    title: 'Hapus Rencana Usulan ',
                    text: "Yakin akan dihapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.value) {
                        $.ajax({
                            url: '<?php echo base_url($controller . '/remove') ?>',
                            type: 'post',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {

                                if (response.success === true) {
                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'success',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        $('#data_table3').DataTable().ajax.reload(null,
                                                false)
                                            .draw(
                                                false);
                                        status3();
                                    })
                                } else {
                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'error',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })


                                }
                            }
                        });
                    }
                });
            };
        }

        function del(id) {
            var status = $("#status").val();
            var end = new Date('<?= $timer->waktu ?>');
            var now = new Date();
            var distance = end - now;
            var distance1 = now - end;
            if (distance1 > 0 && status !== 'Discuss') {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    title: 'Maaf, Waktu Habis atau Kegiatan sudah di Approved/Rejected',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    title: 'Hapus Objek belanja',
                    text: "Yakin akan dihapus?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {

                    if (result.value) {
                        $.ajax({
                            url: '<?php echo base_url('usulanrpk/remove') ?>',
                            type: 'post',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {

                                if (response.success === true) {
                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'success',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        $('#data_table_modal').DataTable().ajax.reload(null,
                                                false)
                                            .draw(
                                                false);
                                        $('#data_table2').DataTable().ajax.reload(null,
                                                false)
                                            .draw(false);
                                        $('#data_table3').DataTable().ajax.reload(null,
                                                false)
                                            .draw(false);
                                        $("#add-objek-form")[0].reset();
                                        $(".form-control").removeClass('is-invalid')
                                            .removeClass(
                                                'is-valid');
                                    })
                                } else {
                                    Swal.fire({
                                        position: 'bottom-end',
                                        icon: 'error',
                                        title: response.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })


                                }
                            }
                        });
                    }
                });
            };
        }
        </script>

        <?= $this->endSection() ?>