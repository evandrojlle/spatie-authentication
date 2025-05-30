<?php
/**
 * Retorna o valor do parametro REDIRECT_BASE do servidor, se existir.
 *
 * @return array|null
 */
if (! function_exists('base_redirect')) {
    function base_redirect(): ?array
    {
        return @$_SERVER['REDIRECT_BASE'];
    }
}

/**
 * Retorna o host do servidor sem o protocolo http.
 *
 * @return string
 */
if (! function_exists('get_host')) {
    function get_host(): string
    {
        return $_SERVER['HTTP_HOST'];
    }
}

/**
 * Retorna o host do servidor com o protocolo http.
 *
 * @return string
 */
if (! function_exists('base_url')) {
    function base_url(): string
    {
        return 'http://' . $_SERVER['HTTP_HOST'];
    }
}

/**
 * Retorna a rota do dominio com protocolo http ou https.
 *
 * @return string
 */
if (! function_exists('base_url_api')) {
    function base_url_api(): string
    {
        $requestScheme = $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $sufix = substr($scriptName, 0, strpos($scriptName, 'index'));

        return "{$requestScheme}://{$host}{$sufix}";
    }
}

/**
 * Transforma a rota do arquivo em url.
 *
 * @param string $pFile
 * @return string
 */
if (! function_exists('dir2url')) {
    function dir2url(string $pFile): string
    {
        $separator = (str_contains($pFile, '\\')) ? '\\' : '/';
        $baseUrl = base_url();
        $arrFile = explode($separator, $pFile);
        if ($arrFile[0] === 'C:') {
            $arrFile[0] = $baseUrl;
        } else {
            array_unshift($arrFile, $baseUrl);
        }

        $url = implode('/', $arrFile);

        return $url;
    }
}

/**
 * Retorna a url atual.
 * 
 * @return string
 */
if (! function_exists('request_url')) {
    function request_url(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $url = base_url() . $requestUri;

        return $url;

    }
}
