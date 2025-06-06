<?php
const STOP_WORDS_LIST = [
    'o',
    'a',
    'os',
    'as',
    'um',
    'uma',
    'uns',
    'umas',
    'de',
    'da',
    'para',
    'pra',
    'com',
    'como',
    'em',
    'até',
    'ate',
    'por'
];

/**
 * Remove os caracteres acentuados.
 *
 * @param string $pString
 * @return string|
 */
 if (! function_exists('remove_accents')) {
    function remove_accents(string $pString): ?string
    {
        return preg_replace('/[`^~\'"ç]/', 'null', iconv('UTF-8', 'ASCII//TRANSLIT', $pString));
    }
 }

/**
 * Remove palavras pequenas de uma frase.
 *
 * @param string $pString
 * @return string
*/
if (! function_exists('remove_short_words_like_search')) {
    function remove_short_words_like_search(string $pString): string
    {
        $arrWords = explode(' ', $pString);
        foreach ($arrWords as $keyWord => $word) {
            if (strlen($word) < 3) {
                unset($arrWords[$keyWord]);
            }
        }

        $string = implode('% %', $arrWords);

        return $string;
    }
}

/**
 * Cria uma slug a partir de uma frase.
 *
 * @param string $pString
 * @return string
 */
if (! function_exists('create_slug')) {
    function create_slug(string $pString): string
    {
        $string = remove_accents($pString);
        $string = strtolower($string);
        $slug = preg_replace('/ /', '-', $string);
        $slug = remove_short_words_Slug($slug);

        return $slug;
    }
}

/**
 * Remove  as stop words encontradas na slug.
 *
 * @param string $pSlug
 * @return string
 */
if (! function_exists('remove_stop_words')) {
    function remove_stop_words(string $pSlug): string
    {
        $slug = explode('-', $pSlug);
        foreach ($slug as $k => $value) {
            // lista de Stop Words que serão removidas
            $keys = STOP_WORDS_LIST;
            foreach ($keys as $wordRemove) {
                if ($value == $wordRemove) {
                    unset($slug[$k]);
                }
            }
        }

        return implode('-', $slug);
    }
}

/**
 * Remove palavras pequenas de uma slug.
 *
 * @param string $pSlug
 * @return string
 */
if (! function_exists('remove_short_words_Slug')) {
    function remove_short_words_Slug(string $pSlug): string
    {
        $slug = explode('-', $pSlug);
        foreach ($slug as $key => $value) {
            if (strlen($value) < 3) {
                unset($slug[$key]);
            }
        }

        return implode('-', $slug);
    }
}

/**
 * Retorna a string Montada no padrão camelCase.
 *
 * @param string $pParamName
 * @param string $pSeparator
 * @return string
 */
if (! function_exists('tocamelcase')) {
    function tocamelcase(string $pParamName, string $pSeparator = "_"): string
    {
        $array = explode($pSeparator, $pParamName);
        $nArray = [];
        foreach ($array as $key => $value) {
            if ($key > 0) {
                $str = ucfirst($value);
            } else {
                $str = $value;
            }
            $nArray[] = $str;
        }

        return implode('', $nArray);
    }
}

/**
 * Retorna a string Montada no padrão PascalCase.
 *
 * @param string $pParamName
 * @param string $pSeparator
 * @return string
 */
if (! function_exists('topascalcase')) {
    function topascalcase(string $pParamName, string $pSeparator = "_"): string
    {
        $array = explode($pSeparator, $pParamName);
        if (count($array) === 1) {
            $array = array_map('strtolower', $array);
        }

        $nArray = [];
        foreach ($array as $value) {
            $str = ucfirst($value);
            $nArray[] = $str;
        }

        return implode('', $nArray);
    }
}

/**
 * Retorna uma string randomica.
 *
 * @param integer $pLenght
 * @param boolean $pUseSalt
 * @param string $pSalt
 * @return string
 */
if (! function_exists('random_string')) {
    function random_string(int $pLenght = 10, bool $pUseSalt = true, string $pSalt = 'flagFast'): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($chars);
        $random = '';
        for ($offset = 0; $offset < $pLenght; $offset ++) {
            $rand = rand(0, $len - 1);
            $random .= $chars[$rand];
        }

        $time = time();
        $random .= ($pUseSalt === true) ? "{$pSalt}{$time}" : $time;

        return str_shuffle($random);
    }
}

/**
 * Retorna um hex randomica.
 *
 * @param integer $pLenght
 * @param boolean $pUseSalt
 * @param string $pSalt
 * @return string
 */
if (! function_exists('random_hex')) {
    function random_hex(int $pLenght = 10, bool $pUseSalt = true, string $pSalt = 'x46x6Cx61x67x66x61x73x74'): string
    {
        $chars = '0123456789abcdef';
        $len   = strlen($chars);
        $random = '';
        for ($offset = 0; $offset < $pLenght; $offset ++) {
            $rand = rand(0, $len - 1);
            $random .= $chars[$rand];
        }

        if (($pUseSalt === true)) {
            $random .= $pSalt;
        }

        return str_shuffle($random);
    }
}

/**
 * Retorna um hex randomica com separadores.
 *
 * @param integer $pLenght
 * @param boolean $pUseSalt
 * @param string $pSalt
 * @return string
 */
if (! function_exists('randomic_hex')) {
    function randomic_hex(int $pLenght = 10, bool $pUseSalt = true, string $pSalt = 'x46x6Cx61x67x66x61x73x74')
    {
        $str = random_hex($pLenght, $pUseSalt, $pSalt);
        if ($pUseSalt === false) {
            $pattern = '/([a-f0-9]{8})([a-f0-9]{4})([a-f0-9]{4})([a-f0-9]{4})([a-f0-9]{12})/';
            $str = preg_replace($pattern, "\$1-\$2-\$3-\$4-\$5", $str);
        }

        return $str;
    }
}

/**
 * Adiciona aspas simples ou duplas na string.
 *
 * @param string $pString
 * @param string $pType
 * @return string
 */
if (! function_exists('add_quotes')) {
    function add_quotes(string $pString, string $pType = 'SINGLE'): string
    {
        $string = $pType === 'SINGLE' ? "'{$pString}'" : "\"{$pString}\"";

        return $string;
    }
}

/**
 * Método replace_special_char - Substritui os caracteres especiais da string por um caractere semelhante, ou sem o acento.
 *
 * @param $string - string a ser substituida.
 * @return string.
 */
if (! function_exists('replace_special_char')) {
    function replace_special_char(string $pString): string
    {
        $chars = preg_replace("/(([ãáàâ]))/", "a", $pString);
        $chars = preg_replace("/(([êéè]))/", "e", $pString);
        $chars = preg_replace("/(([íìî]))/", "i", $pString);
        $chars = preg_replace("/(([óòõô]))/", "o", $pString);
        $chars = preg_replace("/(([ùúû]))/", "u", $pString);
        $chars = preg_replace("/(([ÃÁÀÂ]))/", "A", $pString);
        $chars = preg_replace("/(([ÊÉÈ]))/", "E", $pString);
        $chars = preg_replace("/(([ÍÌÎ]))/", "I", $pString);
        $chars = preg_replace("/(([ÓÒÕÔ]))/", "O", $pString);
        $chars = preg_replace("/(([ÙÚÛ]))/", "U", $pString);
        $chars = preg_replace("/(([º]))/", "o", $pString);
        $chars = preg_replace("/(([ª]))/", "a", $pString);
        $chars = preg_replace("/(([ç]))/", "c", $pString);
        $chars = preg_replace("/(([Ç]))/", "C", $pString);
        $chars = preg_replace("/(([&]))/", "e", $pString);
        $chars = preg_replace("/(([$]))/", "S", $pString);
        $chars = preg_replace("/(([%]))/", "0/0", $pString);
        $chars = preg_replace("/(([*]))/", "x", $pString);
        $chars = preg_replace("/(([¨]))/", "..", $pString);
        $chars = preg_replace("/(([@]))/", "a", $pString);
        $chars = preg_replace("/(([ ]))/", "_", $pString);

        return $chars;
    }
}

/**
 * Substitui todas as ocorrencias da string pesquisada pela string de substrituicao.
 *
 * @param string $pSearch - A string para pesquisa.
 * @param string $pReplace - A string para substrituicao.
 * @param string $pSubject - A string a ser analisada.
 * @return string
 */
if (! function_exists('replace')) {
    function replace(string $pSearch, string $pReplace, string $pSubject)
    {
        return str_replace($pSearch, $pReplace, $pSubject);
    }
}

/**
 * Quebra a string CamelCase adicionando um espaço ou outro caracter entre as palavras.
 *
 * @param string $pStrCamelCase - A palavra CamelCase.
 * @param string $pGlue - O separador. O padrão é um espaço.
 * @return string
 */
if (! function_exists('spacify')) {
    function spacify(string $pStrCamelCase, string $pGlue = ' '): string
    {
        return preg_replace('/([a-z0-9])([A-Z])/', "$1$pGlue$2", $pStrCamelCase);
    }
}

/**
 * Converte uma string para letras minusculas.
 *
 * @param string $pString
 * @return void
 */
if (! function_exists('tolower')) {
    function tolower(string &$pString)
    {
        $pString = strtolower($pString);
    }
}

/**
 * Converte uma string para letras maiusculas.
 *
 * @param string $pString
 * @return void
 */
if (! function_exists('toupper')) {
    function toupper(string &$pString): void
    {
        $pString = strtoupper($pString);
    }
}

/**
 * Adiciona a mascara do CPF.
 *
 * @param string $pString
 * @return void
 */
if (! function_exists('format_cpf')) {
    function format_cpf(string $pCpf): string
    {
        $length = strlen($pCpf);
        if ($length === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $pCpf);
        } 
          
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $pCpf);
    }
}

/**
 * Remove a mascara do CPF.
 *
 * @param string $pString
 * @return void
 */
if (! function_exists('unformat_document')) {
    function unformat_document(string $pDocument): string
    {
        $document = str_replace(['.', '-', '/', ''], '', $pDocument);

        return $document;
    }
}

if ( ! function_exists('is_json')) {
    function is_json (string $pJson): bool
    {
        $pattern = '^[{\[]{1}([,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]|".*?")+[}\]]{1}$';
        $preg = pregmatch($pattern, $pJson);

        return $preg ? true : false;
    }
}

/**
 * Adiciona a mascara do Telefone.
 *
 * @param string $pString
 * @return void
 */
if (! function_exists('format_phone')) {
    function format_phone(string $pPhone): string
    {
        $length = strlen($pPhone);
        if ($length > 12) {
            return preg_replace("/(\d{2,3})(\d{2})(\d{5})(\d{4})/", "+\$1 (\$2) \$3-\$4", $pPhone);
        } else if ($length === 11) {
            return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $pPhone);
        } 

        return preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $pPhone); 
    }
}

/**
 * Remove a mascara do Telefone.
 *
 * @param string $pString
 * @return void
 */
if (! function_exists('unformat_phone')) {
    function unformat_phone(string $pPhone): string
    {
        $pPhone = preg_replace('/\D/', '', $pPhone);

        return $pPhone;
    }
}