<?php
/**
 * Valida se o CNPJ he valido.
 *
 * @param string $pCnpj
 * @return boolean
 */
if (! function_exists('cnpj_validate')) {
    function cnpj_validate(string $pCnpj): bool
    {
        $cnpjValido = true;
        // Etapa 1: Cria um array com apenas os digitos numericos, isso permite receber o cnpj em diferentes formatos
        // como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
        $j = 0;
        for ($i = 0; $i < (strlen($pCnpj)); $i ++) {
            if (is_numeric($pCnpj[$i])) {
                $num[$j] = $pCnpj[$i];
                $j ++;
            }
        }

        // Etapa 2: Conta os digitos, um Cnpj valido possui 14 digitos numericos.
        if (count($num) != 14) {
            $cnpjValido = false;
        }

        // Etapa 3: O numero 00000000000 embora nao seja um cnpj real resultaria um cnpj numero apos o calculo dos
        // digitos verificares e por isso precisa ser filtradas nesta etapa.
        if ($num[0] == 0 &&
            $num[1] == 0 &&
            $num[2] == 0 &&
            $num[3] == 0 &&
            $num[4] == 0 &&
            $num[5] == 0 &&
            $num[6] == 0 &&
            $num[7] == 0 &&
            $num[8] == 0 &&
            $num[9] == 0 &&
            $num[10] == 0 &&
            $num[11] == 0
        ) {
            $cnpjValido = false;
        } else {
            // Etapa 4: Calcula e compara o primeiro digito verificador.
            $j = 5;
            for ($i = 0; $i < 4; $i ++) {
                $multiplica[$i] = $num[$i] * $j;
                $j --;
            }

            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i ++) {
                $multiplica[$i] = $num[$i] * $j;
                $j --;
            }

            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }

            if ($dg != $num[12]) {
                $cnpjValido = false;
            }
        }

        // Etapa 5: Calcula e compara o segundo digito verificador.
        if (! isset($cnpjValido)) {
            $j = 6;
            for ($i = 0; $i < 5; $i ++) {
                $multiplica[$i] = $num[$i] * $j;
                $j --;
            }

            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 5; $i < 13; $i ++) {
                $multiplica[$i] = $num[$i] * $j;
                $j --;
            }

            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }

            if ($dg != $num[13]) {
                $cnpjValido = false;
            } else {
                $cnpjValido = true;
            }
        }

        // Etapa 6: Retorna o Resultado em um valor booleano.
        return $cnpjValido;
    }
}



/**
 * Valida se o CPF he valido.
 *
 * @param string $pCpf
 * @return boolean
 */
if (! function_exists('cpf_validate')) {
    function cpf_validate(string $pCpf): bool
    {
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $pCpf );
     
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}

/**
 * Valida se o CPF ou CNPJ estao no formato valido.
 */
if (! function_exists('cpf_cnpj_validate')) {
    function cpf_cnpj_validate(string $pCpfCnpj): bool
    {
        // removendo a mascara do documento.
        $cpfcnpj = unformat_document($pCpfCnpj);

        // expressao regular para validar se o documento he um cpf ou cnpj.
        $regex = '/^([0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}|[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2})$/';

        // validando o formato do documento com a expressao regular.
        // se o formato do documento nao for um cpf ou cnpj, retorna false.
        $preg = preg_match($regex, $cpfcnpj);
        if (! $preg) {
            return false;
        }

        return true;
    }
}

/**
 * Verifica se o valor existe.
 *
 * @param string $pValue
 * @return boolean
 */
if (! function_exists('exists')) {
    function exists(string $pValue): bool
    {
        if (isset($pValue) && ! empty($pValue)) {
            return true;
        }

        return false;
    }
}

/**
 * Verifica se o valor esta presente.
 *
 * @param string $pValue
 * @return boolean
 */
if (! function_exists('is_present')) {
    function is_present(string $pValue): bool
    {
        if (isset($pValue)) {
            return true;
        }

        return false;
    }
}

/**
 * Verifica se o valor esta vazio.
 *
 * @param string $pValue
 * @return boolean
 */
if (! function_exists('is_empty')) {
    function is_empty(string $pValue): bool
    {
        if (empty($pValue)) {
            return true;
        }

        return false;
    }
}

/**
 * Verifica se o valor esta nulo
 *
 * @param string $pValue
 * @return boolean
 */
if (! function_exists('is_null')) {
    function is_null(string $pValue): bool
    {
        if ($pValue === null) {
            return true;
        }

        return false;
    }
}

/**
 * Valida a string de acordo com o padrao especificado.
 *
 * @param string $pPattern
 * @param string $pSubject
 * @param boolean $pMatches
 * @param integer $pFlags
 * @param integer $pOffset
 * @return int|bool
 */
if (! function_exists('pregmatch')) {
    function pregmatch(string $pPattern, string $pSubject, bool $pMatches = false, int $pFlags = 0, int $pOffset = 0)
    {
        if ($pMatches) {
            preg_match("/{$pPattern}/", "$pSubject", $matches, $pFlags, $pOffset);

            return $matches;
        }

        return preg_match("/{$pPattern}/", "$pSubject", $matches, $pFlags, $pOffset);
    }
}
