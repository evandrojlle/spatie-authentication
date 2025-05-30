<?php
/**
 * Retorna o charset encoding Padrão
 *
 * @return string
 */
if (! function_exists('get_encoding')) {
    function get_encoding(): string
    {
        return ENCODING;
    }
}

/**
 * Retorna lista de encodings suportados
 *
 * @return array
 */
if (! function_exists('encode_list')) {
    function encode_list($pParam)
    {
        return mb_list_encodings();
    }
}

/**
 * Retorna um array ou string traduzidos para UTF8.
 *
 * @param array|string $pParam
 * @return array|string|false
 */
if (! function_exists('utf8_encoder')) {
    function utf8_encoder($pParam)
    {
        $toEncoding = 'UTF-8';
        $fromEncoding = 'ISO-8859-1';
        if (is_array($pParam) || is_object($pParam)) {
            foreach ($pParam as &$param) {
                $param = \UConverter::transcode($param, $toEncoding, $fromEncoding);
            }

            return $pParam;
        }
                
        return \UConverter::transcode($pParam, $toEncoding, $fromEncoding);
    }
}

/**
 * Retorna um array ou string traduzidos para ISO-8859-1.
 *
 * @param array|string $pParam
 * @return array|string|false
 */
if (! function_exists('utf8_decoder')) {
    function utf8_decoder($pParam)
    {
        $toEncoding = 'ISO-8859-1';
        $fromEncoding = 'UTF-8';
        if (is_array($pParam) || is_object($pParam)) {
            foreach ($pParam as &$param) {
                $param = \UConverter::transcode($param, $toEncoding, $fromEncoding);
            }

            return $pParam;
        }

        return \UConverter::transcode($pParam, $toEncoding, $fromEncoding);
    }
}

/**
 * Percorre uma lista e traduz recursivamente cada item para UTF8.
 *
 * @param array $pParams
 * @return array
 */
if (! function_exists('scroll_list_utf8_encode')) {
    function scroll_list_utf8_encode(array $pParams): array
    {
        $new = [];
        $toEncoding = 'UTF-8';
        $fromEncoding = 'ISO-8859-1';
        foreach ($pParams as $key => $val) {
            if (! is_array($val)) {
                $new[$key] = \UConverter::transcode($val, $toEncoding, $fromEncoding);
            } else {
                $new[$key] = utf8_encode_recursive($val);
            }
        }

        return $new;
    }
}

/**
 * Retorna um array ou string traduzidos recursivamente para UTF8.
 *
 * @param array|string $pParam
 * @return array|string
 */
if (! function_exists('utf8_encode_recursive')) {
    function utf8_encode_recursive($pParam)
    {
        if (is_array($pParam)) {
            return scroll_list_utf8_encode($pParam);
        } elseif (is_object($pParam)) {
            $pParam = object2array($pParam);

            return scroll_list_utf8_encode($pParam);
        }

        return utf8_encoder($pParam);
    }
}

/**
 * Percorre uma lista e traduz recursivamente cada item para ISO-8859-1.
 *
 * @param array $pParams
 * @return array
 */
if (! function_exists('scroll_list_utf8_decode')) {
    function scroll_list_utf8_decode(array $pParams): array
    {
        $new = [];
        $toEncoding = 'ISO-8859-1';
        $fromEncoding = 'UTF-8';
        foreach ($pParams as $key => $val) {
            if (! is_array($val)) {
                $new[$key] = \UConverter::transcode($val, $toEncoding, $fromEncoding);
            } else {
                $new[$key] = utf8_decode_recursive($val);
            }
        }

        return $new;
    }
}

/**
 * Retorna um array ou string traduzidos recursivamente para ISO-8859-1.
 *
 * @param array|string $pParam
 * @return array|string
 */
if (! function_exists('utf8_decode_recursive')) {
    function utf8_decode_recursive($pParam)
    {
        if (is_array($pParam)) {
            return scroll_list_utf8_decode($pParam);
        } elseif (is_object($pParam)) {
            $pParam = object2array($pParam);

            return scroll_list_utf8_decode($pParam);
        }

        return utf8_decoder($pParam);
    }
}
