<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\WebService;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

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
        // we need to fetch last 7 days of tasks
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();
        // create a json file with this data
        $jsonFileName = 'task_dump.json';
        Storage::put("/public/temp/$jsonFileName", $tasks->toJson());
        // create a zip file with this json file
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/temp/' . now()->timestamp . '-task.zip');

        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            $filePath = storage_path('app/public/temp/' . $jsonFileName);
            $zip->addFile($filePath);
        }
        $zip->close();
        // send this zip to drive

        $access_token = $web_service->token['access_token'];
        $client->setAccessToken($access_token);

        $service = new Drive($client);
        $file = new DriveFile();

        $file->setName("Hello World!");
        $service->files->create(
            $file,
            array(
                'data' => file_get_contents($zipFileName),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            )
        );

        return response('Uploaded', Response::HTTP_CREATED);
    }
}
