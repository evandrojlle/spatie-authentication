<?php

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

/**
 * Valida se o formato de data esta no formato iso.
 * 
 * @param string $pDate - Data a ser validada.
 * @return bool
 */
if (! function_exists('valid_dateformat')) {
    function valid_dateformat(string $pDate): bool
    {
        $regexp = '/' . REGEX_DATE_US . '/';
        $test = preg_match($regexp, $pDate);
        if ($test) {
            return true;
        }

        return false;
    }
}

/**
 * Valida se o formato de data e hora esta no formato iso.
 * 
 * @param string $pDatetime - Data e hora a ser validada.
 * @return bool
 */
if (! function_exists('valid_datetimeformat')) {
    function valid_datetimeformat(string $pDatetime): bool
    {
        $regexp = '/' . REGEX_DATETIME_US . '/';
        $test = preg_match($regexp, $pDatetime);
        if ($test) {
            return true;
        }

        return false;
    }
}

/**
 * Valida se o formato de data esta no formato brasileiro.
 * 
 * @param string $pDate - Data a ser validada.
 * @return bool
 */
if (! function_exists('valid_br_dateformat')) {
    function valid_br_dateformat(string $pDate): bool
    {
        $regexp = '/' . REGEX_DATE_BR . '/';
        $test = preg_match($regexp, $pDate);
        if ($test) {
            return true;
        }

        return false;
    }
}

/**
 * Valida se o formato de data e hora esta no formato brasileiro.
 * 
 * @param string $pDatetime - Data e hora a ser validada.
 * @return bool
 */
if (! function_exists('valid_br_datetimeformat')) {
    function valid_br_datetimeformat(string $pDatetime): bool
    {
        $regexp = '/' . REGEX_DATETIME_BR . '/';
        $test = preg_match($regexp, $pDatetime);
        if ($test) {
            return true;
        }

        return false;
    }
}

/**
 * Retorna a data de forma invertida, conforme os parâmetros definidos.
 * Identica a função reverseDatetime, mas sem a opção de exibir a hora.
 *
 * @param string $pDate
 * @param string $pDelimiter
 * @param string $pGlue
 * @return string
 */
if (! function_exists('reverse_date')) {
    function reverse_date(string $pDate, string $pDelimiter, string $pGlue): string
    {
        $arrDate = explode($pDelimiter, $pDate);
        $reverse = array_reverse($arrDate);
        $date    = implode($pGlue, $reverse);

        return $date;
    }
}

/**
 * Retorna a data e hora de forma invertida, conforme os parâmetros definidos.
 *
 * @param string $pDatetime - Data a ser invertida
 * @param string $pDelimiter - Delimitador usado para quebrar a string
 * @param string $pGlue - Caracter concatenador para reorganizar a string
 * @param bool $pShowTime - Indicativo se a hora deve ser apresentada no retorno.
 * @param string $pGlueToTime - Caracteres concatenadores para data e hora.
 * @return string
 */
if (! function_exists('reverse_datetime')) {
    function reverse_datetime(string $pDatetime, string $pDelimiter, string $pGlue, bool $pShowTime = false, string $pGlueToTime = ' '): string // phpcs: ignore
    {
        $arrDatetime = explode(" ", $pDatetime);
        $date = $arrDatetime[0];
        $arrDate = explode($pDelimiter, $date);
        $reverseDate = array_reverse($arrDate);
        $date = implode($pGlue, $reverseDate);
        
        if ($pShowTime === true) {
            if (count($arrDatetime) > 1) {
                $time = $arrDatetime[1];
                $date = "{$date}{$pGlueToTime}{$time}";
            }
        }

        return $date;
    }
}

/**
 * Converte uma data em datetime no formato brasileiro para o formato iso.
 * 
 * @param string $pDatetime - Data a ser convertida.
 * @return string
 */
if (! function_exists('datetimebr_2database')) {
    function datetimebr_2database(string $pDatetime): string
    {
        return reverse_datetime($pDatetime, '/', '-', true);
    }
}


/**
 * Converte uma data simples no formato brasileiro para o formato iso.
 * 
 * @param string $pDate - Data a ser convertida.
 * @return string
 */
if (! function_exists('datebr2db')) {
    function datebr2db(string $pDate): string
    {
        return reverse_date($pDate, '/', '-');
    }
}

/**
 * Converte uma data em datetime para o formato brasileiro.
 * 
 * @param string $pDatetime - Data a ser convertida.
 * @return string
 */
if (! function_exists('datetime_2br')) {
    function datetime_2br(string $pDatetime): string
    {
        if (valid_dateformat($pDatetime) || valid_datetimeformat($pDatetime)) {
            return reverse_datetime($pDatetime, '-', '/', true, ' às ');
        }

        return $pDatetime;
    }
}

/**
 * Converte uma data simples para o formato brasileiro.
 * 
 * @param string $pDate - Data a ser convertida.
 * @return string
 */
if (! function_exists('date_2br')) {
    function date_2br(string $pDate)
    {
        if (valid_dateformat($pDate) || valid_datetimeformat($pDate)) {
            return reverse_date($pDate, '-', '/');
        }

        return $pDate;
    }
}

/**
 * Verifica se a data esta no formato brasileiro.
 * 
 * @param string $pDatetime - Data a ser verificada.
 * @return bool
 */
if (! function_exists('is_br_dateformat')) {
    function is_br_dateformat(string $pDate): bool
    {
        return valid_br_dateformat($pDate);
    }
}

/**
 * Verifica se a data e hora esta no formato brasileiro.
 * 
 * @param string $pDatetime - Data e hora a ser verificada.
 * @return bool
 */
if (! function_exists('is_br_datetimeformat')) {
    function is_br_datetimeformat(string $pDatetime): bool
    {
        return valid_br_datetimeformat($pDatetime);
    }
}

/**
 * Formata a data de acordo com $pFormat
 * 
 * @param string|null $pDatetime -  A data a ser formatada.
 * @param string $pFormat - O formato que a data deverá ser exibida.
 * @return strinf
 */
if (! function_exists('parse_datetime')) {
    function parse_datetime(string|null $pDatetime, string $pFormat): string
    {
        if (null === $pDatetime) {
            return null;
        }

        return (
            ! is_br_dateformat($pDatetime) &&
            ! is_br_datetimeformat($pDatetime)
        ) ? Carbon::parse($pDatetime)->timezone(config('app.timezone'))->format($pFormat) : $pDatetime;
    }
}

/**
 * Retorna uma lista com as datas entre $pStartDate e $pEndDate, respeitando o intervalo de $pInterval.
 * 
 * @param Carbon $pStartDate - Data Inicial
 * @param Carbon $pEndDate - Data Final
 * @param string $pInterval - Intervalo
 * @return array
 */
if (! function_exists('range_date')) {
    function range_date(Carbon $pStartDate, Carbon $pEndDate, string $pInterval = '1 day'): array
    {
        $interval = \DateInterval::createFromDateString($pInterval);
        $dateRange = new \DatePeriod($pStartDate, $interval, $pEndDate);
        $range = [];
        foreach ($dateRange as $date) {
            if (intval($date->format('N')) === 6) {
                $date->add(new \DateInterval('P2D'));
            }
            
            $day = $date->format('Y-m-d');
            $range[] = $day;
        }

        return $range;
    }
}

/**
 * Retorna uma lista com os anos entre a data atual e o intervalo de $pInterval anos.
 * 
 * @param string $pInterval - Intervalo.
 * @return array
 */
if (! function_exists('years_interval')) {
    function years_interval (int $pInterval): array
    {
        $start = Carbon::now()->format('Y');
        $last = $start + $pInterval;
        $interval = [];
        for ($i = $start; $i <= $last; $i++) {
            $interval[] = $i;
        }

        return $interval;
    }
}

/**
 * Retorna uma lista com os meses do ano. Se $pShowNames for true, então sera exibido o nome do mes, senao exibira somente o valor numerico
 * 
 * @param bool $pShowNames - Indica se devera exibir o nome do mes.
 * @return array
 */
if (! function_exists('list_months')) {
    function list_months (bool $pShowNames = false): array
    {
        $first = Carbon::createFromFormat('d/m', '01/01');
        $last = Carbon::createFromFormat('d/m', '31/12');
        $interval = CarbonInterval::month(1);
        $period = new CarbonPeriod($first, $interval, $last);
        $list = [];
        foreach ($period as $month) {
            $list[] = ($pShowNames) ? __($month->format('F')) : $month->format('n');
        }
        
        return $list;
    }
}

/**
 * Verifica se a data esta no formato iso.
 * 
 * @param string $pDatetime - Data a ser verificada.
 * @return bool
 */
if (! function_exists('is_isoformat')) {
    function is_isoformat(string $pDatetime): bool
    {
        $pattern = '/' . REGEX_DATETIME_ISO . '/';

        return preg_match($pattern, $pDatetime) ? true : false;
    }
}

/**
 * Retorna a hora atual.
 *
 * @return string
 */
if (! function_exists('current_time')) {
    function current_time() : string
    {
        return date('H:i:s');
    }
}

/**
 * Retorna data de hoje + 12 meses com a ultima hora do dia no formato iso ou brasileiro.
 *
 * @param string $pFormat - Indica se o formato de data dever iso ou brasileiro.
 * @return string
 */
if (! function_exists('end_date')) {
    function end_date(string $pFormat = PT_BR): string
    {
        $timezone = config('app.timezone');
        $mask =  $pFormat == PT_BR ? "dd/MM/yyyy 23:59:59" : "yyyy-MM-dd 23:59:59";
        $time = strtotime('+ 12 months');
        $fmt = new \IntlDateFormatter(
            $pFormat,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            $mask
        );
        
        return $fmt->format($time);
    }
}

/**
 * Retorna data de hoje + a quantidade de meses passado por parametro, no formato iso  ou brasileiro.
 *
 * @param int $pQtdMonths - Quantidade de meses.
 * @param string $pFormat - Indica se o formato de data dever iso ou brasileiro.
 * @return string
 */
if (! function_exists('next_date')) {
    function next_date(int $pQtdMonths = 0, string $pFormat = PT_BR): string
    {
        $timezone = config('app.timezone');
        $mask =  $pFormat == PT_BR ? "dd/MM/yyyy 23:59:59" : "yyyy-MM-dd 23:59:59";
        $time = strtotime("+ {$pQtdMonths} months");
        $fmt = new \IntlDateFormatter(
            $pFormat,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            $timezone,
            \IntlDateFormatter::GREGORIAN,
            $mask
        );

        return $fmt->format($time);
    }
}

/**
 * Retorna a data e hora como objeto.
 *
 * @param string $pDatetime
 * @return DateTime
 */
if (! function_exists('date_time_object')) {
    function date_time_object(string $pDatetime = null): \DateTime
    {
        $datetime = new \Datetime($pDatetime);

        return $datetime;
    }
}

/**
 * Retorna a data como objeto.
 *
 * @param string $pDate
 * @return DateTime
 */
if (! function_exists('date_object')) {
    function date_object(string $pDate = null): \DateTime
    {
        $date = new \Datetime($pDate);

        return $date;
    }
}

/**
 * Retorna a data, se a mesma estiver sem separadores.
 * Ex: 20200812220521 retornará a data no formato 2020-08-12 22:05:21
 *
 * @param string $pStrDate
 * @return string
 */
if (! function_exists('parse_string_date')) {
    function parse_string_date(string $pStrDate): string
    {
        $year  = substr($pStrDate, 0, 4);
        $month = substr($pStrDate, 4, 2);
        $day   = substr($pStrDate, 6, 2);
        $h     = substr($pStrDate, 8, 2);
        $i     = substr($pStrDate, 10, 2);
        $s     = substr($pStrDate, 12, 2);

        $date = "{$year}-{$month}-{$day} {$h}:{$i}:{$s}";

        return $date;
    }
}

/**
 * Retorna a data atual no formato inglês ou brasileiro.
 *
 * @param string $pFormat
 * @return string
 */
if (! function_exists('get_today')) {
    function get_today(string $pFormat = EN): string
    {
        if ($pFormat == EN) {
            return date(DATE_FORMAT_US);
        } else {
            return date(DATE_FORMAT_BR);
        }
    }
}

/**
 * Retorna a data e hora atual no formato inglês ou brasileiro.
 *
 * @param string $pFormat
 * @return string
 */
if (! function_exists('date_now')) {
    function date_now(string $pFormat = EN): string
    {
        //setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        //date_default_timezone_set('America/Sao_Paulo');
        if ($pFormat == EN) {
            $date = date(DATETIME_FORMAT_US);
        } else {
            $date = date(DATETIME_FORMAT_BR);
        }

        return $date;
    }
}

/**
 * Retorna d diferença entre duas datas.
 *
 * @param string $pStrInterval - Intervado desejado. y: anos, m: meses, d: dias, h: horas, i: minutos, s: segundos
 * @param string $pDtMenor - Data menor
 * @param string $pDtMaior - Data maior
 * @param boolean $pAbsoluta - Quando deve ser retornado a diferença absoluta.
 * @return integer
 */
if (! function_exists('datediff')) {
    function datediff(string $pStrInterval, string $pDtMenor, string $pDtMaior, bool $pAbsoluta = false) : int
    {
        if (is_string($pDtMenor)) {
            $dtMenor = date_create($pDtMenor);
        }

        if (is_string($pDtMaior)) {
            $dtMaior = date_create($pDtMaior);
        }

        $diff = date_diff($dtMenor, $dtMaior, ! $pAbsoluta);
        $year = $diff->y;
        $month = $diff->m;
        $days = $diff->d;
        $hour = $diff->h;
        $minutes = $diff->i;
        $seconds = $diff->s;
        switch ($pStrInterval) {
            case "y":
                $total = $year + $month / 12 + $days / 365.25;
                break;
            case "m":
                $total = $year * 12 + $month + $days / 30 + $hour / 24;
                break;
            case "d":
                $total = $year * 365.25 + $month * 30 + $days + $hour / 24 + $minutes / 60;
                break;
            case "h":
                $total = ($year * 365.25 + $month * 30 + $days) * 24 + $hour + $minutes / 60;
                break;
            case "i":
                $total = (($year * 365.25 + $month * 30 + $days) * 24 + $hour) * 60 + $minutes + $seconds / 60;
                break;
            case "s":
                $total = ((($year * 365.25 + $month * 30 + $days) * 24 + $hour) * 60 + $minutes) * 60 + $seconds;
                break;
        }

        if ($diff->invert) {
            return -1 * $total;
        } else {
            return $total;
        }
    }
}