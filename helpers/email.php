<?php

/**
 * Retorna o usuario a partir do endereco de email.
 *
 * @param string $pDsEmail
 * @return string|false
 */
if (! function_exists('get_email_user')) {
    function get_email_user(string $pDsEmail): ?string
    {
        preg_match('/^([0-9a-zA-Z]+([_.-]?[0-9a-zA-Z]+)*@[0-9a-zA-Z]+[0-9,a-z,A-Z,.,-]*(.){1}[a-zA-Z]{2,4})+$/', $pDsEmail, $matches);
        if ($matches) {
            $arr = explode("@", $pDsEmail);
            return reset($arr);
        }

        return false;
    }
}
