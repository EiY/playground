<?php
openssl_public_encrypt( 
  $ts = time() . mt_rand( 100, 999 ), $signature, 
  openssl_pkey_get_public( implode( PHP_EOL, [
    '-----BEGIN PUBLIC KEY-----',
    'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCx7Qve4bpsq2sE326zckvehSEA',
    'ydBNM45elcRq0REzbqscQcVf6pugJxdUokh0nAZ8r7WCdJCEsrfv0deMXdvRNapn',
    '1crfNNwtPgA8yc/dtvmmKxXShfX4LsnFe50j/SBi3bwdMazKQrb7hv0J0BIlcFQu',
    'C7YkPVgqwmTu8MeG6QIDAQAB',
    '-----END PUBLIC KEY-----'
  ] ) ) );

curl_setopt_array( $ch = curl_init(), [
  CURLOPT_URL					=>	'https://target.api.com',
  CURLOPT_SSL_VERIFYPEER		=>	0,
  CURLOPT_SSL_VERIFYHOST		=>	0,
  CURLOPT_RETURNTRANSFER		=>	1,
  CURLOPT_TIMEOUT				=>	5,
  CURLOPT_HTTPHEADER	   	    =>	[
    'Signature: ' . base64_encode( $signature ),
    "Timestamp: $ts"
  ],
  CURLOPT_USERAGENT				=>	'Mozilla/5.0 (compatible; +https://www.mysite.com/)'
]);

$ret = curl_exec( $ch );

curl_close( $ch );

header( 'Content-type:application/json;charset=utf-8' );

echo $ret;
