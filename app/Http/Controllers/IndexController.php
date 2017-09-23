<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

    /*
     * 后台登录页面展示
     */
    public function index()
    {
        return view('Index.index');
    }



}
