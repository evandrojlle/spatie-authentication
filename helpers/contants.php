<?php
const SALTAPI = 'univille';

const SALT    = 'univille_site';

const MOBILE  = 'MOBILE';

const TABLET  = 'TABLET';

const IPHONE  = 'IPHONE';

const ANDROID = 'ANDROID';

const PC      = 'PC';

const WINDOWS = 'WINDOWS';

const LINUX   = 'LINUX';

const MAC     = 'MACINTOSH';

const IPAD    = 'IPAD';

const REST_API = 'APPLICATION\/JSON';

const API = 'API';

const SINGLE    = 'SINGLE';

const DOUBLE    = 'DOUBLE';

/**
 * Expressao Regular para testar a data em formato internacional.
 */
const REGEX_DATE_US = '^\d{4}-(0[1-9]|1[0,1,2])-(0[1-9]|[1,2][0-9]|3[0,1])$';

/**
 * Expressao Regular para testar a data e hora em formato internacional.
 */
const REGEX_DATETIME_US = '^\d{4}-(0[1-9]|1[0,1,2])-(0[1-9]|[1,2][0-9]|3[0,1])\s([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}$';

/**
 * Expressao Regular para testar a data em formato brasileiro.
 */
const REGEX_DATE_BR = '^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])\/(0[1-9]|1[0,1,2])\/\d{4}$';

/**
 * Expressao Regular para testar a data e hora em formato brasileiro.
 */
const REGEX_DATETIME_BR = '^([1-9]|0[1-9]|[1,2][0-9]|3[0,1])\/(0[1-9]|1[0,1,2])\/\d{4}\s([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}$';

/**
 * Expressao Regular para testar a data em formato brasileiro ou internacional.
 */
const REGEX_DUAL = '^(([1-9]|0[1-9]|[1,2][0-9]|3[0,1])/(0[1-9]|1[0,1,2])/\d{4})|(\d{4}-(0[1-9]|1[0,1,2])-(0[1-9]|[1,2][0-9]|3[0,1]))$';

/**
 * Expressao Regular para testar a data em formato ISO.
 */
const REGEX_DATETIME_ISO = '^\d{4}-(0[1-9]|1[0,1,2])-(0[1-9]|[1,2][0-9]|3[0,1])T([0-1][0-9]|[2][0-3])((:([0-5][0-9])){1,2})(.([0-9]){1,3})Z$';

/**
 * Formato da data no Brasil.
 */
const DATE_FORMAT_BR = 'd/m/Y';

/**
 * Formato da data Internacional.
 */
const DATE_FORMAT_US = 'Y-m-d';

/**
 * Formato da data no Brasil.
 */
const DATETIME_FORMAT_BR = 'd/m/Y H:i:s';

/**
 * Formato da data e hora Internacional.
 */
const DATETIME_FORMAT_US = 'Y-m-d H:i:s';

/**
 * Linguagem Ingles
 */
const EN = 'EN';

/**
 * Linguagem Portugues do Brasil.
 */
const PT_BR = 'PT_BR';

/**
 * Cadeia de caracteres para geracao de hashs
 */
const RCHARS = 'zxcvbnmasdfghjklqwertyuiop1234567890ZXCVBNMASDFGHJKLQWERTYUIOP~!@#$%^&*()_+[]\{}|,./?/';

/**
 * Tamanho minimo para a senha.
 */
const PASS_MIN_LEN = 8;

/**
 * Codificacao padrao.
 */
const ENCODING = 'UTF-8';
