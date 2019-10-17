<?php
curl_setopt_array( $ch = curl_init(), [
    CURLOPT_URL                 =>  'http://bbb.com/',
    CURLOPT_SSL_VERIFYPEER      =>  false,
    CURLOPT_SSL_VERIFYHOST      =>	0,
    CURLOPT_RETURNTRANSFER		=>	true,
    CURLOPT_TIMEOUT				=>	5,
    CURLOPT_COOKIEFILE          =>  '/tmp/tcc',
    CURLOPT_COOKIEJAR           =>  '/tmp/tcc',
    CURLOPT_FOLLOWLOCATION		=>	true,
    CURLOPT_REFERER             =>  'http://aaa.com',
    CURLOPT_POSTFIELDS          =>  [],
    CURLOPT_USERAGENT			=>	'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/18.17763'
]);

echo curl_exec( $ch );


/*
            CURLOPT_HTTPGET             =>  true
            CURLOPT_POST                =>  true,


Mozilla/5.0 (Windows NT 3.1; IA64) Gecko/20100101
*/
