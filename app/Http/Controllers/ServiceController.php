<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ];

    public function connect(Request $request)
    {
        if ($request->service === 'google-drive') {
            $client = new Client();

            $config = config('services.google');
            $client->setClientId($config['id']);
            $client->setClientSecret($config['secret']);
            $client->setRedirectUri($config['redirect_url']);

            $client->setScopes(self::DRIVE_SCOPES);

            $url = $client->createAuthUrl();
            return response(['url' => $url]);
        }
    }
}
