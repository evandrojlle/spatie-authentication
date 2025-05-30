<?php
/**
 * Gera a cadeia de cacteres de forma aleatoria com base nas informacoes de $pwd e $hint.
 * Funcao usada de forma privada na funcao pass_encrypt.
 *
 * @param string $pwd
 * @param string $hint
 */
if (! function_exists('gen')) {
    function gen(string $pwd, string $hint): string
    {
        $c  = str_split(preg_replace(preg($hint), '', RCHARS));
        $cl = count($c) - 1;
        $p = [];
        $l = strlen($pwd) * 3 + rand(0, 50);

        for ($i = 0; $i < $l; $i++) {
            $p[] = $c[rand(0, $cl)];
        }

        return paste('', $p);
    }
}

/**
 * Monta uma expresao regular a partir de $str.
 * 
 * @param string $str
 * @return string
 */
if (! function_exists('preg')) {
    function preg($str)
    {
        $str = array_unique(str_split($str));
        return '/[' . implode(
            '',
            pass_map(
                $str,
                function ($v) {
                    return preg_quote($v, '/');
                }
            )
        ) . ']/';
    }
}

/**
 * Aplica uma funcao de callback $fn passando $item por parametro, recursivamente no caso de array ou objeto.
 * 
 * @param mixed &$item
 * @param mixed $fn
 * @return mixed
 */
if (! function_exists('pass_map')) {
    function pass_map(mixed &$item, mixed $fn = ''): mixed
    {
        if (is_object($item) || is_array($item)) {
            foreach ($item as $k => &$v) {
                pass_map($v, $fn);
            }
        } else {
            $item = call_user_func($fn, $item);
        }

        return $item;
    }
}

/**
 * Encripta a senha.
 *
 * @param string $password
 * @param string $hint
 */
if (! function_exists('pass_encrypt')) {
    function pass_encrypt(string $password, string $hint): string
    {
        if ($password == $hint) {
            throw new \Exception('String cannot be the same as your hint password.');
        }

        $k = '';
        $x = 0;
        // $t = 0;
        // $s = [];
        // $d = false;
        $c = str_split(gen($password, $hint));
        $ol = strlen($hint);
        $pl = strlen($password);
        $m = floor(count($c) / 3);
        $nx = [];

        for ($i = 0; $i < $m; $i++) {
            $nx[$i] = (($i + 1) * 3);
        }

        shuffle($nx);

        $f = array_slice($nx, 0, $pl);

        $k = [];
        for ($i = 0; $i < $pl; $i++) {
            if (! isset($hint[$x])) {
                $x = 0;
            }

            if (! isset($k[$hint[$x]])) {
                $k[$hint[$x]] = [];
            }

            $k[$hint[$x]][] = $f[$i];

            $x++;
        }

        foreach ($k as $i => $v) {
            rsort($k[$i]);
        }

        $x = 0;
        $hp = 0;
        for ($i = 0; $i < $pl; $i++) {
            if ($x >= $ol) {
                $x = 0;
            }

            $hp = array_shift($k[$hint[$x]]);
            $c[$hp] = $hint[$x];
            $c[$hp + 1] = $password[$i];

            $x++;
        }

        return str_replace('<', '&lt;', paste('', $c) . 'passcrypt:' . ($pl + $ol) . ':');
    }
}

/**
 * decripta a senha.
 *
 * @param string $password
 * @param string $hint
 */
if (! function_exists('pass_decrypt')) {
    function pass_decrypt(string $encrypted, string $hint)
    {
        $encrypted = str_replace(['&lt;'], '<', $encrypted);
        preg_match('/(.*?)passcrypt:(\d+):$/', $encrypted, $q);

        $ol = strlen($hint);
        $pl = $q[2] - $ol;
        $p  = $q[1];
        $x  = 0;
        $hg = preg($hint);
        $c  = str_split($q[1]);
        $e  = [];
        $k  = [];
        $nl = [];

        preg_match_all($hg, $p, $xout, PREG_OFFSET_CAPTURE);

        foreach ($xout[0] as $key => $val) {
            $x0 = $val[0];
            $index = $val[1];

            if (! isset($nl[$index - 1])) {
                $nl[$index] = 1;
                if (! isset($k[$x0])) {
                    $k[$x0] = [];
                }

                $k[$x0][] = $index;
            }
        }

        foreach ($k as $i => $v) {
            rsort($k[$i]);
        }

        $x = 0;
        for ($i = 0; $i < $pl; $i++) {
            if ($x >= $ol) {
                $x = 0;
            }

            if (isset($k[$hint[$x]])) {
                $e[] = $c[array_shift($k[$hint[$x]]) + 1];
            }

            $x++;
        }

        return paste('', $e);
    }
}

/*
USAGE
$str = 'my-string';
$pwd = 'my-password';
$newstr = pass_encrypt($str, $pwd);
echo $newstr.'<br/>';

echo pass_decrypt($newstr, $pwd) == $str;
echo '</br></br>';

$str = 'my-string';
$pwd = '`12347890-=';

$newstr = pass_encrypt($str, $pwd);
echo $newstr.'<br/>';

echo pass_decrypt($newstr, $pwd) == $str;
echo '</br></br>';

$str = 'my-string';
$pwd = '~!@#$&*()_+%';

$newstr = pass_encrypt($str, $pwd);
echo $newstr.'<br/>';

echo pass_decrypt($newstr, $pwd) == $str;
echo '</br></br>';

$str = 'my-string';
$pwd = '[]\{}|-=_+;\':"';

$newstr = pass_encrypt($str, $pwd);
echo $newstr.'<br/>';

echo pass_decrypt($newstr, $pwd) == $str;
echo '</br></br>';

$str = 'my-string';
$pwd = ',./<>?';

$newstr = pass_encrypt($str, $pwd);
echo $newstr.'<br/>';

echo pass_decrypt($newstr, $pwd) == $str;
*/

/**
 * Gera uma string salt aleatoria com $length caracteres.
 * 
 * @param int $length
 * @return string
 */
if (! function_exists('salts')) {
    function salts(int $length): string
    {
        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $length = count($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $length - 1)];
        }

        return $string;
    }
}

/**
 * Gera uma senha aleatoria sem criptografia.
 */
if (! function_exists('generate_password')) {
    function generate_password()
    {
        $items = [
            '!@#$%^&*_+-*+',
            salts(12),
            '~<>?|:.(),',
        ];
        $pass = implode('', $items);

        return str_shuffle($pass);
    }
}

/**
 * Cria um hash da senha $password
 * 
 * @param string $password
 * @return string
 */
if (! function_exists('hash_password')) {
    function hash_password(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }
}

/**
 * Verifica se a senha $password corresponde a hash $hash
 * 
 * @param string $password
 * @param string $hash
 * @return bool
 */
if (! function_exists('hash_matched')) {
    function hash_matched(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

/**
 * Verifica se a senha $password he valida.
 * 
 * @param string 
 * @return bool
 */
if (! function_exists('is_valid_password')) {
    function is_valid_password(string $password): bool
    {
        return (
            is_uppercase($password) &&
            is_lowercase($password) &&
            is_number($password) &&
            is_simbol($password) &&
            length($password) >= PASS_MIN_LEN
        ) ? true : false;
    }
}

/**
 * Verifica se ha letras maiusculas em $string
 * 
 * @param string $string
 * @return int|false
 */
if (! function_exists('is_uppercase')) {
    function is_uppercase(string $string): int|false
    {
        return preg_match('/[A-Z]/', $string);
    }
}

/**
 * Verifica se ha letras minusculas em $string
 * 
 * @param string $string
 * @return int|false
 */
if (! function_exists('is_lowercase')) {
    function is_lowercase(string $string): int|false
    {
        return preg_match('/[a-z]/', $string);
    }
}

/**
 * Verifica se ha numeros em $string
 * 
 * @param string $string
 * @return int|false
 */
if (! function_exists('is_number')) {
    function is_number(string $string): int|false
    {
        return preg_match('/[0-9]/', $string);
    }
}

/**
 * Verifica se ha simbolos em $string
 * 
 * @param string $string
 * @return int|false
 */
if (! function_exists('is_simbol')) {
    function is_simbol($string)
    {
        return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string);
    }
}

/**
 * Verifica se o tamanho de $string
 * 
 * @param string $string
 * @return int|false
 */
if (! function_exists('length')) {
    function length(string $string): int
    {
        return strlen($string);
    }
}

/*
USAGE
$genPass = generate_password();
var_dump($genPass);

$hashPass = hash_password("!?HhLXN%B5.$@|_(x>,/#*+'*z\)^~:+/S-I<&j/");

echo '<br>';
var_dump(hash_matched("!?HhLXN%B5.$@|_(x>,/#*+'*z\)^~:+/S-I<&j/", $hashPass));
echo '<br>';
var_dump(is_valid_password($genPass));
*/
