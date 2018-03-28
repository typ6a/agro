<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class Kenga3MainController extends Controller
{
    public function __construct()
    {
        header('Content-Type: text/html; charset=utf-8');
        set_time_limit(36000);
    }

    public function getKenga3Categories()
    {
        $c = new \App\Http\Controllers\GetKenga3Controller();
        $c->execute();
    }
    // public function crawlRewProperties()
    // {
    //     $c = new \App\Libs\RewPropertiesCrawler();
    //     $c->execute();
    // }
}