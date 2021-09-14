<?php

namespace App\Services;

use ZipArchive;

class Zipper
{
    public static function createZipOf($filename)
    {
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/temp/' . now()->timestamp . '-task.zip');

        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            $filePath = storage_path('app/public/temp/' . $filename);
            $zip->addFile($filePath, $filename);
        }
        $zip->close();

        return $zipFileName;
    }
}
