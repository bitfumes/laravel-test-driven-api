<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\WebService;
use App\Services\GoogleDrive;
use App\Services\Zipper;
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
            'token' => $access_token,
            'name' => 'google-drive'
        ]);
        return $service;
    }

    public function store(WebService $web_service, GoogleDrive $drive)
    {
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();
        $jsonFileName = 'task_dump.json';
        Storage::put("/public/temp/$jsonFileName", TaskResource::collection($tasks)->toJson());

        $zipFileName = Zipper::createZipOf($jsonFileName);

        $access_token = $web_service->token['access_token'];
        $drive->uploadFile($zipFileName, $access_token);

        Storage::deleteDirectory('public/temp');
        return response('Uploaded', Response::HTTP_CREATED);
    }
}
