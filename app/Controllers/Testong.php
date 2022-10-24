<?php

namespace App\Controllers;

class Testong extends BaseController
{
    public function index()
    {
        $email = \Config\Services::email();

        $email->setTo('dennyrico.21@gmail.com');

        $email->setSubject('Email Test');
        $email->setMessage('Testing the email class.');

        if ($email->send()) {
            echo "<h1>Pesan terkirim</h1>";
        } else {
            echo "<h1>Pesan belum terkirim</h1>";
        }
    }
}