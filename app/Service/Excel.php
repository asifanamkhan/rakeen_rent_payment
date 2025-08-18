<?php

namespace App\Service;

class Excel
{


    public static function export($output , $filename){
        return response($output)
            ->header('Content-Type', 'application/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }


}