<?php
/**
 * Converte um json para objeto
 *
 * @param string $pJson
 * @return mixed
 */
if (! function_exists('json2Object')) {
    function json2Object(string $pJson)
    {
        return json_decode($pJson);
    }
}

if (! function_exists('json2Array')) {
    function json2Array(string $pJson) : array
    {
        $obj = json2Object($pJson);
        return object2array($obj);
    }
}
