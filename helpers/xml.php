<?php
/**
 * Converte um XML para SimpleXMLElement.
 *
 * @param string $pXml
 * @return \SimpleXMLElement
 */
if (! function_exists('xml2object')) {
    function xml2object(string $pXml): \SimpleXMLElement
    {
        $object = simplexml_load_string($pXml);

        return $object;
    }
}

/**
 * Converte um XML para array.
 *
 * @param string $pXml
 * @return array
 */
if (! function_exists('xml2array')) {
    function xml2array(string $pXml): array
    {
        $object = xml2object($pXml);
        $array  = object2array($object);

        return $array;
    }
}

/**
 * Converte um XML para json.
 *
 * @param string $pXml
 * @return string
 */
if (! function_exists('xml2json')) {
    function xml2json(string $pXml): string
    {
        $object = xml2object($pXml);
        $json = json_encode($object);

        return $json;
    }
}
