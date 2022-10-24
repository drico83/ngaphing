<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\RpkModel;
use App\Models\ProgramModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\UsulanRpkModel;
use App\Models\PaguModel;
use App\Models\PengaturanuserModel;
use App\Models\Mcountdown;

class Rpk extends BaseController
{

    protected $rukModel;
    protected $rpkModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $usulanRpkModel;
    protected $pengaturanuserModel;
    protected $paguModel;
    protected $mcountdown;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->rukModel = new RukModel();
        $this->rpkModel = new RpkModel();
        $this->programModel = new ProgramModel();
        $this->penyusunModel = new PenyusunModel();
        $this->kodebelanjaModel = new KodebelanjaModel();
        $this->uptModel = new UptModel();
        $this->usulanModel = new UsulanModel();
        $this->usulanRpkModel = new UsulanRpkModel();
        $this->paguModel = new PaguModel();
        $this->pengaturanuserModel = new PengaturanuserModel();
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where(['link' => 'rpk', 'tahun' => $tahun])->first();
        $data = [
            'controller'        => 'rpk',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if ($timer) {
            $datetime = date('Y-m-d H:i');;
            if ($datetime < $timer->waktu) {
                $data['tombol'] = '<button class="btn btn-info me-1" onclick="add()" title="Add"><i class="fa fa-business-time fa-w-20"></i>Tambah RPK</button>';
            } else {
                $data['tombol'] = '';
            }
        }

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            if ($tahun != 2023) {
                return view('rpk/rpk', $data);
            } else {
                return view('rpk/rpk2023', $data);
            }
        }
    }

    public function getAll()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $response = array();

        $data['data'] = array();
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $result = $this->rpkModel->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab,rpk.jadwal, rpk.lokasi, rpk.status, rpk.catatan, rpk.waktu, rpk.user_id, sub_komponen.nama_subkomponen, sub_komponen.id_prog, b.harga_total')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu', 'left')
            ->where(['rpk.id_pkm' => $pkm, 'rpk.tahun' => $tahun])
            ->findAll();
        $no = 1;
        $keyy = 0;
        foreach ($result as $key => $value) {
            $ops2 = "";
            if ($value->status == "Approve") {
                $status = "success";
            } elseif ($value->status == "Discuss") {
                $status = "warning";
            } else {
                $status = "danger";
            };
            if ($value->status != "") {
                $ops2 =  '<button type="button" class="btn btn-' . $status . '" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $value->catatan . '">
                ' . $value->status . '</button>';
            };

            $ops = '<div class="text-center">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i>Edit</button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i>Hapus</button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-success" onclick="belanja(' . $value->id . ',' . $value->id_menu . ')"><i class="fa fa-search"></i>Objek Belanja</button>';
            if (in_groups('admin')) {

                $ops .= '	<button type="button" class="btn btn-sm btn-warning" onclick="edit2(' . $value->id . ')"><i class="fa fa-check"></i>Approval</button>';
            };
            $ops .= '</div>';

            $program = explode(',', $value->id_prog);
            // var_dump($program);
            // die;
            if (in_array($prog, $program)) {

                if ($value->user_id != 0) {
                    $user = $this->pengaturanuserModel->where('id', $value->user_id)->first();
                    $nama = $user->name;
                } else {
                    $nama = 'noname';
                };
                $data['data'][$keyy] = array(
                    $no++,
                    $value->nama_subkomponen . '<span class="badge bg-secondary">diinput oleh ' . $nama . '</span>',
                    $value->keterangan,
                    $value->tujuan,
                    $value->sasaran,
                    $value->target,
                    $value->tgjawab,
                    $value->jadwal,
                    $value->lokasi,
                    $value->harga_total,
                    $ops2,
                    $ops,
                );
                $keyy++;
            };
        }

        return $this->response->setJSON($data);
    }

    public function dt()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $dt = new Datatables();
        $dt->table('ruk');
        // $objek = "(SELECT usul_belanja_rpk.id_sub, sum(harga_total) as harga_total from usul_belanja WHERE usul_belanja_rpk.id_pkm=" . $pkm . " GROUP BY usul_belanja_rpk.id_sub) as usul";
        $dt->select('ruk.*, sub_komponen.nama_subkomponen, sum("usul_belanja_rpk.harga_total")');
        $dt->join('sub_komponen', 'sub_komponen.id=ruk.id_menu');
        $dt->join('usul_belanja', 'usul_belanja_rpk.id_sub=ruk.id');
        // $dt->GroupBy('usul_belanja_rpk.id_sub');
        $dt->where(['ruk.id_pkm' => $pkm]);
        $dt->where(['sub_komponen.id_prog' => $prog]);
        return $dt->addColumn('action', function ($db) {
            $id = $db['id'];
            $idmenu = $db['id_menu'];
            $btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> 
            <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>
            <button class='btn btn-sm btn-info' onclick='belanja(" . $id . "," . $idmenu . ")' title='objek belanja'><i class='fa fa-search'></i>Objek Belanja</button>";
            return $btn;
        })->draw();
    }

    public function getpuskesmas()
    {
        $tahun = $this->session->get('db_tahun');
        $data['data'] = array();
        if ($tahun != 2023) {
            $join = "(SELECT usul_belanja_rpk.id_pkm, rpk.id_menu, 
            SUM(CASE WHEN rpk.id_menu <= 131 AND usul_belanja_rpk.tahun=" . $tahun . " THEN usul_belanja_rpk.harga_total ELSE 0 END) AS ukm, 
            SUM(CASE WHEN rpk.id_menu <= 139 AND usul_belanja_rpk.tahun=" . $tahun . " THEN usul_belanja_rpk.harga_total else 0 END) AS harga_total
            FROM usul_belanja_rpk JOIN rpk ON rpk.id = usul_belanja_rpk.id_sub GROUP BY usul_belanja_rpk.id_pkm) as b";
            $join2 = "(SELECT rpk.id_pkm as id_pkm, 
            COUNT(IF(rpk.status = 'Approve' And rpk.tahun=" . $tahun . ", 1, NULL)) 'Approve',
            COUNT(IF(rpk.status = 'Reject' And rpk.tahun=" . $tahun . ", 1, NULL)) 'Reject',
            COUNT(IF(rpk.tahun=" . $tahun . ", 1, NULL)) 'total' FROM rpk Group By rpk.id_pkm) as c";
            $pkm = user()->puskesmas;
            if (in_groups('admin') || in_groups('programmer')) {
                $result = $this->uptModel->select('upt.*, pagu.anggaran, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm, c.Approve, c.Reject, c.total')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->join('pagu', 'pagu.id_pkm=upt.id', 'Left')
                    ->join($join2, 'c.id_pkm=upt.id', 'Left')
                    ->orderBy('upt.id', 'ASC')
                    ->findAll();
            } else {
                $result = $this->uptModel->select('upt.*, pagu.anggaran, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm, c.Approve, c.Reject, c.total')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->join('pagu', 'pagu.id_pkm=upt.id', 'Left')
                    ->join($join2, 'c.id_pkm=upt.id')
                    ->where(['upt.id' =>  $pkm])
                    ->orderBy('upt.id', 'ASC')
                    ->findAll();
            };
            $response = array();
            $no = 1;

            foreach ($result as $key => $value) {
                $hasil = 0;
                if ($value->Approve != 0) {
                    $hasil = intval(($value->Approve / $value->total) * 100);
                };
                $ops2 = "";
                if ($hasil == "100") {
                    $status = "success";
                } elseif ($hasil == "0") {
                    $status = "danger";
                } else {
                    $status = "warning";
                };

                $ops2 =  '<button type="button" class="btn btn-' . $status . ' text-center">
                    ' . $hasil . '</button>';

                // $ops2 = $hasil;
                $ops = '<div class="text-center">';
                $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="program(' . $value->id . ')"><i class="fa fa-edit"></i>Lihat Detail</button>';
                $ops .= '</div>';


                if ($value->ukm == 0) {
                    $pukm = '';
                    $pcovid = '';
                } else {
                    $pukm = round(($value->ukm / $value->harga_total) * 100);
                    $pcovid = 100 - $pukm;
                };

                $data['data'][$key] = array(
                    $no++,
                    $value->pkm,
                    $value->anggaran,
                    $value->ukm,
                    $pukm,
                    $value->harga_total - $value->ukm,
                    $pcovid,
                    $value->harga_total,
                    $ops2,
                    $ops,
                );
            }
        } else {
            $join = "(SELECT usul_belanja_rpk.id_pkm, rpk.id_menu, 
            SUM(CASE WHEN rpk.id_menu < 310 AND usul_belanja_rpk.tahun=" . $tahun . " THEN usul_belanja_rpk.harga_total ELSE 0 END) AS blud, 
            SUM(CASE WHEN rpk.id_menu <= 359 AND rpk.id_menu >= 310 AND usul_belanja_rpk.tahun=" . $tahun . " THEN usul_belanja_rpk.harga_total ELSE 0 END) AS ukm, 
            SUM(CASE WHEN rpk.id_menu = 360 AND usul_belanja_rpk.tahun=" . $tahun . " THEN usul_belanja_rpk.harga_total else 0 END) AS insentif,
            SUM(CASE WHEN rpk.id_menu <= 363 AND rpk.id_menu >= 361 AND usul_belanja_rpk.tahun=" . $tahun . " THEN usul_belanja_rpk.harga_total ELSE 0 END) AS manajemen 
            FROM usul_belanja_rpk JOIN rpk ON rpk.id = usul_belanja_rpk.id_sub GROUP BY usul_belanja_rpk.id_pkm) as b";
            $join2 = "(SELECT rpk.id_pkm as id_pkm, 
            COUNT(IF(rpk.status = 'Approve' And rpk.tahun=" . $tahun . ", 1, NULL)) 'Approve',
            COUNT(IF(rpk.status = 'Reject' And rpk.tahun=" . $tahun . ", 1, NULL)) 'Reject',
            COUNT(IF(rpk.tahun=" . $tahun . ", 1, NULL)) 'total' FROM rpk Group By rpk.id_pkm) as c";
            $pkm = user()->puskesmas;
            if (in_groups('admin') || in_groups('programmer')) {
                $result = $this->uptModel->select('upt.*, pagu2023.pagu_ukm, pagu2023.pagu_insentif, pagu2023.pagu_manajemen, COALESCE(b.blud, 0) as blud, COALESCE(b.ukm,0) as ukm,  COALESCE(b.insentif,0) as insentif,  COALESCE(b.manajemen,0) as manajemen, c.Approve, c.Reject, c.total')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->join('pagu2023', 'pagu2023.id_pkm=upt.id', 'Left')
                    ->join($join2, 'c.id_pkm=upt.id', 'Left')
                    ->orderBy('upt.id', 'ASC')
                    ->findAll();
            } else {
                $result = $this->uptModel->select('upt.*, pagu2023.pagu_ukm, pagu2023.pagu_insentif, pagu2023.pagu_manajemen, COALESCE(b.blud, 0) as blud, COALESCE(b.ukm,0) as ukm,  COALESCE(b.insentif,0) as insentif,  COALESCE(b.manajemen,0) as manajemen, c.Approve, c.Reject, c.total')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->join('pagu2023', 'pagu2023.id_pkm=upt.id', 'Left')
                    ->join($join2, 'c.id_pkm=upt.id')
                    ->where(['upt.id' =>  $pkm])
                    ->orderBy('upt.id', 'ASC')
                    ->findAll();
            };
            $response = array();
            $no = 1;

            foreach ($result as $key => $value) {
                $hasil = 0;
                if ($value->Approve != 0) {
                    $hasil = intval(($value->Approve / $value->total) * 100);
                };
                $ops2 = "";
                if ($hasil == "100") {
                    $status = "success";
                } elseif ($hasil == "0") {
                    $status = "danger";
                } else {
                    $status = "warning";
                };

                $ops2 =  '<button type="button" class="btn btn-' . $status . ' text-center">
                    ' . $hasil . '</button>';

                // $ops2 = $hasil;
                $ops = '<div class="text-center">';
                $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="program(' . $value->id . ')"><i class="fa fa-edit"></i>Lihat Detail</button>';
                $ops .= '</div>';


                if ($value->ukm == 0) {
                    $pukm = '';
                    $pcovid = '';
                } else {
                    $pukm = round(($value->ukm / $value->total) * 100);
                    $pcovid = 100 - $pukm;
                };



                $data['data'][$key] = array(
                    $no++,
                    $value->pkm,
                    $value->blud,
                    $value->pagu_ukm,
                    $value->ukm,
                    $value->pagu_insentif,
                    $value->insentif,
                    $value->pagu_manajemen,
                    $value->manajemen,
                    $value->ukm + $value->insentif + $value->manajemen,
                    $ops2,
                    $ops,
                );
            }
        }

        return $this->response->setJSON($data);
    }

    public function getbanner()
    {
        $sum = array();
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) AS harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $sum = $this->rpkModel->select('SUM( IF( rpk.status = "Discuss", b.harga_total, 0) ) AS discuss1, SUM( IF( rpk.status = "Approve", b.harga_total, 0) ) AS approve1, 
        SUM(b.harga_total) AS total1,
        SUM( IF( rpk.status = "Reject", b.harga_total, 0) ) AS reject1, SUM( IF( rpk.status = "", b.harga_total, 0) ) AS disclaim1')->join($join, 'b.id_sub=rpk.id')->first();
        $count = $this->rpkModel->select('count( IF( rpk.status = "Discuss", 1, NULL) ) AS cdiscuss1, count( IF( rpk.status = "Approve", 1, NULL) ) AS capprove1, 
        count(rpk.status) AS ctotal2,
        count( IF( rpk.status = "Reject", 1, NULL) ) AS creject1, SUM( IF( rpk.status = "",1, NULL) ) AS cdisclaim1')->first();
        $pagu = $this->paguModel->select('sum(pagu.anggaran) as anggaran')->first();
        $msg = [
            'data' => $sum,
            'count' => $count,
            'pagu' => $pagu
        ];

        return $this->response->setJSON($msg);
    }


    public function getbanner2()
    {
        $pkm = $this->request->getPost('pkm');
        $sum = array();
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) AS harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $sum = $this->rpkModel->select('COALESCE(SUM( IF( rpk.status = "Discuss", b.harga_total, 0)),0) AS discuss2, COALESCE(SUM( IF( rpk.status = "Approve", b.harga_total, 0)),0) AS approve2, 
        COALESCE(SUM(b.harga_total),0) AS total2,
        COALESCE(SUM( IF( rpk.status = "Reject", b.harga_total, 0)),0) AS reject2, COALESCE(SUM( IF( rpk.status = "", b.harga_total, 0)),0) AS disclaim2')->join($join, 'b.id_sub=rpk.id')->where('id_pkm', $pkm)->first();
        $count = $this->rpkModel->select('COALESCE(count( IF( rpk.status = "Discuss", 1, NULL)),0) AS cdiscuss2, COALESCE(count( IF( rpk.status = "Approve", 1, NULL)),0) AS capprove2, 
        COALESCE(count(rpk.status),0) AS ctotal1,
        COALESCE(count( IF( rpk.status = "Reject", 1, NULL)),0) AS creject2, COALESCE(SUM( IF( rpk.status = "",1, NULL)),0) AS cdisclaim2')->where('id_pkm', $pkm)->first();
        $pagu = $this->paguModel->select('sum(pagu.anggaran) as anggaran')->where('id_pkm', $pkm)->first();
        $msg = [
            'data' => $sum,
            'count' => $count,
            'pagu' => $pagu
        ];

        return $this->response->setJSON($msg);
    }

    public function getbanner3()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $sum = array();
        $join = "(SELECT usul_belanja_rpk.id_sub, SUM(usul_belanja_rpk.harga_total) AS harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $sum = $this->rpkModel->select('SUM( IF( rpk.status = "Discuss", b.harga_total, 0) ) AS discuss3, SUM( IF( rpk.status = "Approve", b.harga_total, 0) ) AS approve3, 
        SUM(b.harga_total) AS total3,
        SUM( IF( rpk.status = "Reject", b.harga_total, 0) ) AS reject3, SUM( IF( rpk.status = "", b.harga_total, 0) ) AS disclaim3')->join($join, 'b.id_sub=rpk.id')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['rpk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog])->first();
        $count = $this->rpkModel->select('count( IF( rpk.status = "Discuss", 1, 0) ) AS cdiscuss3, count( IF( rpk.status = "Approve", 1, 0) ) AS capprove3, 
        count(rpk.status) AS ctotal3,
        count( IF( rpk.status = "Reject", 1, 0) ) AS creject3, SUM( IF( rpk.status = "",1, 0) ) AS cdisclaim3')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu', 'left')->where(['rpk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog])->first();
        $msg = [
            'data' => $sum,
            'count' => $count
        ];

        return $this->response->setJSON($msg);
    }

    public function getprogram()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $response = array();

        $data['data'] = array();
        $join = "(SELECT usul_belanja_rpk.id_program, rpk.id_menu,
        SUM( IF( rpk.id_menu <= 363 and usul_belanja_rpk.tahun=" . $tahun . ", usul_belanja_rpk.harga_total, 0) ) AS harga_total
        FROM usul_belanja_rpk JOIN rpk ON rpk.id = usul_belanja_rpk.id_sub WHERE usul_belanja_rpk.id_pkm=" . $pkm . " GROUP BY usul_belanja_rpk.id_program) as b";
        $result = $this->programModel->select('program.*, COALESCE(b.harga_total, 0) as harga_total')
            ->join($join, 'b.id_program=program.id', 'Left')
            ->findAll();
        $no = 1;
        foreach ($result as $key => $value) {

            $ops = '<div class="text-center">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="usulan(' . $value->id . ')"><i class="fa fa-edit"></i>Lihat Detail</button>';
            $ops .= '</div>';

            $data['data'][$key] = array(
                $no++,
                $value->nama_program,
                $value->harga_total,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getobjek()
    {
        $objek = $this->request->getPost('objek');
        if ($objek) {
            $datarka = $this->penyusunModel->select('id_kode_belanja, kode_belanja.nama_belanja')
                ->join('kode_belanja', 'kode_belanja.id=penyusun.id_kode_belanja')
                ->where('id_subkomponen', $objek)->findAll();
            $isidata = "<option value=''>-Pilih Objek Belanja-</option>";
        } else {
            $datarka = $this->penyusunModel->select('id, id_subkomponen')->findAll();
            $isidata = "<option value=''>--Pilih Objek Belanja--</option>";
        }

        $data['data'] = array();

        foreach ($datarka as $key => $value) {
            $isidata .= '<option value="' . $value->id_kode_belanja . '">' . $value->nama_belanja . '</option>';
        };

        $msg = [
            'data' => $isidata
        ];
        return $this->response->setJSON($msg);
    }

    public function getobj()
    {
        $response = array();

        $obj = $this->request->getPost('obj');

        if ($this->validation->check($obj, 'required|numeric')) {

            $data = $this->kodebelanjaModel->where('id', $obj)->first();
            // if ($data = "") {
            //     $data = [
            //         "harga" => 0,
            //         "satuan" => ""
            //     ];
            // }
            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function getOne()
    {
        $response = array();

        $id = $this->request->getPost('id');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->rpkModel->where('id', $id)->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function gethiji()
    {
        $response = array();

        $id = 12;

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->rpkModel->where('id', $id)->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function getprog()
    {
        $response = array();

        $prog = $this->request->getPost('prog');

        if ($this->validation->check($prog, 'required|numeric')) {

            $data = $this->programModel->where('id', $prog)->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {
        $tahun = $this->session->get('db_tahun');
        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_menu'] = $this->request->getPost('idMenu');
        $fields['tujuan'] = $this->request->getPost('tujuan');
        $fields['sasaran'] = $this->request->getPost('sasaran');
        $fields['target'] = $this->request->getPost('target');
        $fields['tgjawab'] = $this->request->getPost('tgjawab');
        $fields['jadwal'] = $this->request->getPost('jadwal');
        $fields['lokasi'] = $this->request->getPost('lokasi');
        $fields['id_pkm'] = $this->request->getPost('pkm');
        $fields['id_ruk'] = $this->request->getPost('id_ruk');
        $fields['user_id'] = user_id();
        $fields['tahun'] = $tahun;
        $id_ruk = $this->request->getPost('id_ruk');
        $fields['keterangan'] = $this->request->getPost('keterangan');
        $id_menu = $this->request->getPost('idMenu');
        $id_pkm = $this->request->getPost('pkm');

        $this->validation->setRules([
            'id_menu' => ['label' => 'Kegiatan', 'rules' => 'required|max_length[11]'],
            'tujuan' => ['label' => 'Tujuan', 'rules' => 'required|max_length[255]'],
            'sasaran' => ['label' => 'Sasaran', 'rules' => 'required|max_length[255]'],
            'target' => ['label' => 'Target Sasaran', 'rules' => 'required|max_length[255]'],
            'tgjawab' => ['label' => 'Penanggungjawab', 'rules' => 'required|max_length[255]'],
            'jadwal' => ['label' => 'jadwal Pelaksanaan', 'rules' => 'required'],
            'lokasi' => ['label' => 'Lokasi Pelaksanaan', 'rules' => 'required'],
            'id_ruk' => ['label' => 'id RUK', 'rules' => 'required'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            $data = $this->rpkModel->where(['id_menu' => $id_menu, 'id_pkm' => $id_pkm])->first();

            if ($data) {
                $response['success'] = false;
                $response['messages'] = 'Maaf, Kegiatan yang anda masukan sudah ada, silahkan tambahkan pada rincian objek';
            } else {

                if ($this->rpkModel->insert($fields)) {
                    $data = $this->rpkModel->asArray()->where(['id_menu' => $id_menu, 'id_pkm' => $id_pkm])->first();
                    $rpk = $data['id'];
                    $rincibelanja = $this->usulanModel->where(['id_sub' => $id_ruk, 'id_pkm' => $id_pkm])->findall();
                    if ($rincibelanja) {
                        foreach ($rincibelanja as $key => $value) {
                            $this->usulanRpkModel->insert([
                                'id_pkm' => $value->id_pkm,
                                'tahun' => $tahun,
                                'id_program' => $value->id_program,
                                'id_sub' => $data['id'],
                                'id_belanja' => $value->id_belanja,
                                'keterangan' => $value->keterangan,
                                'vol1' => $value->vol1,
                                'sat1' => $value->sat1,
                                'vol2' => $value->vol2,
                                'sat2' => $value->sat2,
                                'vol3' => $value->vol3,
                                'sat3' => $value->sat3,
                                'vol4' => $value->vol4,
                                'sat4' => $value->sat4,
                                'vol_total' => $value->vol_total,
                                'harga_total' => $value->harga_total,
                            ]);
                        }
                    }
                    $response['success'] = true;
                    $response['messages'] = 'Tambah RPK Berhasil' . $rpk;
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Tambah RPK Gagal!';
                };
            };
        };

        return $this->response->setJSON($response);
    }

    public function edit()
    {

        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_menu'] = $this->request->getPost('idMenu');
        $fields['tujuan'] = $this->request->getPost('tujuan');
        $fields['sasaran'] = $this->request->getPost('sasaran');
        $fields['target'] = $this->request->getPost('target');
        $fields['tgjawab'] = $this->request->getPost('tgjawab');
        $fields['jadwal'] = $this->request->getPost('jadwal');
        $fields['lokasi'] = $this->request->getPost('lokasi');

        $fields['keterangan'] = $this->request->getPost('keterangan');


        $this->validation->setRules([
            'id_menu' => ['label' => 'Kegiatan', 'rules' => 'required|max_length[11]'],
            'tujuan' => ['label' => 'Tujuan', 'rules' => 'required|max_length[255]'],
            'sasaran' => ['label' => 'Sasaran', 'rules' => 'required|max_length[255]'],
            'target' => ['label' => 'Target Sasaran', 'rules' => 'required|max_length[255]'],
            'tgjawab' => ['label' => 'Penanggungjawab', 'rules' => 'required|max_length[255]'],
            'jadwal' => ['label' => 'jadwal', 'rules' => 'required'],
            'lokasi' => ['label' => 'lokasi', 'rules' => 'required'],


        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->rpkModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Ubah RPK Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Ubah RPK Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function edit2()
    {

        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['status'] = $this->request->getPost('status');
        $fields['catatan'] = $this->request->getPost('catatan');


        $this->validation->setRules([
            'status' => ['label' => 'Status', 'rules' => 'required'],
            'catatan' => ['label' => 'Catatan', 'rules' => 'required'],
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->rpkModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Ubah Status RPK Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Ubah Status RPK Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('id');
        $data = $this->usulanRpkModel->where('id_sub', $id)->first();

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($data) {
                $response['success'] = false;
                $response['messages'] = 'Hapus RPK Gagal, Data belanja masih ada!';
            } else {
                if ($this->rpkModel->where('id', $id)->delete()) {

                    $response['success'] = true;
                    $response['messages'] = 'Hapus RPK Berhasil';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Hapus RPK Gagal!';
                }
            }
        }

        return $this->response->setJSON($response);
    }
}