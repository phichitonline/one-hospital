<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    //
    function index()
    {
        $apitoken = env('API_TOKEN');

        $collection = Http::withHeaders([
            'Authorization'=> 'Bearer '.$apitoken.'',
        ])->get('https://apiservice.tphcp.go.th/api/products');

        return view('test',[
            'collection' => $collection['data'],
        ]);
    }

    function test1()
    {
        $apitoken = env('API_TOKEN');

        $collection = Http::withHeaders([
            'Authorization'=> 'Bearer '.$apitoken.'',
        ])->get('https://apiservice.tphcp.go.th/api/products');

        return response()->json($collection['data']);
    }
}
