<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TripoliMainController extends Controller
{
   public function __construct()
    {
        header('Content-Type: text/html; charset=utf-8');
        set_time_limit(36000);
    }

    public function getTripoliFermers()
    {
        $c = new \App\Http\Controllers\getTripoliFermersController();
        $c->execute();
    }

    public function sendEmailsTripoliFermers()
    {
        $c = new \App\Http\Controllers\SendEmailTripoliFermersController();
        $c->execute();
    }
}
