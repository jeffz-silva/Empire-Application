<?php

namespace App\Http\Controllers;

use App\Models\ConsortiaInfo;
use App\Models\UserDetailInfo;
use Illuminate\Http\Request;

class PlayersController extends Controller
{
    //
    public static function getRankingPlayers(){
        return UserDetailInfo::select('UserName', 'NickName', 'FightPower')->orderBy('FightPower', 'DESC')->take(10)->get()->toJson();
    }

    public static function getRankingConsortias(){
        return ConsortiaInfo::select('ConsortiaName', 'FightPower')->orderBy('FightPower', 'DESC')->take(10)->get()->toJson();
    }
}
