<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', function(){
    return view('upload-file');
});

Route::post('/upload', function(){
    if (request()->has('mycsv')) {
        // dd(request()->has('mycsv'));
        $csv = array_map('getcsv', file(request()->mycsv));
        dd($csv);
        // return $csv;
    } else{
        return "Upload Your File.";
    }
    // if (request()->has('mycsv')) {
    //     return "yes";
    // }
});

