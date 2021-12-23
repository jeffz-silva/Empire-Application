<?php

namespace App\Http\Controllers;

use App\Models\UserDetailInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamePlayerController extends Controller
{
    //

    public static function ApplicationGetUserCharacters(Request $request){
        return self::getUserDetailInfos($request->get('Username'))->toJson();
    }

    public static function ApplicationGetUserCharacter(Request $request){
        return self::getUserDetailInfoById($request->get('UserID'))->toJson();
    }

    public static function getUserDetailInfoById(int $Id){
        return UserDetailInfo::select('UserID', 'Username', 'NickName', 'Grade', 'FightPower', "IsStaff")->where('UserID', '=', $Id)->get();
    }

    public static function getUserDetailInfos(string $Username){
        return UserDetailInfo::select('UserID', 'Username', 'NickName', 'Grade', 'FightPower', 'IsStaff')->where('UserName', '=', $Username)->get();
    }

    public static function getUserDetailInfoByNickname(string $NickName){
        return UserDetailInfo::select('UserID', 'Username', 'NickName', 'Grade', 'FightPower', 'IsStaff')->where('NickName', '=', $NickName)->get();
    }

    public static function createPlayerCharacter(Request $request){
        $UserName = strtolower($request->get('UserName'));
        $NickName = $request->get('NickName');
        $Password = $request->get('Password');
        $Sex = $request->get('Sex');

        if(self::getUserDetailInfos($UserName)->count() != 0)
            return json_encode(['IsCreated' => false, 'Message' => 'Você já tem um personagem nesse servidor!']);

        if(self::getUserDetailInfoByNickname($NickName)->count() != 0)
            return json_encode(['IsCreated' => false, 'Message' => 'Nome de personagem já existente!']);

        DB::connection('sqlsrv')->statement("EXEC SP_Users_Active @UserID='',@Attack=0,@Colors=N',,,,,,',@ConsortiaID=0,@Defence=0,@Gold=100000,@GP=1437053,@Grade=25,@Luck=0,@Money=0,@Style=N',,,,,,',@Agility=0,@State=0,@UserName=N'{$UserName}',@PassWord=N'{$Password}',@Sex='{$Sex}',@Hide=1111111111,@ActiveIP=N'',@Skin=N'',@Site=N''");
        switch($Sex){
            case 0:
                DB::connection('sqlsrv')->statement("EXEC SP_Users_RegisterNotValidate @UserName=N'{$UserName}',@PassWord=N'{$Password}',@NickName=N'{$NickName}',@BArmID=7008,@BHairID=3244,@BFaceID=6204,@BClothID=5276,@BHatID=1214,@GArmID=7008,@GHairID=3244,@GFaceID=6202,@GClothID=5276,@GHatID=1214,@ArmColor=N'',@HairColor=N'',@FaceColor=N'',@ClothColor=N'',@HatColor=N'',@Sex='{$Sex}',@StyleDate=0");
                break;
            case 1:
                DB::connection('sqlsrv')->statement("EXEC SP_Users_RegisterNotValidate @UserName=N'{$UserName}',@PassWord=N'{$Password}',@NickName=N'{$NickName}',@BArmID=7008,@BHairID=3158,@BFaceID=6103,@BClothID=5160,@BHatID=1142,@GArmID=7008,@GHairID=3158,@GFaceID=6103,@GClothID=5160,@GHatID=1142,@ArmColor=N'',@HairColor=N'',@FaceColor=N'',@ClothColor=N'',@HatColor=N'',@Sex='{$Sex}',@StyleDate=0");
                break;
        }
        DB::connection('sqlsrv')->statement("EXEC SP_Users_LoginWeb @UserName=N'{$UserName}',@Password=N'',@FirstValidate=0,@NickName=N'{$NickName}'");

        return json_encode(['IsCreated' => true, "Message" => "Personagem cadastrado com sucesso!"]);
    }
}
