<?php

use Google\Client;
use Illuminate\Support\Facades\Route;

/**
 *
 * Refer to this url for all details
 * https://github.com/googleapis/google-api-php-client/blob/master/examples/simple-file-upload.php
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/drive', function () {
    $client = new Client();
    $client->setClientId('673196264301-65ibo2q7mg0njs31e6051hmdulsej0er.apps.googleusercontent.com');
    $client->setClientSecret('_fh9LWLk8cH1r1_1KllIuH-t');
    $client->setRedirectUri('http://localhost:8000/google-drive/callback');
    $client->setScopes([
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ]);
    $url = $client->createAuthUrl();
    return $url;
});

Route::get('/google-drive/callback', function () {
    return request('code');
    // $client = new Client();
    // $client->setClientId('673196264301-65ibo2q7mg0njs31e6051hmdulsej0er.apps.googleusercontent.com');
    // $client->setClientSecret('_fh9LWLk8cH1r1_1KllIuH-t');
    // $client->setRedirectUri('http://localhost:8000/google-drive/callback');
    // $code = request('code');
    // $access_token = $client->fetchAccessTokenWithAuthCode($code);
    // return $access_token;
});

Route::get('upload', function () {
    $client = new Client();
    $access_token = 'ya29.a0ARrdaM8zhppotaylqPVBXM1SirlULNGgjhV6SzXODpR30nVwjreCmSueTHmB_M41wVMpCuecnKud8sIxk6TwCVJUtD7kJYrriCLDcassSozlzePscFtkZx16A8Gkvn__mQU0s-1m3UtLrhdC6KS29_7SwTTX';

    $client->setAccessToken($access_token);
    $service = new Google\Service\Drive($client);
    $file = new Google\Service\Drive\DriveFile();

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
});
