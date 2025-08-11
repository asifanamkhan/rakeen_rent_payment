<?php

namespace App\Service;

class ReportTypeCheck
{
    public static function route($route){

        if($route == 'money-receipt-print-pdf'){
            return 'SERVICE BILL';
        }

    }


}
