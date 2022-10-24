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
use App\Models\UsulanRpkpModel;
use App\Models\PaguModel;
use App\Models\RealisasiModel;
use App\Models\Mcountdown;

class Rpkperubahan extends BaseController
{

    protected $rukModel;
    protected $rpkModel;
    protected $programModel;
    protected $penyusunModel;
    protected $kodebelanjaModel;
    protected $uptModel;
    protected $usulanModel;
    protected $usulanRpkModel;
    protected $usulanRpkpModel;
    protected $paguModel;
    protected $realisasiModel;
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
        $this->usulanRpkpModel = new UsulanRpkpModel();
        $this->paguModel = new PaguModel();
        $this->realisasiModel = new RealisasiModel();
        $this->mcountdown = new Mcountdown();
        $this->validation =  \Config\Services::validation();
        $this->session = session();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $timer = $this->mcountdown->where(['link' => 'rpkperubahan', 'tahun' => $tahun])->first();
        $data = [
            'controller'        => 'rpkperubahan',
            'title'             => 'Daftar Rencana Usulan Kegiatan ',
            'timer'                => $timer
        ];

        if (!$this->session->get('db_tahun')) {
            return redirect()->to(base_url('pilihtahun'));
        } else {
            $data['tahun'] = $tahun;
            return view('rpkp/rpkperubahan', $data);
        }
    }

    public function getAll()
    {
        $tahun = $this->session->get('db_tahun');
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $response = array();

        $data['data'] = array();
        $join = "(select usul_belanja_rpk.id_sub, sum(usul_belanja_rpk.harga_total) as harga_total FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        // $join = "(select usul_belanja_rpk.id_sub, sum(usul_belanja_rpk.harga_total) as harga_total, sum((select SUM(realisasi.jumlah) FROM realisasi WHERE usul_belanja_rpk.id=realisasi.id_rpk GROUP BY realisasi.id_rpk)) as jumlah FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $join2 = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total2 FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as c";
        $result = $this->rpkModel->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab,rpk.jadwal, rpk.lokasi, rpk.status2, rpk.catatan2, rpk.waktu, sub_komponen.nama_subkomponen, b.harga_total, c.harga_total2')
            ->join($join, 'b.id_sub=rpk.id', 'left')
            ->join($join2, 'c.id_sub=rpk.id', 'left')
            ->join('sub_komponen', 'sub_komponen.id=rpk.id_menu', 'left')
            ->where(['rpk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog])
            ->findAll();
        $no = 1;

        foreach ($result as $key => $value) {

            $realisasi = $this->realisasiModel->select('usul_belanja_rpk.id_sub, sum(realisasi.jumlah) as jumlah')->join('usul_belanja_rpk', 'usul_belanja_rpk.id=realisasi.id_rpk')->where(['usul_belanja_rpk.id_sub' => $value->id, 'realisasi.bulan <=' => 5, 'realisasi.tahun' => $tahun])->groupBy('usul_belanja_rpk.id_sub')->first();
            $jumlah = ($realisasi != null) ? $realisasi->jumlah : 0;

            $ops2 = "";
            if ($value->status2 == "Approve") {
                $status = "success";
            } elseif ($value->status2 == "Discuss") {
                $status = "warning";
            } else {
                $status = "danger";
            };
            if ($value->status2 != "") {
                $ops2 =  '<button type="button" class="btn btn-' . $status . '" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $value->catatan2 . '">
                ' . $value->status2 . '</button>';
            };

            $ops = '<div class="text-center">';
            $ops .= '	<button type="button" class="btn btn-sm btn-info" onclick="edit(' . $value->id . ')"><i class="fa fa-edit"></i>Edit</button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-danger" onclick="remove(' . $value->id . ')"><i class="fa fa-trash"></i>Hapus</button>';
            $ops .= '	<button type="button" class="btn btn-sm btn-success" onclick="belanja(' . $value->id . ',' . $value->id_menu . ')"><i class="fa fa-search"></i>Objek Belanja</button>';
            if (in_groups('admin')) {

                $ops .= '	<button type="button" class="btn btn-sm btn-warning" onclick="edit2(' . $value->id . ')"><i class="fa fa-check"></i>Approval</button>';
            };
            $ops .= '</div>';

            $data['data'][$key] = array(
                $no++,
                $value->nama_subkomponen,
                $value->keterangan,
                $value->tujuan,
                $value->sasaran,
                $value->target,
                $value->tgjawab,
                $value->jadwal,
                $value->lokasi,
                $value->harga_total,
                $jumlah,
                $value->harga_total2,
                $ops2,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function dt2()
    {
        $pkm = $this->request->getPost('pkm');
        $prog = $this->request->getPost('prog');
        $dt = new Datatables();
        $join = "(select usul_belanja_rpk.id_sub, sum(usul_belanja_rpk.harga_total) as harga_total, sum((select SUM(realisasi.jumlah) FROM realisasi WHERE usul_belanja_rpk.id=realisasi.id_rpk GROUP BY realisasi.id_rpk)) as jumlah FROM usul_belanja_rpk GROUP BY usul_belanja_rpk.id_sub) as b";
        $join2 = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) as harga_total2 FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as c";

        $dt->table('rpk');
        $dt->select('rpk.id, rpk.id_menu, rpk.keterangan, rpk.tujuan, rpk.sasaran, rpk.target, rpk.tgjawab,rpk.jadwal, rpk.lokasi, rpk.status2, rpk.catatan2, rpk.waktu, sub_komponen.nama_subkomponen, b.harga_total, c.harga_total2, b.jumlah');
        $dt->join($join, 'b.id_sub=rpk.id', 'left');
        $dt->join($join2, 'c.id_sub=rpk.id', 'left');
        $dt->join('sub_komponen', 'sub_komponen.id=rpk.id_menu', 'left');
        $dt->where(['rpk.id_pkm' => $pkm]);
        $dt->where(['sub_komponen.id_prog' => $prog]);
        return $dt->addColumn('action', function ($db) {
            $id = $db['id'];
            $idmenu = $db['id_menu'];
            $btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> 
            <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>
            <button class='btn btn-sm btn-info' onclick='belanja(" . $id . "," . $idmenu . ")' title='objek belanja'><i class='fa fa-search'></i>Objek Belanja</button>";
            if (in_groups('admin')) {

                $btn .= '	<button type="button" class="btn btn-sm btn-warning" onclick="edit2(' . $id . ')"><i class="fa fa-check"></i>Approval</button>';
            };
            return $btn;
        })->draw();
    }

    public function dt()
    {
        // $pkm = $this->request->getPost('pkm');
        // $prog = $this->request->getPost('prog');
        // $dt = new Datatables();
        // $dt->table('ruk');
        // // $objek = "(SELECT usul_belanja_rpk.id_sub, sum(harga_total) as harga_total from usul_belanja WHERE usul_belanja_rpk.id_pkm=" . $pkm . " GROUP BY usul_belanja_rpk.id_sub) as usul";
        // $dt->select('ruk.*, sub_komponen.nama_subkomponen, sum("usul_belanja_rpk.harga_total")');
        // $dt->join('sub_komponen', 'sub_komponen.id=ruk.id_menu');
        // $dt->join('usul_belanja', 'usul_belanja_rpk.id_sub=ruk.id');
        // // $dt->GroupBy('usul_belanja_rpk.id_sub');
        // $dt->where(['ruk.id_pkm' => $pkm]);
        // $dt->where(['sub_komponen.id_prog' => $prog]);
        // return $dt->addColumn('action', function ($db) {
        //     $id = $db['id'];
        //     $idmenu = $db['id_menu'];
        //     $btn = "<button class='btn btn-sm btn-warning' onclick='edit(\"$id\")' title='edit'><i class='fa fa-edit'></i>edit</button> 
        //     <button class='btn btn-sm btn-danger' onclick='remove(\"$id\")' title='delete'><i class='fa fa-eraser'></i>Hapus</button>
        //     <button class='btn btn-sm btn-info' onclick='belanja(" . $id . "," . $idmenu . ")' title='objek belanja'><i class='fa fa-search'></i>Objek Belanja</button>";
        //     return $btn;
        // })->draw();
    }

    public function getpuskesmas()
    {
        $data['data'] = array();
        $join = "(SELECT usul_belanja_rpk.id_pkm, rpk.id_menu, SUM( IF( rpk.id_menu <= 131, usul_belanja_rpk.harga_total, 0) ) AS ukm, 
        SUM( IF( rpk.id_menu <= 139, usul_belanja_rpk.harga_total, 0) ) AS harga_total
        FROM usul_belanja_rpk JOIN rpk ON rpk.id = usul_belanja_rpk.id_sub GROUP BY usul_belanja_rpk.id_pkm) as b";

        $join2 = "(SELECT rpk.id_pkm as id_pkm, 
        COUNT(IF(rpk.status2 = 'Approve', 1, NULL)) 'Approve',
        COUNT(IF(rpk.status2 = 'Reject', 1, NULL)) 'Reject',
        COUNT(*) 'total' FROM rpk Group By rpk.id_pkm) as c";
        $pkm = user()->puskesmas;
        if (in_groups('admin') || in_groups('programmer')) {
            $result = $this->uptModel->select('upt.*, pagu.anggaran, COALESCE(b.harga_total, 0) as harga_total, c.Approve, c.Reject, c.total')
                ->join($join, 'b.id_pkm=upt.id', 'Left')
                ->join('pagu', 'pagu.id_pkm=upt.id')
                ->join($join2, 'c.id_pkm=upt.id', 'left')
                ->orderBy('upt.id', 'ASC')
                ->findAll();
        } else {
            $result = $this->uptModel->select('upt.*, pagu.anggaran, COALESCE(b.harga_total, 0) as harga_total, c.Approve, c.Reject, c.total')
                ->join($join, 'b.id_pkm=upt.id', 'Left')
                ->join('pagu', 'pagu.id_pkm=upt.id')
                ->join($join2, 'c.id_pkm=upt.id', 'left')
                ->where(['upt.id' =>  $pkm])
                ->orderBy('upt.id', 'ASC')
                ->findAll();
        };
        $response = array();
        $no = 1;

        foreach ($result as $key => $value) {
            $join3 = "(SELECT usul_belanja_rpkp.id_pkm, rpk.id_menu, SUM( IF( rpk.id_menu <= 131, usul_belanja_rpkp.harga_total, 0) ) AS ukm2, 
            SUM( IF( rpk.id_menu = 307, usul_belanja_rpkp.harga_total, 0) ) AS insentif, 
            SUM( IF( rpk.id_menu <= 139 and rpk.id_menu > 131 , usul_belanja_rpkp.harga_total, 0) ) AS covid, 
            SUM(usul_belanja_rpkp.harga_total) AS harga_total2, (SUM( IF( rpk.id_menu <= 131, usul_belanja_rpkp.harga_total, 0) )/SUM(usul_belanja_rpkp.harga_total)*100) as pukm,
            (SUM( IF( rpk.id_menu = 307, usul_belanja_rpkp.harga_total, 0) )/SUM(usul_belanja_rpkp.harga_total)*100) as pinsentif,
            (SUM( IF( rpk.id_menu <= 139 and rpk.id_menu > 131 , usul_belanja_rpkp.harga_total, 0) )/ SUM(usul_belanja_rpkp.harga_total)*100) as pcovid
            FROM usul_belanja_rpkp JOIN rpk ON rpk.id = usul_belanja_rpkp.id_sub GROUP BY usul_belanja_rpkp.id_pkm) as d";


            $anggaran = $this->usulanRpkpModel->select('sum(harga_total) as harga_total')->where('usul_belanja_rpkp.id_pkm', $value->id)->groupBy('usul_belanja_rpkp.id_pkm')->first();

            $anggaran2 = $this->usulanRpkpModel->select('SUM( IF( rpk.id_menu <= 131, usul_belanja_rpkp.harga_total, 0) ) AS ukm2, 
            SUM( IF( rpk.id_menu = 307, usul_belanja_rpkp.harga_total, 0) ) AS insentif, 
            SUM( IF( rpk.id_menu <= 139 and rpk.id_menu > 131 , usul_belanja_rpkp.harga_total, 0) ) AS covid')->join('rpk', 'rpk.id = usul_belanja_rpkp.id_sub', 'right')->where('usul_belanja_rpkp.id_pkm', $value->id)->groupBy('usul_belanja_rpkp.id_pkm')->first();
            $hasil = 0;
            $hasil = intval(($value->Approve / $value->total) * 100);
            // dd($hasil);
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


            $data['data'][$key] = array(
                $no++,
                $value->pkm,
                $value->anggaran,
                $value->harga_total,
                $anggaran2->ukm2,
                (($anggaran2->ukm2 / $anggaran->harga_total) * 100),
                $anggaran2->insentif,
                (($anggaran2->insentif / $anggaran->harga_total) * 100),
                $anggaran2->covid,
                (($anggaran2->covid / $anggaran->harga_total) * 100),
                $anggaran->harga_total,
                $ops2,
                $ops,
            );
        }

        return $this->response->setJSON($data);
    }

    public function getbanner()
    {
        $sum = array();
        $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) AS harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
        $sum = $this->rpkModel->select('SUM( IF( rpk.status2 = "Discuss", b.harga_total, 0) ) AS discuss1, SUM( IF( rpk.status2 = "Approve", b.harga_total, 0) ) AS approve1, 
        SUM(b.harga_total) AS total1,
        SUM( IF( rpk.status2 = "Reject", b.harga_total, 0) ) AS reject1, SUM( IF( rpk.status2 = "", b.harga_total, 0) ) AS disclaim1')->join($join, 'b.id_sub=rpk.id')->first();
        $count = $this->rpkModel->select('count( IF( rpk.status2 = "Discuss", 1, NULL) ) AS cdiscuss1, count( IF( rpk.status2 = "Approve", 1, NULL) ) AS capprove1, 
        count(rpk.status2) AS ctotal2,
        count( IF( rpk.status2 = "Reject", 1, NULL) ) AS creject1, SUM( IF( rpk.status2 = "",1, NULL) ) AS cdisclaim1')->first();
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
        $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) AS harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
        $sum = $this->rpkModel->select('COALESCE(SUM( IF( rpk.status2 = "Discuss", b.harga_total, 0)),0) AS discuss2, COALESCE(SUM( IF( rpk.status2 = "Approve", b.harga_total, 0)),0) AS approve2, 
        COALESCE(SUM(b.harga_total),0) AS total2,
        COALESCE(SUM( IF( rpk.status2 = "Reject", b.harga_total, 0)),0) AS reject2, COALESCE(SUM( IF( rpk.status2 = "", b.harga_total, 0)),0) AS disclaim2')->join($join, 'b.id_sub=rpk.id')->where('id_pkm', $pkm)->first();
        $count = $this->rpkModel->select('COALESCE(count( IF( rpk.status2 = "Discuss", 1, NULL)),0) AS cdiscuss2, COALESCE(count( IF( rpk.status2 = "Approve", 1, NULL)),0) AS capprove2, 
        COALESCE(count(rpk.status2),0) AS ctotal1,
        COALESCE(count( IF( rpk.status2 = "Reject", 1, NULL)),0) AS creject2, COALESCE(SUM( IF( rpk.status2 = "",1, NULL)),0) AS cdisclaim2')->where('id_pkm', $pkm)->first();
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
        $join = "(SELECT usul_belanja_rpkp.id_sub, SUM(usul_belanja_rpkp.harga_total) AS harga_total FROM usul_belanja_rpkp GROUP BY usul_belanja_rpkp.id_sub) as b";
        $sum = $this->rpkModel->select('SUM( IF( rpk.status2 = "Discuss", b.harga_total, 0) ) AS discuss3, SUM( IF( rpk.status2 = "Approve", b.harga_total, 0) ) AS approve3, 
        SUM(b.harga_total) AS total3,
        SUM( IF( rpk.status2 = "Reject", b.harga_total, 0) ) AS reject3, SUM( IF( rpk.status2 = "", b.harga_total, 0) ) AS disclaim3')->join($join, 'b.id_sub=rpk.id')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu')->where(['rpk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog])->first();
        $count = $this->rpkModel->select('count( IF( rpk.status2 = "Discuss", 1, 0) ) AS cdiscuss3, count( IF( rpk.status2 = "Approve", 1, 0) ) AS capprove3, 
        count(rpk.status2) AS ctotal3,
        count( IF( rpk.status2 = "Reject", 1, 0) ) AS creject3, SUM( IF( rpk.status2 = "",1, 0) ) AS cdisclaim3')->join('sub_komponen', 'sub_komponen.id=rpk.id_menu', 'left')->where(['rpk.id_pkm' => $pkm, 'sub_komponen.id_prog' => $prog])->first();
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
        $response = array();

        $data['data'] = array();
        $join = "(SELECT usul_belanja_rpk.id_program, rpk.id_menu,
        SUM(usul_belanja_rpk.harga_total) AS harga_total
        FROM usul_belanja_rpk JOIN rpk ON rpk.id = usul_belanja_rpk.id_sub WHERE usul_belanja_rpk.id_pkm=" . $pkm . " GROUP BY usul_belanja_rpk.id_program) as b";
        $join2 = "(SELECT usul_belanja_rpkp.id_program, rpk.id_menu,
        SUM(usul_belanja_rpkp.harga_total) AS harga_total2
        FROM usul_belanja_rpkp JOIN rpk ON rpk.id = usul_belanja_rpkp.id_sub WHERE usul_belanja_rpkp.id_pkm=" . $pkm . " GROUP BY usul_belanja_rpkp.id_program) as c";
        $result = $this->programModel->select('program.*, COALESCE(b.harga_total, 0) as harga_total, COALESCE(c.harga_total2, 0) as harga_total2')
            ->join($join, 'b.id_program=program.id', 'Left')
            ->join($join2, 'c.id_program=program.id', 'Left')
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
                $value->harga_total2,
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
        $fields['tahun'] = $this->session->get('db_tahun');
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
        $fields['status2'] = $this->request->getPost('status');
        $fields['catatan2'] = $this->request->getPost('catatan');


        $this->validation->setRules([
            'status2' => ['label' => 'Status', 'rules' => 'required'],
            'catatan2' => ['label' => 'Catatan', 'rules' => 'required'],
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