<?php
/**
 * Retorna o valor no formato de moeda de acordo com a localizacao.
 *
 * @param string $locale
 * @param float $value
 * @return string
 * 
 */
if (! function_exists('currency')) {
    function currency(string $locale, float $value): string
    {
        $currency = (new \NumberFormatter($locale, \NumberFormatter::CURRENCY))->format($value);

        return $currency;
    }
}


if (! function_exists('check_digit')) {
    function check_digit($numbers)
    {
        $length = strlen($numbers);
        $second_algorithm = $length >= 12;
        $verifier = 0;

        for ($i = 1; $i <= $length; $i++) {
            if (!$second_algorithm) {
                $multiplier = $i+1;
            } else {
                $multiplier = ($i >= 9)? $i-7 : $i+1;
            }
            $verifier += $numbers[$length-$i] * $multiplier;
        }

        $verifier = 11 - ($verifier % 11);
        if ($verifier >= 10) {
            $verifier = 0;
        }

        return $verifier;
    }
}

/**
 * Converte uma string em um valor inteiro. Convem converter uma string numerica para inteiro.
 *
 * @param string $pNumber
 * @return integer
 */
if (! function_exists('int')) {
    function int(string $pNumber): int
    {
        return (int) $pNumber;
    }
}

/**
 * Formata um numero de acordo com as informacoes especificadas.
 *
 * @param float $pNumber - O valor a ser formatado.
 * @param int $pDecimals - A quantidade de casas decimais.
 * @param string $pDecimalPoint - O separador decimal.
 * @param string $pThousandsSeparator - O separador de milhar.
 */
if (! function_exists('format_number')) {
    function format_number(float $pNumber, int $pDecimals = 0, string $pDecimalPoint = '.', string $pThousandsSeparator = ','): string
    {
        if (is_numeric($pNumber)) {
            return number_format($pNumber, $pDecimals, $pDecimalPoint, $pThousandsSeparator);
        } else {
            return $pNumber;
        }
    }
}

/**
 * Converte uma balor decimal no formato brasileiro para o formato internacional.
 *
 * @param float $pValue
 * @return float
 */
if (! function_exists('decimal2Db')) {
    function decimal2Db(float $pValue): float
    {
        $value = \str_replace(',', '.', $pValue);

        return $value;
    }
}

/**
 * Retorna uma serie de numeros randomicos.
 *
 * @param integer $pLenght
 * @param bool $useTime
 * @return string
 */
if (! function_exists('random_series')) {
    function random_series(int $pLenght = 10, $useTime = false): string
    {
        $numbers = '0123456789';
        $qtdNumbers = strlen($numbers);
        $seriesRandomica = '';
        for ($offset = 0; $offset < $pLenght; $offset ++) {
            $rand = rand(0, $qtdNumbers - 1);
            $seriesRandomica .= $numbers[$rand];
        }

        if ($useTime) {
            $time = time();
            $seriesRandomica .= $time;
        }

        return str_shuffle($seriesRandomica);
    }
}
