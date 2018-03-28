<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HorozMainController extends Controller
{
    public function __construct()
    {
        header('Content-Type: text/html; charset=utf-8');
        set_time_limit(36000);
    }

    public function getHorozua()
    {
        $c = new \App\Http\Controllers\GetHorozController();
        $c->execute();
    }
    // public function crawlRewProperties()
    // {
    //     $c = new \App\Libs\RewPropertiesCrawler();
    //     $c->execute();
    // }
}