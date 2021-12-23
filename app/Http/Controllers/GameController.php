<?php

namespace App\Http\Controllers;

use ErrorException;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GameController extends Controller
{
    //
    public static function AuthPlayer(Request $request)
    {
        $ZoneID = $request->get('id');
        $Username = $request->get('username');
        $Key = $request->get('key');

        if ($Key != env('GAME_KEY'))
            return ['message' => 'Chave invalida'];

        return self::IsEnableLogin($Username, $ZoneID);
    }

    public static function IsEnableLogin(string $Username, int $ZoneID)
    {
        try {
            $Quest = env('GAME_QUEST');
            $Version = env('GAME_VERSION');
            $AuthKey = env('GAME_AUTH_KEY');
            $Flash = env('GAME_FLASH');
            $Resource = env('GAME_RESOURCE');
            $Language = env('GAME_LANGUAGE');

            $AuthPublicKey = rand(111111, 999999);
            $AuthAccessTime = time();

            if (empty($Quest) || empty($Version) || empty($AuthKey) || empty($ZoneID))
                throw new ErrorException("Não foi configurado um servidor de destino.");

            $AuthParams = strtolower($Username) . "|" . $AuthPublicKey . "|" . $AuthAccessTime . "|" . md5(strtolower($Username) . strtoupper($AuthPublicKey) . $AuthAccessTime . $AuthKey);

            $client = new Client(['verify' => false]);

            $request = $client->request('GET', $Quest . "/CreateLogin.aspx", [
                'query' => ['content' => $AuthParams]
            ]);

            $streamData = $request->getBody()->getContents();
            if ($streamData != "0")
                return json_encode(['message' => 'falha no login']);

            $Game = [
                'ID' => $ZoneID,
                'Quest' => $Quest,
                'Flash' => $Flash,
                'Resource' => $Resource,
                'Language' => $Language
            ];

            return json_encode([
                'PlayerCharacters' => GamePlayerController::getUserDetailInfos($Username),
                'Game' => $Game,
                'GameHash' => self::base64_url_encode(json_encode($Game)),
                'Version' => $Version,
                'PublicKey' => $AuthPublicKey,
                'CreateKeyTime' => $AuthAccessTime,
                'Code' => $streamData,
                'Status' => true
            ]);
        } catch (Exception $ex) {
            return json_encode(['message' => 'O servidor retornou um erro!', 'error' => $ex->getMessage()]);
        }
    }

    private static function base64_url_encode($val)
    {
        return strtr(base64_encode($val), '=', '[');
    }
}
