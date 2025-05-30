<?php
/**
 * iniset()
 * Ativa, desativa ou altera a configuracao de forma segura, de algum parametro atraves da funcao ini_set, se disponivel.
 *
 * @param string $pOption The configuration option name.
 * @param string $pValue The new value for the option.
 * @throws \RuntimeException
 * @throws \InvalidArgumentException
 * @return string|false
 */

use App\Utils\MobileDetect\MobileDetect;
use Illuminate\Support\Facades\Hash;

if (! function_exists('iniset')) {
    function iniset(string $pOption, string $pValue)
    {
        if (! \function_exists('\\ini_set')) {
            // disabled_functions?
            throw new \RuntimeException('Native ini_set function not available.');
        }

        if (empty($pOption)) {
            throw new \InvalidArgumentException('$pOption must not be empty.');
        }
        return \ini_set($pOption, $pValue);
    }
}

/**
 * Ativa ou desativa a permissao para o tratamento de URLs (como http:// ou ftp://) como arquivos.
 *
 * @param bool $pAllow
 * @return string|false
 */
if (! function_exists('allowurlfopen')) {
    function allowurlfopen(bool $pAllow = true)
    {
        ini_set("allow_url_fopen", $pAllow);
    }
}

/**
 * Retorna o IP atual sem os pontos(.) de separacao.
 *
 * @return string
 */
if (! function_exists('get_ip')) {
    function get_ip(): string
    {
        $remoteAddr = $_SERVER['REMOTE_ADDR'];
        return str_replace('.', '', $remoteAddr);
    }
}

/**
 * Retorna o IP atual de forma natural.
 *
 * @return string
 */
if (! function_exists('current_ip')) {
    function current_ip(): string
    {
        $variables = [
            'REMOTE_ADDR',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_X_COMING_FROM',
            'HTTP_COMING_FROM',
            'HTTP_CLIENT_IP',
            'SSH_CONNECTION',
        ];

        $return = 'Unknown';
        foreach ($variables as $variable) {
            if (isset($_SERVER[$variable])) {
                $return = $_SERVER[$variable];
                break;
            }
        }

        return $return;
    }
}

/**
 * Retorna o Sistema Operacional Atual.
 *
 * @return string
 */
if (! function_exists('get_os')) {
    function get_os(): string
    {
        $agent = @$_SERVER['OS'] ?? @$_SERVER['HTTP_USER_AGENT'];
        $userAgent = @strtoupper($agent);
        if (preg_match('/' . WINDOWS . '/', $userAgent)) {
            $plataforma = WINDOWS;
        } elseif (preg_match('/' . LINUX . '/', $userAgent) && ! preg_match('/' . ANDROID . '/', $userAgent)) {
            $plataforma = LINUX;
        } elseif (preg_match('/' . MAC . '/', $userAgent)) {
            $plataforma = MAC;
        } elseif (preg_match('/' . ANDROID . '/', $userAgent)) {
            $plataforma = ANDROID;
        } elseif (preg_match('/' . IPHONE . '/', $userAgent)) {
            $plataforma = IPHONE;
        } elseif (preg_match('/' . IPAD . '/', $userAgent)) {
            $plataforma = IPAD;
        } elseif (preg_match('/' . REST_API . '/', $userAgent)) {
            $plataforma = API;
        } else {
            $plataforma = LINUX;
        }

        return $plataforma;
    }
}

/**
 * Detecta de o device he um celular, tablet ou PC.
 *
 * @return string
 */
if (! function_exists('mobile_detect')) {
    function mobile_detect(): string
    {
        $md = new MobileDetect();
        if ($md->isMobile()) {
            return MOBILE;
        } elseif ($md->isTablet()) {
            return TABLET;
        } else {
            return PC;
        }
    }
}

/**
 * Gera e retorna uma hash aleatoria de segurança para o device.
 *
 * @return string
 */
if (! function_exists('hash_device')) {
    function hash_device(): string
    {
        $device = mobile_detect();
        $os = get_os();
        $ip = get_ip();
        $hashDevice = Hash::make("{$device}.{$os}.{$ip}");

        return $hashDevice;
    }
}
