<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use Kusmantopratama\Ci4datatables\Datatables;
use App\Models\RukModel;
use App\Models\ProgramModel;
use App\Models\PenyusunModel;
use App\Models\KodebelanjaModel;
use App\Models\PengaturanuserModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\Mcountdown;

class Ruk extends BaseController
{

    protected $rukModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $pengaturanuserModel;
    protected $mcountdown;
    protected $validation;
    protected $session;

    public function __construct()
    {
        $this->rukModel = new RukModel();
        $this->programModel = new ProgramModel();
        $this->penyusunModel = new PenyusunModel();
        $this->kodebelanjaModel = new KodebelanjaModel();
        $this->uptModel = new UptModel();
        $this->usulanModel = new UsulanModel();
        $this->pengaturanuserModel = new PengaturanuserModel();
        $this->mcountdown = new Mcountdown();
        $this->session = session();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where(['link' => 'ruk', 'tahun' => $tahun])->first();
        $data = [
            'controller'        => 'ruk',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {

            $data['tahun'] = $tahun;
            if ($tahun != 2023) {
                return view('ruk/ruk2022', $data);
            } else {
                return view('ruk/ruk', $data);
            }
        }
    }


    public function getruk()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $tahun = $this->session->get('db_tahun');

        $datarka = $this->rukModel->select('sub_komponen.id, sub_komponen.nama_subkomponen, sub_komponen.id_prog')->join('sub_komponen', 'sub_komponen.id=ruk.id_menu')->where(['ruk.id_pkm' => $pkm, 'ruk.tahun' => $tahun])->findAll();
        $isidata = "<option>--Pilih RUK--</option>";
        $data['data'] = array();
        $i = 1;
        foreach ($datarka as $key => $value) {
            $program = explode(',', $value->id_prog);
            if (in_array($prog, $program)) {
                $isidata .= '	<option value="' . $value->id . '">' . $value->nama_subkomponen . '</option>';
            };
        };

        $msg = [
            'data' => $isidata
        ];
        return $this->response->setJSON($msg);
    }



    public function getAll()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $tahun = $this->session->get('db_tahun');

        $response = array();

        $data['data'] = array();
        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) as harga_total FROM usul_belanja GROUP BY usul_belanja.id_sub) as b";
        $result = $this->rukModel->select('ruk.id, ruk.id_menu, ruk.keterangan, ruk.tujuan, ruk.sasaran, ruk.target, ruk.tgjawab, ruk.sumberdaya, ruk.mitra, ruk.status, ruk.catatan, ruk.waktu, ruk.user_id, ruk.indikator, sub_komponen.nama_subkomponen, sub_komponen.id_prog, b.harga_total')
            ->join($join, 'b.id_sub=ruk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=ruk.id_menu', 'left')
            ->where(['ruk.id_pkm' => $pkm, 'ruk.tahun' => $tahun])
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
                $ops2 = '<button class="btn btn-' . $status . ' tippy-btn" title="' . $value->catatan . '" data-tippy-animation="scale" data-tippy-arrow="true">' . $value->status . '</button>';
            };

            $ops = '<div class="text-center">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i>Edit</button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i>Hapus</button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-success" onclick="belanja(' . $value->id . ',' . $value->id_menu . ')"><i class="fa fa-search"></i>Objek Belanja</button>';
            if (in_groups('admin') || in_groups('programmer')) {

                $ops .= '	<button type="button" class="btn btn-sm btn-warning" onclick="edit2(' . $value->id . ')"><i class="fa fa-check"></i>Approval</button>';
            };
            $ops .= '</div>';

            $program = explode(',', $value->id_prog);
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
                    $value->sumberdaya,
                    $value->mitra,
                    $value->waktu,
                    $value->harga_total,
                    $value->indikator,
                    $ops2,
                    $ops,
                );
                $keyy++;
            };
        };

        return $this->response->setJSON($data);
    }

    public function dt()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $tahun = $this->session->get('db_tahun');

        $dt = new Datatables();
        $dt->table('ruk');
        // $objek = "(SELECT usul_belanja.id_sub, sum(harga_total) as harga_total from usul_belanja WHERE usul_belanja.id_pkm=" . $pkm . " GROUP BY usul_belanja.id_sub) as usul";
        $dt->select('ruk.*, sub_komponen.nama_subkomponen, sum("usul_belanja.harga_total")');
        $dt->join('sub_komponen', 'sub_komponen.id=ruk.id_menu');
        $dt->join('usul_belanja', 'usul_belanja.id_sub=ruk.id');
        // $dt->GroupBy('usul_belanja.id_sub');
        $dt->where(['ruk.id_pkm' => $pkm]);
        $dt->where(['sub_komponen.id_prog' => $prog]);
        $dt->where(['ruk.tahun' => $tahun]);
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
            $join = "(SELECT usul_belanja.id_pkm, ruk.id_menu, SUM( IF( ruk.id_menu <= 131, usul_belanja.harga_total, 0) ) AS ukm, 
            SUM( IF( ruk.id_menu <= 139, usul_belanja.harga_total, 0) ) AS harga_total
            FROM usul_belanja JOIN ruk ON ruk.id = usul_belanja.id_sub WHERE usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_pkm) as b";
            $pkm = user()->puskesmas;
            if (in_groups('admin') || in_groups('programmer')) {
                $result = $this->uptModel->select('upt.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->findAll();
            } else {
                $result = $this->uptModel->select('upt.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->where(['upt.id' =>  $pkm])
                    ->findAll();
            };
            $response = array();
            $no = 1;

            foreach ($result as $key => $value) {

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
                    $value->ukm,
                    $pukm,
                    $value->harga_total - $value->ukm,
                    $pcovid,
                    $value->harga_total,
                    $ops,
                );
            }
        } else {

            $join = "(SELECT usul_belanja.id_pkm, ruk.id_menu, 
            SUM( IF(ruk.id_menu < 310, usul_belanja.harga_total, 0) ) AS blud,
            SUM( IF( ruk.id_menu <= 359 and ruk.id_menu >= 310, usul_belanja.harga_total, 0) ) AS ukm, 
            SUM( IF( ruk.id_menu = 360, usul_belanja.harga_total, 0) ) AS insentif, SUM( IF( ruk.id_menu >= 361 and ruk.id_menu <= 363, usul_belanja.harga_total, 0) ) AS manajemen 
            FROM usul_belanja JOIN ruk ON ruk.id = usul_belanja.id_sub WHERE usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_pkm) as b";
            $pkm = user()->puskesmas;
            if (in_groups('admin') || in_groups('programmer')) {
                $result = $this->uptModel->select('upt.*, COALESCE(b.insentif, 0) as insentif, COALESCE(b.blud, 0) as blud, COALESCE(b.manajemen, 0) as manajemen, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->findAll();
            } else {
                $result = $this->uptModel->select('upt.*, COALESCE(b.insentif, 0) as insentif, COALESCE(b.blud, 0) as blud, COALESCE(b.manajemen, 0) as manajemen, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_pkm=upt.id', 'Left')
                    ->where(['upt.id' =>  $pkm])
                    ->findAll();
            };
            $response = array();
            $no = 1;

            foreach ($result as $key => $value) {

                $ops = '<div class="text-center">';
                $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="program(' . $value->id . ')"><i class="fa fa-edit"></i>Lihat Detail</button>';
                $ops .= '</div>';



                $data['data'][$key] = array(
                    $no++,
                    $value->pkm,
                    $value->blud,
                    $value->ukm,
                    $value->insentif,
                    $value->manajemen,
                    $value->ukm + $value->insentif + $value->manajemen,
                    $ops,
                );
            }
        }
        return $this->response->setJSON($data);
    }

    public function getbanner()
    {
        $sum = array();
        $tahun = $this->session->get('db_tahun');

        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) AS harga_total FROM usul_belanja WHERE usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_sub) as b";
        $sum = $this->rukModel->select('SUM( IF( ruk.status = "Discuss", b.harga_total, 0) ) AS discuss1, SUM( IF( ruk.status = "Approve", b.harga_total, 0) ) AS approve1, 
        SUM(b.harga_total) AS total1,
        SUM( IF( ruk.status = "Reject", b.harga_total, 0) ) AS reject1, SUM( IF( ruk.status = "", b.harga_total, 0) ) AS disclaim1')->join($join, 'b.id_sub=ruk.id')->where('ruk.tahun', $tahun)->first();
        $count = $this->rukModel->select('count( IF( ruk.status = "Discuss", 1, NULL) ) AS cdiscuss1, count( IF( ruk.status = "Approve", 1, NULL) ) AS capprove1, 
        count(ruk.status) AS ctotal2,
        count( IF( ruk.status = "Reject", 1, NULL) ) AS creject1, SUM( IF( ruk.status = "",1, NULL) ) AS cdisclaim1')->where('tahun', $tahun)->first();
        $msg = [
            'data' => $sum,
            'count' => $count
        ];

        return $this->response->setJSON($msg);
    }

    public function getbanner2()
    {
        $pkm = $this->request->getPost('pkm');
        $sum = array();
        $tahun = $this->session->get('db_tahun');
        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) AS harga_total FROM usul_belanja where usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_sub) as b";
        $sum = $this->rukModel->select('SUM( IF( ruk.status = "Discuss", b.harga_total, 0) ) AS discuss2, SUM( IF( ruk.status = "Approve", b.harga_total, 0) ) AS approve2, 
        SUM(b.harga_total) AS total2,
        SUM( IF( ruk.status = "Reject", b.harga_total, 0) ) AS reject2, SUM( IF( ruk.status = "", b.harga_total, 0) ) AS disclaim2')->join($join, 'b.id_sub=ruk.id')->where(['id_pkm' => $pkm, 'ruk.tahun' => $tahun])->first();
        $count = $this->rukModel->select('count( IF( ruk.status = "Discuss", 1, NULL) ) AS cdiscuss2, count( IF( ruk.status = "Approve", 1, NULL) ) AS capprove2, 
        count(ruk.status) AS ctotal1,
        count( IF( ruk.status = "Reject", 1, NULL) ) AS creject2, SUM( IF( ruk.status = "",1, NULL) ) AS cdisclaim2')->where(['id_pkm' => $pkm, 'ruk.tahun' => $tahun])->first();
        $msg = [
            'data' => $sum,
            'count' => $count
        ];

        return $this->response->setJSON($msg);
    }

    public function getbanner3()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $tahun = $this->session->get('db_tahun');
        $sum = array();
        $join = "(SELECT usul_belanja.id_sub, SUM(usul_belanja.harga_total) AS harga_total FROM usul_belanja where usul_belanja.tahun=" . $tahun . " GROUP BY usul_belanja.id_sub) as b";
        $sum = $this->rukModel->select('SUM( IF( ruk.status = "Discuss", b.harga_total, 0) ) AS discuss3, SUM( IF( ruk.status = "Approve", b.harga_total, 0) ) AS approve3, 
        SUM(b.harga_total) AS total3,
        SUM( IF( ruk.status = "Reject", b.harga_total, 0) ) AS reject3, SUM( IF( ruk.status = "", b.harga_total, 0) ) AS disclaim3')->join($join, 'b.id_sub=ruk.id')->join('sub_komponen', 'sub_komponen.id=ruk.id_menu')->where(['ruk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog, 'sub_komponen.tahun' => $tahun])->first();
        $count = $this->rukModel->select('count( IF( ruk.status = "Discuss", 1, NULL) ) AS cdiscuss3, count( IF( ruk.status = "Approve", 1, NULL) ) AS capprove3, 
        count(ruk.status) AS ctotal3,
        count( IF( ruk.status = "Reject", 1, NULL) ) AS creject3, SUM( IF( ruk.status = "",1, NULL) ) AS cdisclaim3')->join('sub_komponen', 'sub_komponen.id=ruk.id_menu', 'left')->where(['ruk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog, 'sub_komponen.tahun' => $tahun])->first();
        $msg = [
            'data' => $sum,
            'count' => $count
        ];

        return $this->response->setJSON($msg);
    }

    public function getprogram()
    {

        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $tahun = $this->session->get('db_tahun');
        $response = array();

        if ($tahun != 2023) {
            $wherecond = "( ( ( usul_belanja.id_pkm='" . $pkm . "' AND usul_belanja.tahun='" . $tahun . "') ) )";

            $data['data'] = array();
            $join = '(SELECT usul_belanja.id_program, ruk.id_menu, SUM( IF( ruk.id_menu <= 131, usul_belanja.harga_total, 0) ) AS ukm, 
            SUM( IF( ruk.id_menu <= 139, usul_belanja.harga_total, 0) ) AS harga_total
            FROM usul_belanja JOIN ruk ON ruk.id = usul_belanja.id_sub WHERE' . $wherecond . ' GROUP BY usul_belanja.id_program) as b';

            if ($pkm > 68) {
                $result = $this->programModel->select('program.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_program=program.id', 'Left')
                    ->where('program.id >', 22)
                    ->findAll();
            } else {
                $result = $this->programModel->select('program.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_program=program.id', 'Left')
                    ->findAll();
            }



            $no = 1;
            foreach ($result as $key => $value) {

                $ops = '<div class="text-center">';
                $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="usulan(' . $value->id . ')"><i class="fa fa-edit"></i>Lihat Detail</button>';
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
                    $value->nama_program,
                    $value->ukm,
                    $pukm,
                    $value->harga_total - $value->ukm,
                    $pcovid,
                    $value->harga_total,
                    $ops,
                );
            }
        } else {
            $wherecond = "( ( ( usul_belanja.id_pkm='" . $pkm . "' AND usul_belanja.tahun='" . $tahun . "') ) )";

            $data['data'] = array();
            $join = '(SELECT usul_belanja.id_program, ruk.id_menu, SUM( IF( ruk.id_menu <= 131, usul_belanja.harga_total, 0) ) AS ukm, 
            SUM( IF( ruk.id_menu <= 363, usul_belanja.harga_total, 0) ) AS harga_total
            FROM usul_belanja JOIN ruk ON ruk.id = usul_belanja.id_sub WHERE' . $wherecond . ' GROUP BY usul_belanja.id_program) as b';

            if ($pkm > 68) {
                $result = $this->programModel->select('program.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_program=program.id', 'Left')
                    ->where('program.id >', 22)
                    ->findAll();
            } else {
                $result = $this->programModel->select('program.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(b.ukm,0) as ukm')
                    ->join($join, 'b.id_program=program.id', 'Left')
                    ->findAll();
            }



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
        }

        return $this->response->setJSON($data);
    }

    public function getobjek()
    {
        $objek = $this->request->getPost('objek');
        $tahun = $this->session->get('db_tahun');
        if ($objek) {
            $datarka = $this->penyusunModel->select('id_kode_belanja, kode_belanja.nama_belanja')
                ->join('kode_belanja', 'kode_belanja.id=penyusun.id_kode_belanja')
                ->where(['id_subkomponen' => $objek])->findAll();
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

            $data = $this->kodebelanjaModel->where(['id' => $obj])->first();
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

    public function getOneRuk()
    {
        $response = array();

        $id = $this->request->getPost('id');
        $pkm = $this->request->getPost('pkm');
        $tahun = $this->session->get('db_tahun');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->rukModel->where(['id_menu' => $id, 'id_pkm' => $pkm, 'tahun' => $tahun])->first();

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

            $data = $this->rukModel->where(['id' => $id])->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function gethiji()
    {
        $response = array();
        $tahun = $this->session->get('db_tahun');
        $id = 12;

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->rukModel->where(['id' => $id, 'tahun' => $tahun])->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function getprog()
    {
        $response = array();
        $tahun = $this->session->get('db_tahun');
        $prog = $this->request->getPost('prog');

        if ($this->validation->check($prog, 'required|numeric')) {

            $data = $this->programModel->where(['id' => $prog])->first();

            return $this->response->setJSON($data);
        } else {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {

        $response = array();

        $fields['id'] = $this->request->getPost('id');
        $fields['id_menu'] = $this->request->getPost('idMenu');

        $fields['tujuan'] = $this->request->getPost('tujuan');
        $fields['sasaran'] = $this->request->getPost('sasaran');
        $fields['target'] = $this->request->getPost('target');
        $fields['tgjawab'] = $this->request->getPost('tgjawab');
        $fields['sumberdaya'] = $this->request->getPost('sumberdaya');
        $fields['mitra'] = $this->request->getPost('mitra');
        $fields['waktu'] = $this->request->getPost('waktu');
        $fields['indikator'] = $this->request->getPost('indikator');
        $fields['id_pkm'] = $this->request->getPost('pkm');
        $fields['keterangan'] = $this->request->getPost('keterangan');
        $fields['tahun'] = $tahun = $this->session->get('db_tahun');
        $fields['user_id'] = user_id();
        $id_menu = $this->request->getPost('idMenu');
        $id_pkm = $this->request->getPost('pkm');

        $this->validation->setRules([
            'id_menu' => ['label' => 'Kegiatan', 'rules' => 'required|max_length[11]'],
            'tujuan' => ['label' => 'Tujuan', 'rules' => 'required|max_length[255]'],
            'sasaran' => ['label' => 'Sasaran', 'rules' => 'required|max_length[255]'],
            'target' => ['label' => 'Target Sasaran', 'rules' => 'required|max_length[255]'],
            'tgjawab' => ['label' => 'Penanggungjawab', 'rules' => 'required|max_length[255]'],
            'sumberdaya' => ['label' => 'Kebutuhan Sumber Daya', 'rules' => 'required|max_length[255]'],
            'mitra' => ['label' => 'Mitra Kerja', 'rules' => 'required|max_length[255]'],
            'waktu' => ['label' => 'Waktu Pelaksanaan', 'rules' => 'required|max_length[255]'],
            'indikator' => ['label' => 'Indikator Kinerja', 'rules' => 'required|max_length[255]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {
            $data = $this->rukModel->where(['id_menu' => $id_menu, 'id_pkm' => $id_pkm])->first();
            if ($data) {
                $response['success'] = false;
                $response['messages'] = 'Maaf, Kegiatan yang anda masukan sudah ada, silahkan tambahkan pada rincian objek';
            } else {
                if ($this->rukModel->insert($fields)) {

                    $response['success'] = true;
                    $response['messages'] = 'Tambah RUK Berhasil';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Tambah RUK Gagal!';
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
        $fields['sumberdaya'] = $this->request->getPost('sumberdaya');
        $fields['mitra'] = $this->request->getPost('mitra');
        $fields['waktu'] = $this->request->getPost('waktu');
        $fields['indikator'] = $this->request->getPost('indikator');
        $fields['keterangan'] = $this->request->getPost('keterangan');


        $this->validation->setRules([
            'id_menu' => ['label' => 'Kegiatan', 'rules' => 'required|max_length[11]'],
            'tujuan' => ['label' => 'Tujuan', 'rules' => 'required|max_length[255]'],
            'sasaran' => ['label' => 'Sasaran', 'rules' => 'required|max_length[255]'],
            'target' => ['label' => 'Target Sasaran', 'rules' => 'required|max_length[255]'],
            'tgjawab' => ['label' => 'Penanggungjawab', 'rules' => 'required|max_length[255]'],
            'sumberdaya' => ['label' => 'Kebutuhan Sumber Daya', 'rules' => 'required|max_length[255]'],
            'mitra' => ['label' => 'Mitra Kerja', 'rules' => 'required|max_length[255]'],
            'waktu' => ['label' => 'Waktu Pelaksanaan', 'rules' => 'required|max_length[255]'],
            'indikator' => ['label' => 'Indikator Kinerja', 'rules' => 'required|max_length[255]'],

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->listErrors();
        } else {

            if ($this->rukModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Ubah RUK Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Ubah RUK Gagal!';
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

            if ($this->rukModel->update($fields['id'], $fields)) {

                $response['success'] = true;
                $response['messages'] = 'Ubah Status RUK Berhasil';
            } else {

                $response['success'] = false;
                $response['messages'] = 'Ubah Status RUK Gagal!';
            }
        }

        return $this->response->setJSON($response);
    }

    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('id');
        $data = $this->usulanModel->where('id_sub', $id)->first();

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($data) {
                $response['success'] = false;
                $response['messages'] = 'Hapus RUK Gagal, Data belanja masih ada!';
            } else {
                if ($this->rukModel->where('id', $id)->delete()) {

                    $response['success'] = true;
                    $response['messages'] = 'Hapus RUK Berhasil';
                } else {

                    $response['success'] = false;
                    $response['messages'] = 'Hapus RUK Gagal!';
                }
            }
        }

        return $this->response->setJSON($response);
    }
}