<?php
/* 
in .htaccess
SetEnvIf Authorization .+ HTTP_AUTHORIZATION=$0
*/

try {
  Auth();
} catch ( Exception $ex ) {
  echo $ex->getMessage();
}

function Auth() {

  $realm = 'Restricted Area';
  $users = [ 'bb' => 'aa' ];

  $ask = function( $msg ) use ( $realm ) {
    http_response_code( 401 );
    header('WWW-Authenticate: Digest realm="'.$realm.'",qop="auth",nonce="'.md5(microtime()).'",opaque="'.md5($realm).'"');
    throw new Exception( $msg );
  };

  // send digest
  if ( empty( $_SERVER['PHP_AUTH_DIGEST'] ) ) return $ask( 'Need Login' );

  // analyze the PHP_AUTH_DIGEST variable
  $np = [ 'nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1 ];
  $d = [];
  $k = implode( '|', array_keys( $np ) );

  preg_match_all( "@($k)=(?:(['\"])([^\2]+?)\2|([^\s,]+))@", $_SERVER['PHP_AUTH_DIGEST'], $matches, PREG_SET_ORDER );

  foreach ( $matches as $m ) {
      $d[$m[1]] = $m[3] ? trim( $m[3], '"' ) : trim( $m[4], '"' );
      unset($np[$m[1]]);
  }

  if ( $np || ! isset( $users[$d['username']] ) || md5(
    md5( "{$d['username']}:{$realm}:{$users[$d['username']]}" ) .
    ":{$d['nonce']}:{$d['nc']}:{$d['cnonce']}:{$d['qop']}:" .
    md5( "{$_SERVER['REQUEST_METHOD']}:{$d['uri']}" )
  ) !== $d['response'] ) return $ask( 'Access Denied' );

  // ok, valid username & password
  echo 'You are logged in as: ' . $d['username'];

}
