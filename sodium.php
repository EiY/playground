<?php
/*  https://www.zend.com/blog/modern-cryptography-php-72-sodium  */

# substr( sodium_crypto_box_keypair(), 0, 32 );
# sodium_crypto_box_secretkey( sodium_crypto_box_keypair() );
# sodium_crypto_auth_keygen(); // ?
$server_key = base64_decode( 'lO8g4PuEiVPyLjdB8MwlIIGLPwXbQmjveabhMJnTmgI=' );
$public_key = base64_encode( sodium_crypto_box_publickey_from_secretkey( $server_key ) );

if ( isset( $_POST['box'], $_POST['pub'], $_POST['nonce'] ) ) {
  header( 'Content-type:application/json' );

  # sodium_crypto_box_keypair_from_secretkey_and_publickey( $server_key, base64_decode( $_POST['pub'] ) );
  $user_key = $server_key . base64_decode( $_POST['pub'] );

  $txt = sodium_crypto_box_open(
    base64_decode( $_POST['box'] ), base64_decode( $_POST['nonce'] ), $user_key
  );

  if ( false === $txt ) return print( json_encode([ 'ok' => 0 ]) );

  # it's 24
  $nonce = random_bytes( SODIUM_CRYPTO_BOX_NONCEBYTES );

  return print( json_encode([ 'ok' => 1, 'nonce' => base64_encode( $nonce ), 'box' => 
    base64_encode( sodium_crypto_box( strrev( $txt ), $nonce, $user_key ) )
  ]) );

} else header( 'Content-type:text/html' );
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sodium</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tweetnacl/1.0.1/nacl.min.js"></script>
    <script> /* tweetnacl.util.js */
      !function(e,n){"use strict";"undefined"!=typeof module&&module.exports?module.exports=n():e.nacl?e.nacl.util=n():(e.nacl={},e.nacl.util=n())}(this,function(){"use strict";function e(e){if(!/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/.test(e))throw new TypeError("invalid encoding")}var n={};return n.decodeUTF8=function(e){if("string"!=typeof e)throw new TypeError("expected string");var n,r=unescape(encodeURIComponent(e)),t=new Uint8Array(r.length);for(n=0;n<r.length;n++)t[n]=r.charCodeAt(n);return t},n.encodeUTF8=function(e){var n,r=[];for(n=0;n<e.length;n++)r.push(String.fromCharCode(e[n]));return decodeURIComponent(escape(r.join("")))},"undefined"==typeof atob?"undefined"!=typeof Buffer.from?(n.encodeBase64=function(e){return Buffer.from(e).toString("base64")},n.decodeBase64=function(n){return e(n),new Uint8Array(Array.prototype.slice.call(Buffer.from(n,"base64"),0))}):(n.encodeBase64=function(e){return new Buffer(e).toString("base64")},n.decodeBase64=function(n){return e(n),new Uint8Array(Array.prototype.slice.call(new Buffer(n,"base64"),0))}):(n.encodeBase64=function(e){var n,r=[],t=e.length;for(n=0;n<t;n++)r.push(String.fromCharCode(e[n]));return btoa(r.join(""))},n.decodeBase64=function(n){e(n);var r,t=atob(n),o=new Uint8Array(t.length);for(r=0;r<t.length;r++)o[r]=t.charCodeAt(r);return o}),n});
    </script>
    <script>
      const server = "<?=$public_key?>";
      const $ = id => document.getElementById( id );

      document.addEventListener( "DOMContentLoaded", e => {
        const keyPair = nacl.box.keyPair();

        $("form").addEventListener( "submit", async e => {
          e.preventDefault();

          const txt = $("txt").value;
          const nonce = nacl.randomBytes( 24 );

          const r = await (await fetch( "", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: 
              "nonce=" + encodeURIComponent( nacl.util.encodeBase64( nonce ) ) +
              "&box=" + encodeURIComponent( nacl.util.encodeBase64( nacl.box( 
                nacl.util.decodeUTF8( txt ), nonce, nacl.util.decodeBase64( server ), keyPair.secretKey 
              ) ) ) +
              "&pub=" + encodeURIComponent( nacl.util.encodeBase64( keyPair.publicKey ) )
          })).json();

          $("resp").innerHTML = 1 === r.ok && ( resp = nacl.box.open( 
            nacl.util.decodeBase64( r.box ), nacl.util.decodeBase64( r.nonce ), nacl.util.decodeBase64( server ), keyPair.secretKey 
          ) ) ? nacl.util.encodeUTF8( resp ) : '<em>- Error -</em>';

        });
      });
    </script>
  </head>
  <body>
    <form id="form"><input type="text" id="txt"><input type="submit"><p id="resp"></p></form>
  </body>
</html>