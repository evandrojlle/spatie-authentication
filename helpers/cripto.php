<?php

/**
 * Codifica dados com MIME base64.
 * Esta codificacao he designada para que dados binarios durem no transporte sobre camadas
 * de transporte que nao sao 8-bit clean, como mensagens de e-mail.
 * Dados codificados em Base-64 tem aproximadamente 33% mais espaco que dos dados originais.
 * Retorna a informação codificada, como uma string.
 *
 * @param mixed $pEncode - Dados a serem codificados
 * @param int $pQtdLoop - Quantidade de vezes que deve ser codificado
 * @return string
 */
if (! function_exists('encript')) {
    function encript(mixed $pEncode, int $pQtdLoop = 10): string
    {
        if (is_array($pEncode)) {
            $pEncode = json_encode($pEncode);
        }

        $h = base64_encode(SALTAPI);
        $p = base64_encode($pEncode);
        $s = base64_encode(date('YmdHis'));
        
        $string = "{$h};{$p};{$s}";
        for ($i = 0; $i <= $pQtdLoop; $i++) {
            $string = base64_encode($string);
        }

        return $string;
    }
}

/**
 * Decodifica dados codificados com MIME base64.
 * Retorna a informação original ou FALSE em falha.
 * O dado retornado pode ser binário.
 *
 * @param string $pEncripted - String a ser decodificada.
 * @param int $pQtdLoop - Quantidade de vezes que deve ser decodificado. Deve ser a mesma quantidade de quando foi codificado.
 * @return string|array|false
 */
if (! function_exists('decript')) {
    function decript(string $pEncripted, int $pQtdLoop = 10): string|array|false
    {
        $stepOne = $pEncripted;
        for ($i = 0; $i <= $pQtdLoop; $i++) {
            $stepOne = base64_decode($stepOne);
        }

        if (! $stepOne) {
            return false;
        }

        $stepTwo = pieces(';', $stepOne);
        if (! $stepTwo) {
            return false;
        }

        $stepThree = mapping('base64_decode', $stepTwo);
        if (! $stepThree) {
            return false;
        }

        if (! $stepThree[1]) {
            return false;
        }

        if (is_json($stepThree[1])) {
            $stepFour = json2Array($stepThree[1]);
            if (! $stepFour) {
                return false;
            }

            return $stepFour;
        }

        return $stepThree[1];
    }
}

/**
 * Decodifica uma senha encriptada por Strings::encript($str).
 * Retorna a senha decriptada.
 *
 * @var string
 * @param string $pHash
 * @return string
 */
if (! function_exists('decript_pass')) {
    function decript_pass(string $pHash): string
    {
        $str = decript($pHash);
        $arr = explode(';', $str);
        $pass = $arr[1];

        return $pass;
    }
}

/**
 * Serializa e codifica um objeto em uma hash base64.
 *
 * @param object $pObject
 * @return string
 */
if (! function_exists('object_encript')) {
    function object_encript(object $pObject): string
    {
        $serializedObject = serialize($pObject);
        $string = base64_encode($serializedObject);

        return $string;
    }
}

/**
 * Faz o processo inverso de encriptObject(object $pObject).
 * Decodifica e deserializa uma hash base64 em objeto.
 * Esse processo funcionará somente se o objeto foi encriptado pela função encriptObject
 *
 * @param string $pString
 * @return object
 */
if (! function_exists('object_decript')) {
    function object_decript(string $pString): object
    {
        $decryptedString = base64_decode($pString);
        $object = unserialize($decryptedString);

        return $object;
    }
}
