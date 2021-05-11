<?php
// 这是系统自动生成的公共文件
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    不做证书校验，部署在linux环境下改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;

}

function getRandChar($count)
{
    $str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $len = strlen($str)-1;
    $randChar = '';
    for ($i = 0; $i < $count; $i++) {
        $rand = rand(0, $len);
        $randChar .= $str[$rand];
    }
    return $randChar;
}