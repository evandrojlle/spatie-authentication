<?php
/**
 * Converte um array em uma lista html.
 *
 * @param array $pArray - O array.
 * @return string
 */
if (! function_exists('array2list_recursive')) {
    function array2list_recursive(array $pArray): string
    {
        $cont=0;
        $htmlList = "<ul class='list-ticked'>";
        foreach ($pArray as $key => $value) {
            $cont++;
            if (! is_array($value)) {
                $htmlList .= "<li>{$key}: {$value}</li>";
            } else {
                if (! is_numeric($key)) {
                    $htmlList .= "<li>{$key}:" . array2list_recursive($value) . "</li>";
                } else {
                    $htmlList .= array2list_recursive($value);
                }
            }

            if ($cont >= 5) {
                break;
            }
        }
        $htmlList .= "</ul>";

        return $htmlList;
    }
}

/**
 * Converte um array em uma tabela html.
 *
 * @param array $pArray - O array.
 * @return string
 */
if (! function_exists('array2table_recursive')) {
    function array2table_recursive(array $pArray): string
    {
        $htmlTable = '<table border="0" cellpadding="0" cellspacing="0" class="table-2-2">
            <tr class="head">';
                foreach ($pArray as $key => $value):
                    $htmlTable .= '<td>' . $key . '</td>';
                endforeach;
            $htmlTable .= '</tr>';

            $htmlTable .= '<tr class="body">';
                foreach ($pArray as $key => $value):
                    if (! is_array($value)):
                        $htmlTable .= '<td>' . $value . '</td>';
                    else:
                        $htmlTable .= '<td>' . array2table_recursive($value) . '</td>';
                    endif;
                endforeach;
            $htmlTable .= '</tr>';

        $htmlTable .= "</table>";

        return $htmlTable;
    }
}

/**
 * Retorna uma ultimo item do array.
 *
 * @param array $pArray - O array.
 * @return mixed
 */
if (! function_exists('last_item')) {
    function last_item(array $pArray): mixed
    {
        return end($pArray);
    }
}

/**
 * Localiza os itens do campo $pIndex e salva em um novo array.
 *
 * @param array $pArray - O array.
 * @param string $pIndex - O campo.
 * @return array
 */
if (! function_exists('concat')) {
    function concat($pArray, string $pIndex): array
    {
        $output = [];
        foreach ($pArray as $key => $value) {
            if (! is_array($value)) {
                if ($key == $pIndex) {
                    $output = $value;
                } else {
                    unset($pArray[$key]);
                }
            } else {
                $output[] = concat($value, $pIndex, $output);
            }
        }

        return $output;
    }
}

/**
 * Retorna uma array convertido em objeto recursivamente.
 *
 * @param array|object|string $pParam - O array.
 * @return \stdClass
 */
if (! function_exists('array2object')) {
    function array2object($pParam): \stdClass
    {
        if (is_array($pParam)) {
            $pParam = (object) $pParam;
        }

        $new = new \stdClass;
        if (is_object($pParam)) {
            foreach ($pParam as $key => $val) {
                $new->$key = array2object($val);
            }
        } else {
            $new = $pParam;
        }

        return $new;
    }
}

/**
 * Renomeia o campo id dentro de um array
 *
 * @param array|object $pParams - O array ou objeto.
 * @return array
 */
if (! function_exists('renameidfield')) {
    function renameidfield($pParams): array
    {
        if (is_object($pParams)) {
            $array = object2array($pParams);
        } else {
            $array = $pParams;
        }

        $arrReturn = [];
        foreach ($array as $key => $val) {
            $arr = explode('_', $key);
            $prefix = reset($arr);
            if ($prefix === 'id') {
                $sufix = ucfirst(substr($key, 3));
                $arrReturn[$prefix . $sufix] = $val;
            } else {
                $arrReturn[$key] = $val;
            }
        }

        return $arrReturn;
    }
}

/**
 * Converte um objeto em array recursivamente, se necessario.
 *
 * @param object|array $pObj - O objeto
 * @return mixed
 */
if (! function_exists('object2array')) {
    function object2array($pObj)
    {
        if (is_object($pObj)) {
            $pObj = (array) $pObj;
        }

        if (is_array($pObj)) {
            $new = [];
            foreach ($pObj as $key => $val) {
                $new[$key] = object2array($val);
            }
        } else {
            $new = $pObj;
        }

        return $new;
    }
}

/**
 * Quebra o token JWT em um array.
 *
 * @param string $pJwt - A hash JWT.
 * @return array
 */
if (! function_exists('jwt2array')) {
    function jwt2array(string $pJwt): array
    {
        return explode('.', $pJwt);
    }
}

/**
 * Faz um merge entre dois arrays, indicando a posicao onde esse novo array sera inserido.
 *
 * @param array $pArray - O array principal.
 * @param string $pPrevKey - O campo de referencia onde o array secundario sera inserido. A insercao sera feita antes desse campo.
 * @param array $pArrayAdd - O array secundario.
 * @return array
 */
if (! function_exists('put_array')) {
    function put_array(array $pArray, string $pPrevKey, array $pPutArray): array
    {
        $partial = [];
        foreach ($pArray as $key => $value) {
            if ($key != $pPrevKey) {
                $partial[$key] = $value;
            } else {
                break;
            }
        }

        $half = array_merge($partial, $pPutArray);
        $keys = array_keys($partial);
        $complement = delete_fields($pArray, $keys);
        $complete = array_merge($half, $complement);

        return $complete;
    }
}

/**
 * Remove os campos do primeiro array indicado no segundo array.
 *
 * @param array $pArray - O array.
 * @param array $pFields - Os campos que serao removidos.
 * @return array
 */
if (! function_exists('delete_fields')) {
    function delete_fields(array &$pArray, array $pFields): array
    {
        $arrKey = array_keys($pArray);
        foreach ($pFields as $field) {
            if (in_array($field, $arrKey)) {
                unset($pArray[$field]);
            }
        }

        return $pArray;
    }
}

/**
 * Realiza a comparacao entre dois arrays e retorna a igualdade entre eles de forma recursiva.
 *
 * @param array $pArray1 - O primeiro array
 * @param array $pArray2 - O segundo array
 * @return array
 */
if (! function_exists('array_diff')) {
    function array_diff(array $pArray1, array $pArray2) : array
    {
        $new = [];
        $keysArr2 = array_keys($pArray2);
        foreach ($pArray1 as $key => $val) {
            if (! in_array($key, $keysArr2)) {
                $new[$key] = $val;
            } else {
                if (is_array($val)) {
                    array_diff($val, $pArray2[$key]);
                }
            }
        }

        return $new;
    }
}

/**
 * Funde dois arrays
 *
 * @param array $pArray1 - O primeiro array
 * @param array $pArray2 - O segundo array
 * @return array
 */
if (! function_exists('merge')) {
    function merge(array &$pArray1, array $pArray2)
    {
        return $pArray1 = array_merge($pArray1, $pArray2);
    }
}

/**
 * Remove itens duplidados em um array
 *
 * @param array $pArray - O array
 * @return array
 */
if (! function_exists('arrayunique')) {
    function arrayunique(array &$pArray)
    {
        return $pArray = array_unique($pArray);
    }
}

/**
 * Converte um array em string.
 *
 * @param string $pGlue - parametro concatenador. Realiza a juncao entre os itens do array.
 * @param array $pPieces - O array.
 * @return string
 */
if (! function_exists('paste')) {
    function paste(string $pGlue, array $pPieces) : string
    {
        return implode($pGlue, $pPieces);
    }
}

/**
 * Converte uma string em array.
 *
 * @param string $pDelimiter
 * @param string $pString
 * @param integer $pLimit
 * @return array
 */
if (! function_exists('pieces')) {
    function pieces(string $pDelimiter, string $pString, int $pLimit = PHP_INT_MAX) : array
    {
        return explode($pDelimiter, $pString, $pLimit);
    }
}

/**
 * Retorna o valor em uma determinada posicao do array.
 *
 * @param array $pArray - O array.
 * @param integer $pPosition - Aposicao a ser retornada.
 * @return string
 */
if (! function_exists('array_position')) {
    function array_position(array $pArray, int $pPosition = 0) : string
    {
        if ($pPosition === 0) {
            return reset($pArray);
        } elseif ($pPosition === count($pArray)) {
            return end($pArray);
        } else {
            return $pArray[$pPosition];
        }
    }
}

/**
 * Realiza um filtro em um array.
 *
 * @param array $pArray - O array.
 * @param string $pIndex - O campo de busca.
 * @param string $pValue - O valor para o filtro.
 * @param string [$pOperator] - O operador. Pode ser LIKE ou operador = (igual)
 * @return array
 */
if (! function_exists('array_filters')) {
    function array_filters(array $pArray, string $pIndex, string $pValue, string $pOperator = '') : array
    {
        $newArray = [];
        if (is_array($pArray) && count($pArray) > 0) {
            foreach ($pArray as $key => $val) {
                if ($pOperator == 'LIKE') {
                    $valueSlashed = str_replace('/', '\/', $pValue);
                    preg_match("/{$valueSlashed}/", $pArray[$key][$pIndex], $matches, PREG_OFFSET_CAPTURE);
                    if ($matches) {
                        $newArray[$key] = $pArray[$key];
                    }
                } else {
                    if ($pArray[$key][$pIndex] == $pValue) {
                        $newArray[$key] = $pArray[$key];
                    }
                }
            }
        }

        return $newArray;
    }
}

/**
 * Ordena um array por um campo especifico mantendo as associacoes.
 *
 * @param array $pArray - O array.
 * @param string $pOn - Campo de Ordenacao.
 * @param int $pOrder - Direcao da Ordenacao, podendo ser: SORT_ASC ou SORT_DESC.
 * @return void
 */
if (! function_exists('array_sort')) {
    function array_sort(array $pArray, string $pOn, int $pOrder = SORT_ASC) : array
    {
        $newArray = [];
        $sortableArray = [];

        if (count($pArray) > 0) {
            foreach ($pArray as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $pOn) {
                            $sortableArray[$k] = $v2;
                        }
                    }
                } else {
                    $sortableArray[$k] = $v;
                }
            }

            switch ($pOrder) {
                case SORT_ASC:
                    asort($sortableArray);
                    break;
                case SORT_DESC:
                    arsort($sortableArray);
                    break;
            }

            foreach ($sortableArray as $k => $v) {
                $newArray[$k] = $pArray[$k];
            }
        }

        return $newArray;
    }
}

/**
 * Aplica uma funcao em todos os elementos do array
 *
 * @param callable $pCallback - Funcao callback para executar para cada elemento do array.
 * @param string $pOn - Um array para percorrer chamando a funcao callback.
 * @return array
 */
if (! function_exists('mapping')) {
    function mapping(callable $pCallback, array &$pArray)
    {
        return $pArray = array_map($pCallback, $pArray);
    }
}

/**
 * Converte uma string CamelCase em array.
 *
 * @param string $pStrCamelCase
 * @return array
 */
if (! function_exists('camelcase_explode')) {
    function camelcase_explode(string $pStrCamelCase) : array
    {
        return preg_split("/(?<=\\w)(?=[A-Z])/", $pStrCamelCase);
    }
}

/**
 * Remove os itens duplicados em uma matriz.
 *
 * @param array $pArray - O array
 * @param string $pKey - O campo.
 * @return array
 */
if (! function_exists('array_unique_recursive')) {
    function array_unique_recursive(array $pArray, string $pKey): array
    {
        $arrTemp = [];
        $i = 0;
        $arrKeys = [];

        foreach ($pArray as $val) {
            if (! in_array($val[$pKey], $arrKeys)) {
                $arrKeys[$i] = $val[$pKey];
                $arrTemp[$i] = $val;
            }
            $i++;
        }

        return $arrTemp;
    }
}

/**
 * Verifica se a valor esta no array.
 *
 * @param array $pArray
 * @return int
 */
if (! function_exists('inarray')) {
    function inarray(string $pData, array $pArray) : bool
    {
        if (in_array($pData, $pArray)) {
            return true;
        }

        return false;
    }
}

/**
 * Calcula a quantidade de itens no array.
 *
 * @param array $pArray
 * @return int
 */
if (! function_exists('array_count')) {
    function array_count($pArray) : int
    {
        return count($pArray);
    }
}

/**
 * Remove os valores do primeiro array indicado no segundo array.
 *
 * @param array $pArray - O array.
 * @param array $pValues - Os valores que serao removidos.
 * @return array
 */
if (! function_exists('delete_values')) {
    function delete_values(array &$pArray, array $pValues): array
    {
        foreach ($pArray as $key => $value) {
            if (in_array($value, $pValues)) {
                unset($pArray[$key]);
            }
        }

        return $pArray;
    }
}
