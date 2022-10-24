<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;
use App\Models\PengajuanModel;
use App\Models\UptModel;
use App\Models\UsulanModel;
use App\Models\UsulanRpkModel;
use App\Models\DaftarrkaModel;
use App\Models\RealisasiModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use TCPDF;

class Pengajuan extends BaseController
{

	protected $pengajuanModel;
	protected $uptModel;
	protected $usulanModel;
	protected $usulanrpkModel;
	protected $daftarrkaModel;
	protected $realisasiModel;
	protected $validation;
	protected $session;

	public function __construct()
	{
		$this->pengajuanModel = new PengajuanModel();
		$this->uptModel = new UptModel();
		$this->usulanModel = new UsulanModel();
		$this->usulanrpkModel = new UsulanRpkModel();
		$this->daftarrkaModel = new DaftarrkaModel();
		$this->realisasiModel = new RealisasiModel();
		$this->session = session();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{
		$tahun = $this->session->get('db_tahun');
		$data = [
			'controller'    	=> 'pengajuan',
			'title'     		=> 'Pengajuan Pencairan'
		];

		if (!$this->session->get('db_tahun')) {
			return redirect()->to(base_url('pilihtahun'));
		} else {
			$data['tahun'] = $tahun;
			return view('pengajuan', $data);
		}
	}


	public function basic()
	{
		$tahun = $this->session->get('db_tahun');
		$db = db_connect();
		$builder = $db->table('pengajuan')->select('pengajuan.id, keterangan, no_pengajuan, tgl_pengajuan, no_spp, tgl_spp, no_spm, tgl_spm, verifikator, nip_verifikator, a.nama_bulan as bln1, b.nama_bulan as bln2, nilai ')->join('bulan a', 'a.id=pengajuan.bulan_awal')->join('bulan b', 'b.id=pengajuan.bulan_akhir')->where('tahun', $tahun);

		return DataTable::of($builder)->addNumbering()
			->add('action', function ($row) {
				return '<div class="btn-group">
				<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-eject"></i> Aksi  </button>
				<div class="dropdown-menu">
				<a class="dropdown-item text-info" onClick="save(' . $row->id . ')"><i class="fas fa-edit"></i>   ' .  lang("App.edit")  . '</a>
				<a class="dropdown-item text-orange" ><i class="far fa-copy"></i>   ' .  lang("App.copy")  . '</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item text-danger" onClick="remove(' . $row->id . ')"><i class="fas fa-trash"></i>   ' .  lang("App.delete")  . '</a>
				</div></div>
				<div class="btn-group">
				<button type="button" class=" btn btn-sm dropdown-toggle btn-success" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-print"></i> Cetak  </button>
				<div class="dropdown-menu">
				<a class="dropdown-item text-info" href=' . base_url("pengajuan/cetak1?id=1") . ' target="_blank"><i class="fas fa-eject"></i> Permohonan</a>
				<a class="dropdown-item" href=' . base_url("pengajuan/cetak2?id=1") . ' target="_blank"><i class="far fa-copy"></i> Pernyataan SPP</a>
				<a class="dropdown-item" href=' . base_url("pengajuan/cetak3?id=1") . ' target="_blank"><i class="far fa-copy"></i> Pernyataan SPM</a>
				<a class="dropdown-item" ><i class="far fa-copy"></i> SPTJM</a>
				<a class="dropdown-item" ><i class="far fa-copy"></i> SPTJB</a>
				<a class="dropdown-item" ><i class="far fa-copy"></i> Rincian</a>
				<a class="dropdown-item" ><i class="far fa-copy"></i> Surat Pernyataan</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item text-danger" onClick="remove(' . $row->id . ')"><i class="fas fa-trash"></i>   ' .  lang("App.delete")  . '</a>
				</div></div>';
			}, 'last')
			->hide('id')
			->toJson();
	}

	public function getAll()
	{
		// $response = $data['data'] = array();

		// $result = $this->pengajuanModel->select()->findAll();

		// foreach ($result as $key => $value) {

		// 	$ops = '<div class="btn-group">';
		// 	$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
		// 	$ops .= '<i class="fa-solid fa-pen-square"></i>  </button>';
		// 	$ops .= '<div class="dropdown-menu">';
		// 	$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id . ')"><i class="fa-solid fa-pen-to-square"></i>   ' .  lang("App.edit")  . '</a>';
		// 	$ops .= '<a class="dropdown-item text-orange" ><i class="fa-solid fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
		// 	$ops .= '<div class="dropdown-divider"></div>';
		// 	$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id . ')"><i class="fa-solid fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
		// 	$ops .= '</div></div>';

		// 	$data['data'][$key] = array(
		// 		$value->id,
		// 		$value->keterangan,
		// 		$value->no_pengajuan,
		// 		$value->tgl_pengajuan,
		// 		$value->no_spp,
		// 		$value->tgl_spp,
		// 		$value->no_spm,
		// 		$value->tgl_spm,
		// 		$value->verifikator,
		// 		$value->nip_verifikator,
		// 		$value->bulan_awal,
		// 		$value->bulan_akhir,
		// 		$value->nilai,

		// 		$ops
		// 	);
		// }

		// return $this->response->setJSON($data);
	}

	public function getSelect()
	{
		$pengajuan = $this->request->getPost('pengajuan');
		if ($pengajuan) {
			$datapengajuan = $this->pengajuanModel->select('id, keterangan')->where('id', $pengajuan)->findAll();
			$isidata = "";
		} else {
			$datapengajuan = $this->pengajuanModel->select('id, keterangan')->findAll();
			$isidata = "<option>--Pilih Nama Anggaran--</option>";
		}

		$data['data'] = array();

		foreach ($datapengajuan as $key => $value) {
			$isidata .= '	<option value="' . $value->id . '">' . $value->keterangan . '</option>';
		};

		$msg = [
			'data' => $isidata
		];
		return $this->response->setJSON($msg);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->pengajuanModel->where('id', $id)->first();

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
		$fields['id_pkm'] = $this->request->getPost('id_pkm');
		$fields['keterangan'] = $this->request->getPost('keterangan');
		$fields['no_pengajuan'] = $this->request->getPost('no_pengajuan');
		$fields['tgl_pengajuan'] = $this->request->getPost('tgl_pengajuan');
		$fields['no_spp'] = $this->request->getPost('no_spp');
		$fields['tgl_spp'] = $this->request->getPost('tgl_spp');
		$fields['no_spm'] = $this->request->getPost('no_spm');
		$fields['tgl_spm'] = $this->request->getPost('tgl_spm');
		$fields['verifikator'] = $this->request->getPost('verifikator');
		$fields['nip_verifikator'] = $this->request->getPost('nip_verifikator');
		$fields['bulan_awal'] = $this->request->getPost('bulan_awal');
		$fields['bulan_akhir'] = $this->request->getPost('bulan_akhir');
		$fields['tahun'] = $tahun;
		$fields['nilai'] = str_replace(",", "", $this->request->getPost('nilai'));


		$this->validation->setRules([
			'keterangan' => ['label' => 'Keterangan', 'rules' => 'required|min_length[0]'],
			'no_pengajuan' => ['label' => 'Nomor Surat Pengajuan', 'rules' => 'required|min_length[0]|max_length[100]'],
			'tgl_pengajuan' => ['label' => 'Tanggal Pengajuan', 'rules' => 'required|valid_date|min_length[0]'],
			'no_spp' => ['label' => 'Nomor SPP', 'rules' => 'required|min_length[0]|max_length[100]'],
			'tgl_spp' => ['label' => 'Tanggal SPP', 'rules' => 'required|valid_date|min_length[0]'],
			'no_spm' => ['label' => 'Nomor SPM', 'rules' => 'required|min_length[0]|max_length[100]'],
			'tgl_spm' => ['label' => 'Tanggal SPM', 'rules' => 'required|valid_date|min_length[0]'],
			'verifikator' => ['label' => 'Nama Verifikator', 'rules' => 'required|min_length[0]|max_length[100]'],
			'nip_verifikator' => ['label' => 'NIP Verifikator', 'rules' => 'required|min_length[0]|max_length[50]'],
			'bulan_awal' => ['label' => 'Bulan awal', 'rules' => 'required|min_length[0]|max_length[4]'],
			'bulan_akhir' => ['label' => 'Bulan akhir', 'rules' => 'required|min_length[0]|max_length[4]'],
			'id_pkm' => ['label' => 'Nama Puskesmas', 'rules' => 'required|min_length[0]|max_length[4]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pengajuanModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.insert-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.insert-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id'] = $this->request->getPost('id');
		$fields['keterangan'] = $this->request->getPost('keterangan');
		$fields['no_pengajuan'] = $this->request->getPost('no_pengajuan');
		$fields['tgl_pengajuan'] = $this->request->getPost('tgl_pengajuan');
		$fields['no_spp'] = $this->request->getPost('no_spp');
		$fields['tgl_spp'] = $this->request->getPost('tgl_spp');
		$fields['no_spm'] = $this->request->getPost('no_spm');
		$fields['tgl_spm'] = $this->request->getPost('tgl_spm');
		$fields['verifikator'] = $this->request->getPost('verifikator');
		$fields['nip_verifikator'] = $this->request->getPost('nip_verifikator');
		$fields['bulan_awal'] = $this->request->getPost('bulan_awal');
		$fields['bulan_akhir'] = $this->request->getPost('bulan_akhir');
		$fields['nilai'] = str_replace(",", "", $this->request->getPost('nilai'));


		$this->validation->setRules([
			'keterangan' => ['label' => 'Keterangan', 'rules' => 'required|min_length[0]'],
			'no_pengajuan' => ['label' => 'Nomor Surat Pengajuan', 'rules' => 'required|min_length[0]|max_length[100]'],
			'tgl_pengajuan' => ['label' => 'Tanggal Pengajuan', 'rules' => 'required|valid_date|min_length[0]'],
			'no_spp' => ['label' => 'Nomor SPP', 'rules' => 'required|min_length[0]|max_length[100]'],
			'tgl_spp' => ['label' => 'Tanggal SPP', 'rules' => 'required|valid_date|min_length[0]'],
			'no_spm' => ['label' => 'Nomor SPM', 'rules' => 'required|min_length[0]|max_length[100]'],
			'tgl_spm' => ['label' => 'Tanggal SPM', 'rules' => 'required|valid_date|min_length[0]'],
			'verifikator' => ['label' => 'Nama Verifikator', 'rules' => 'required|min_length[0]|max_length[100]'],
			'nip_verifikator' => ['label' => 'NIP Verifikator', 'rules' => 'required|min_length[0]|max_length[50]'],
			'bulan_awal' => ['label' => 'Bulan awal', 'rules' => 'required|min_length[0]|max_length[4]'],
			'bulan_akhir' => ['label' => 'Bulan akhir', 'rules' => 'required|min_length[0]|max_length[4]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pengajuanModel->update($fields['id'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.update-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.update-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->pengajuanModel->where('id', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("App.delete-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.delete-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function tampil()
	{
		$tahun = $this->session->get('db_tahun');
		$pkm = $this->request->getPost('pkm');
		$rka = $this->request->getVar('prog');
		$awal = $this->request->getPost('awal');
		$akhir = $this->request->getPost('akhir');
		$puskesmas = $this->uptModel->asArray()->where('id', $pkm)->first();
		$datarka = $this->daftarrkaModel->asArray()->select('daftar_rka.id, daftar_rka.kode, daftar_rka.judul, prog.kode_prog,prog.nama_prog, kegiatan.kode_keg, kegiatan.nama_keg')->join('prog', 'daftar_rka.id_prog=prog.id')->join('kegiatan', 'daftar_rka.id_keg=kegiatan.id')->findAll();

		$isitampil = "<table class='table table-bordered table-striped' style='border-width: medium'>";
		$isitampil .= "<thead>";
		$isitampil .= "<tr>";
		$isitampil .= "<th class='text-center'>No.</th>";
		$isitampil .= "<th class='text-center'>Rekening</th>";
		$isitampil .= "<th class='text-center'>Jumlah Anggaran</th>";
		$isitampil .= "<th class='text-center'>Pengajuan Sebelumnya</th>";
		$isitampil .= "<th class='text-center'>Jumlah Pengajuan</th>";
		$isitampil .= "</tr>";
		$isitampil .= "</thead>";
		$isitampil .= "<tbody class='bord'>";
		$i = 65;
		$total = 0;
		foreach ($datarka as $row) {
			$join2 = '(select id_rpk, sum(jumlah) as rkajumlah from realisasi where realisasi.jumlah>=' . $awal . ' and realisasi.bulan<=' . $akhir . ' and realisasi.tahun=' . $tahun . ' group by id_rpk) as c';
			$result = $this->usulanrpkModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpk.*, sum(usul_belanja_rpk.harga_total) as hargatotal, sum((select sum(realisasi.jumlah) from realisasi where realisasi.id_rpk=usul_belanja_rpk.id and realisasi.bulan>= ' . $awal . ' and realisasi.bulan <=' . $akhir . ' and realisasi.tahun=' . $tahun . ' group by realisasi.id_rpk)) as rkajumlah, sum((select sum(realisasi.jumlah) from realisasi where realisasi.id_rpk=usul_belanja_rpk.id and realisasi.bulan< ' . $awal . ' and realisasi.tahun=' . $tahun . ' group by realisasi.id_rpk)) as rkajumlahs')
				->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
				->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
				->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
				->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
				->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
				->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
				->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')
				->where([
					'usul_belanja_rpk.id_pkm' => $pkm,
					'sub_komponen.id_rka' => $row['id'],
					'usul_belanja_rpk.tahun' => $tahun,
				])->groupBy('daftar_rka.kode')
				->first();

			if ($result != null) {

				$msg = array();
				$isitampil .= "<tr>";
				$isitampil .= "<td><b>" . chr($i++) . ".</b></td>";
				$isitampil .= "<td><b>" . $row['kode'] . '-' . $row['judul'] . "</b></td>";
				$isitampil .= "<td class='text-end'><b>" .  number_format($result['hargatotal'], 0, ",", ".") . "</b></td>";
				$rkajumlahs = ($result['rkajumlahs'] != null) ? number_format($result['rkajumlahs'], 0, ",", ".") : 0;
				$isitampil .= "<td class='text-end'><b>" . $rkajumlahs . "</b></td>";
				$rkajumlah = ($result['rkajumlah'] != null) ? number_format($result['rkajumlah'], 0, ",", ".") : 0;

				if ($result['rkajumlah'] != null) {

					$total += $result['rkajumlah'];
				};

				$isitampil .= "<td class='text-end'><b>" . $rkajumlah . "</b></td>";
				$isitampil .= "</tr>";

				$join = '(select id_rpk, sum(jumlah) as rjumlah from realisasi where realisasi.jumlah>=' . $awal . ' and realisasi.bulan<=' . $akhir . ' and realisasi.tahun=' . $tahun . ' group by id_rpk) as c';
				$result1 = $this->usulanrpkModel->asArray()->select('daftar_rka.kode as kode_rka, daftar_rka.judul, rincian.nama_rincian AS nama_rincian,komponen.nama_komponen AS nama_komponen,sub_komponen.nama_subkomponen AS nama_subkomponen, sub_komponen.id_kom,  kodrek.kode AS kode,kodrek.nama_rekening AS nama_rekening,kode_belanja.nama_belanja AS nama_belanja,kode_belanja.satuan AS satuan,kode_belanja.harga AS harga,rpk.keterangan AS ket_rpk,usul_belanja_rpk.*, sum(usul_belanja_rpk.harga_total) as hargatotal, sum((select sum(realisasi.jumlah) from realisasi where realisasi.id_rpk=usul_belanja_rpk.id and realisasi.bulan< ' . $awal . ' and realisasi.tahun=' . $tahun . ' group by realisasi.id_rpk)) as kodjumlahs, sum(usul_belanja_rpk.harga_total) as hargatotal, sum((select sum(realisasi.jumlah) from realisasi where realisasi.id_rpk=usul_belanja_rpk.id and realisasi.bulan>= ' . $awal . ' and realisasi.bulan <=' . $akhir . ' and realisasi.tahun=' . $tahun . ' group by realisasi.id_rpk)) as kodjumlah')
					->join('kode_belanja', 'usul_belanja_rpk.id_belanja = kode_belanja.id', 'left')
					->join('kodrek', 'kodrek.id = kode_belanja.id_kodrek', 'left')
					->join('rpk', 'rpk.id = usul_belanja_rpk.id_sub', 'left')
					->join('sub_komponen', 'sub_komponen.id = rpk.id_menu', 'left')
					->join('rincian', 'rincian.id = sub_komponen.id_rincian', 'left')
					->join('komponen', 'komponen.id = sub_komponen.id_kom', 'left')
					->join('daftar_rka', 'daftar_rka.id=sub_komponen.id_rka', 'left')

					->where([
						'usul_belanja_rpk.id_pkm' => $pkm,
						'sub_komponen.id_rka' => $row['id'],
						'usul_belanja_rpk.tahun' => $tahun,
					])->groupBy('kodrek.kode')
					->orderBy('kodrek.kode')->findAll();
				$no = 1;
				foreach ($result1 as $row1) {

					$isitampil .= "<tr>";
					$isitampil .= "<td>" . $no++ . "</td>";
					$isitampil .= "<td>" . $row1['kode'] . " " . $row1['nama_rekening'] . "</td>";
					$isitampil .= "<td class='text-end' >" . number_format($row1['hargatotal'], 0, ",", ".") . "</td>";
					$kjumlahs = ($row1['kodjumlahs'] != null) ? number_format($row1['kodjumlahs'], 0, ",", ".") : 0;
					$isitampil .= "<td class='text-end' >" . $kjumlahs . "</td>";
					$kjumlah = ($row1['kodjumlah'] != null) ? number_format($row1['kodjumlah'], 0, ",", ".") : 0;
					$isitampil .= "<td class='text-end' >" . $kjumlah . "</td>";
					$isitampil .= "</tr>";
				};
			}
		}
		$isitampil .= "</tbody>";
		$isitampil .= "</table>";

		$msg = [
			'jumlah' => $total,
			'data' => $isitampil,
			'puskesmas' => $puskesmas['pkm']
		];

		return $this->response->setJSON($msg);
	}

	public function cetak1()
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->AddPage();
		$pdf->Image('assets/images/logogarut.png', 15, 10, 25, 25);
		$view = view('cetak/cetakx');
		$pdf->writeHTML($view);


		$this->response->setContentType('application/pdf');

		$pdf->Output('example_001.pdf', 'I');
	}
	public function cetak2()
	{

		$data['pkm'] = "puskesmas";
		return view('cetak/cetak2', $data);
	}
	public function cetak3()
	{

		$data['pkm'] = "puskesmas";
		return view('cetak/cetak3', $data);
	}
}