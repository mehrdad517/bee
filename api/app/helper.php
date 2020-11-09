<?php

function blogSortItems($key = null) {
    $list = [
        ['title' => 'جدیدترین', 'field' => 'id', 'type' => 'desc'],
        ['title' => 'قدیمی', 'field' => 'id', 'type' => 'asc'],
        ['title' => 'محبوبترین', 'field' => 'visitor', 'type' => 'asc'],
        ['title' => 'عنوان', 'field' => 'title', 'type' => 'asc'],
    ];

    if ($key) {
        return $list[$key];
    }

    return $list;
}

function productSortItems($key = null) {
    $list = [
        ['title' => 'جدیدترین', 'field' => 'id', 'type' => 'desc'],
        ['title' => 'گرانترین', 'field' => 'price', 'type' => 'desc'],
        ['title' => 'ارزانترین', 'field' => 'price', 'type' => 'asc'],
        ['title' => 'بیشترین تخفیف', 'field' => 'discount', 'type' => 'desc'],
        ['title' => 'پرفروش ترین', 'field' => 'sales_number', 'type' => 'desc'],
        ['title' => 'محبوبترین', 'field' => 'visitor', 'type' => 'asc'],
        ['title' => 'حروف الفبا', 'field' => 'title', 'type' => 'asc'],
    ];

    if ($key) {
        return $list[$key];
    }

    return $list;
}

function payloadRecoverySMS($mobile) {

    $rand = random_code();
    try {
        $verify = new \MahdiMajidzadeh\Kavenegar\KavenegarVerify();
        $res = $verify->lookup($mobile, 'PasswordRecovery', $rand, null, null, 'sms');
        if (@$res[0]->status) {
            return $rand;
        } else {
            return false;
        }
    } catch (Exception $exception) {
        return false;
    }

}


function quickRandom($length = 16)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
}


function imploadValue($types){
    $strTypes = implode(",", $types);
    return $strTypes;
}

function explodeValue($types){
    $strTypes = explode(",", $types);
    return $strTypes;
}

function random_code() {

    return rand(11111, 99999);
}

function remove_special_char($text) {

    $t = $text;

    $specChars = array(
        ' ' => '-',    '!' => '',    '"' => '',
        '#' => '',    '$' => '',    '%' => '',
        '&amp;' => '',    '\'' => '',   '(' => '',
        ')' => '',    '*' => '',    '+' => '',
        ',' => '',    '₹' => '',    '.' => '',
        '/-' => '',    ':' => '',    ';' => '',
        '<' => '',    '=' => '',    '>' => '',
        '?' => '',    '@' => '',    '[' => '',
        '\\' => '',   ']' => '',    '^' => '',
        '_' => '',    '`' => '',    '{' => '',
        '|' => '',    '}' => '',    '~' => '',
        '-----' => '-',    '----' => '-',    '---' => '-',
        '/' => '',    '--' => '-',   '/_' => '-',

    );

    foreach ($specChars as $k => $v) {
        $t = str_replace($k, $v, $t);
    }

    return mb_strtolower($t, 'UTF-8');
}


?>
