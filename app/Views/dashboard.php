<?= $this->extend("layout/main") ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.bootstrap5.min.css">
<title>ePlanning - Dashboard</title>
<?= $this->endSection() ?>

<?= $this->section('judul') ?>

<div class="container-fluid">
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row">
                    <div class="col">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <!--end col-->
                    <div class="col-auto align-self-center">

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
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="ribbon3 rib3-warning">
                            <span class="text-white text-center rib3-warning">ePlanning</span>
                        </div>
                        <!--end ribbon-->
                        <div class="row">
                            <div class="col-auto">
                                <img src="<?= base_url() ?>/assets/images/dashboard.png" alt="user" height="150"
                                    class="align-self-center mb-3 mb-lg-0">
                            </div>
                            <!--end col-->
                            <div class="col align-self-center">
                                <p class="font-18 fw-semibold mb-2">New Planning for Public Health Cluster</p>
                                <p class="text-muted">Adalah aplikasi yang dikembangkan dalam mendukung Go Digital pada
                                    tahapan Perencanaan,
                                    Penganggaran dan Pelaporan Pelayanan Kesehatan Masyarakat.
                                </p>
                                <div class="avatar-box thumb-xxl align-self-center me-2">
                                    <a href="<?= base_url('ruk') ?>" class="avatar-title bg-primary rounded">RUK</a>
                                </div>
                                <div class="avatar-box thumb-xxl align-self-center me-2">
                                    <a href="<?= base_url('rpk') ?>" class="avatar-title bg-warning rounded">RPK</a>
                                </div>
                                <div class="avatar-box thumb-xxl align-self-center me-2">
                                    <a href="<?= base_url('rpkperubahan') ?>" class="avatar-title bg-danger rounded"
                                        style="font-size: 22px;">RPKP</a>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0" id="exampleModalCenterTitle">Billing</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!--end modal-header-->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-3 text-center align-self-center">
                                <img src="<?= base_url() ?>assets/images/widgets/btc.png" alt="" class="img-fluid">
                            </div>
                            <!--end col-->
                            <div class="col-lg-9">
                                <h5>Billing Cycle Aplikasi ePlanning</h5>
                                <p>Yth. Para User ePlanning Puskesmas Kanupaten Garut.
                                    kami sampaikan permohonan maaf bahwa Mulai Tanggal 21 Agustus 2022 aplikasi ini akan
                                    menerapkan mode Pembayaran.
                                    Hal ini berkaitan dengan penggunaan server dan tenaga developer yang berasal dari
                                    luar institusi Dinas Kesehatan. Pembiayaan dilakukan annually tahunan sebesar Rp.
                                    2.000.000.
                                    Kami sampaikan permakluman maaf atas hal tersebut.
                                </p>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end modal-body-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft-primary btn-sm">Save changes</button>
                        <button type="button" class="btn btn-soft-secondary btn-sm"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                    <!--end modal-footer-->
                </div>
                <!--end modal-content-->
            </div>
            <!--end modal-dialog-->
        </div>
        <!--end modal-->

        <?= $this->endSection() ?>
        <!-- /.content -->


        <!-- page script -->
        <?= $this->section("js") ?>


        <script>
        $(document).ready(function() {
            // $('#exampleModalCenter').modal('show');
        });
        </script>

        <?= $this->endSection() ?>