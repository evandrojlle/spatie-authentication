<?php
/**
 * Gera as informacoes formatadas que deverao ser gravadas no arquivo de log.
 *
 * @param array $pInfoLog
 * @param string $pTipo
 * @param string $pPrefixo
 * @return string
 */
if (! function_exists('log_create')) {
    function log_create(array $pInfoLog, string $pTipo = null, string $pPrefixo = null): string
    {
        if (is_array($pInfoLog)) {
            $dataLog = paste('|', $pInfoLog);
        } else {
            $dataLog = $pInfoLog;
        }

        $log = "LOG";
        if ($pTipo) {
            $log .= " DE {$pTipo}";
        }

        if ($pPrefixo) {
            $log .= " COM PREFIXO {$pPrefixo}";
        }

        $today = get_today(PT_BR);
        $now = current_time();

        $log .= " GERADO EM {$today} às {$now}. DADOS: ";
        $log .= $dataLog;

        return $log;
    }
}
