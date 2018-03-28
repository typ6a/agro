<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProlumController extends Controller
{
    public function __construct()
    {
        header('Content-Type: text/html; charset=utf-8');
        set_time_limit(36000);
    }

    public function getProlum()
    {
        $c = new \App\Http\Controllers\GetProlumController();
        $c->execute();
    }
}
