<?php
/**
 * Retorna as informacoes gravadas na sessao atual, de acordo com o nome recebido por parametro.
 * Se nao receber o nome como parametro retornara um array vazio.
 *
 * @param string $pSessionName - Index da sessao
 * @return array
 */
if (! function_exists('get_session')) {
    function get_session(string $pSessionName = null): array
    {
        if ($pSessionName) {
            if (! isset($_SESSION[$pSessionName])) {
                return [];
            }

            return $_SESSION[$pSessionName];
        }

        return $_SESSION;
    }
}

/**
 * Grava $pSessionValue na sessao de $pSessionIndex.
 *
 * @param string $pSessionIndex
 * @param string|string[] $pSessionValue
 * @return void
 */
if (! function_exists('set_session')) {
    function set_session(string $pSessionIndex, $pSessionValue)
    {
        $_SESSION[$pSessionIndex] = $pSessionValue;
    }
}

/**
 * Limpa os valores da sessao de $pSessionIndex.
 *
 * @param string $pSessionIndex
 * @return void
 */
if (! function_exists('clear_session')) {
    function clear_session(string $pSessionIndex)
    {
        if ($pSessionIndex) {
            if (isset($_SESSION[$pSessionIndex])) {
                unset($_SESSION[$pSessionIndex]);
            }
        }
    }
}

/**
 * Transforma o json armazenado na sessao em array.
 *
 * @param string $pSessionName - Index da sessao
 * @return void
 */
if (! function_exists('pass_decrypt')) {
    function jsonSessionTransform(string $pSessionName, string $fieldName): array
    {
        $session = get_session($pSessionName);
        if (! $session) {
            return [];
        }

        $sessionObj = json_decode($session[$fieldName]);
        return object2array($sessionObj);
    }
}
