<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log as FacadesLog;

trait Log
{
    public static function save(string $pType, \Exception $pException)
    {
        $message = "{$pType}: {$pException->getMessage()}. File: {$pException->getFile()}. Line: {$pException->getLine()}";
        if (getenv('APP_ENV') != 'production' && $pType === 'error') {
            dd($message);
        }

        FacadesLog::$pType($message);
    }
}