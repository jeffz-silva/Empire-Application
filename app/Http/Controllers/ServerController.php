<?php

namespace App\Http\Controllers;

use App\Models\ServerConfig;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    //
    public static function ServerStatus(){
        return self::getServerConfigByName('ServerOnline')->toJson();
    }

    public static function getServerConfig(){
        return ServerConfig::all();
    }

    public static function getServerConfigByName(string $Name){
        return ServerConfig::where('Name', '=', $Name)->get()[0];
    }
}
