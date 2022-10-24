<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        $tahun = $this->session->get('db_tahun');
        $data['tahun'] = $tahun;
        if (!$tahun) {
            return redirect()->to('pilihtahun');
        } else {
            return view('dashboard', $data);
        }
    }
}