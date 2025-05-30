<?php

/**
 * Retorna o tamanho do arquivo.
 *
 * @param float $pBytes
 * @param integer $pDecimals
 * @return string
 */
if (! function_exists('filesize')) {
    function filesize(float $pBytes, int $pDecimals = 2): string
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($pBytes) - 1) / 3);

        return sprintf("%.{$pDecimals}f", $pBytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
