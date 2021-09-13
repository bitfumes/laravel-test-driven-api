<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebServiceController extends Controller
{
    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ];

    public function connect($name, Client $client)
    {
        if ($name === 'google-drive') {
            $client->setScopes(self::DRIVE_SCOPES);
            $url = $client->createAuthUrl();
            return response(['url' => $url]);
        }
    }

    public function callback(Request $request, Client $client)
    {
        $access_token = $client->fetchAccessTokenWithAuthCode($request->code);

        $service = WebService::create([
            'user_id' => auth()->id(),
            'token' => json_encode(['access_token' => $access_token]),
            'name' => 'google-drive'
        ]);
        return $service;
    }

    public function store(Request $request, WebService $web_service, Client $client)
    {
        $access_token = $web_service->token['access_token'];
        $client->setAccessToken($access_token);

        $service = new Drive($client);
        $file = new DriveFile();

        DEFINE("TESTFILE", 'testfile-small.txt');
        if (!file_exists(TESTFILE)) {
            $fh = fopen(TESTFILE, 'w');
            fseek($fh, 1024 * 1024);
            fwrite($fh, "!", 1);
            fclose($fh);
        }

        $file->setName("Hello World!");
        $service->files->create(
            $file,
            array(
                'data' => file_get_contents(TESTFILE),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );

        return response('Uploaded', Response::HTTP_CREATED);
    }
}
