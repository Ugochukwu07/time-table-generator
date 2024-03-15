<?php
namespace App\Helpers;

use App\Models\File;

class FileHelper {
    public function newFile(String $from, String $location, String $filename):bool
    {
        $file = File::create([
            'from' => $from,
            'uploader_id' => auth()->user()->id,
            'uploader_role' => auth()->user()->type,
            'location' => $location,
            'filename' => $filename
        ]);

        if($file){
            return true;
        }
        return false;
    }
}
