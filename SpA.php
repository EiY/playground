<?php
if ( $key = key( $_GET ) ) {
  header( 'Content-type: application/json' );

  return print( json_encode([
    'api'     =>  $key, 
    'method'  =>  $_SERVER['REQUEST_METHOD'],
    'query'   =>  array_slice( $_GET, 1 ),
    'data'    =>  json_decode( file_get_contents( 'php://input' ) ) 
  ]) );

} else header( 'Content-type: text/html' );
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Single-page Application</title>
    <script>
      "use strict";
      (() => {
        const main = {

          "event" : {

            "hashchange" : e => main.request( location.hash, {} ),

            "submit" : e => {
              e.preventDefault();

              const d = {};
              let a;
              new FormData( e.target ).forEach( ( v, k ) => {

                if ( a = k.match( /^([^\[]+)\[['"]?([^'"]*)['"]?\]$/ ) )

                  if ( "undefined" === typeof( d[a[1]] ) ) 
                    d[a[1]] = a[2] ? { [a[2]]: v } : [ v ];

                  else if ( a[2] ) main.build( d[a[1]], a[2], v );
  
                  else d[a[1]].push( v );
    
                else main.build( d, k, v );

              } );

              return main.request( e.target.getAttribute( "action" ), {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify( d )
              });

            }

          },

          "build" : ( o, k, v ) => {
            if ( Array.isArray( o[k] ) ) o[k].push( v );
            else if ( "undefined" === typeof( o[k] ) ) o[k] = v;
            else o[k] = [ o[k], v ]
          },

          "request" : async ( url, data ) => {
            if ( ! url || "#!" !== url.substr(0, 2) ) return;
            console.log( await (await fetch( "?" + url.substring(2).replace( '?', '&' ), data ) ).json() );
          },

        };

        for ( const ev of Object.entries( main.event ) )
          this.addEventListener( ...ev );

      })();
    </script>
  </head>
<body>
  <p>
    <a href="#!link1">link1</a>
    <a href="#!link2?with=?queryString">link2</a>
    <a href="#!link3/with/paramter">link3</a>
  </p>
  <form action="#!form">
    <p>
      <input type="text" name="text">
      <input type="submit">
    </p>
    <p><input type="text" name="another"></p>
    <select name="list">
      <option value="1">item 1</option>
      <option value="2">item 2</option>
      <option value="3">item 3</option>
      <option value="4">item 4</option>
    </select>
    <input type="radio" name="r" value="1">
    <input type="radio" name="r" value="2">
    <input type="checkbox" name="r" value="3">
    <input type="checkbox" name="r" value="4">
    <input type="checkbox" name="c1" value="5">
    <input type="checkbox" name="c1" value="6">
    <input type="checkbox" name="c2[]" value="7">
    <input type="checkbox" name="c2[]" value="8">
    <input type="checkbox" name="c2[]" value="9">
    <input type="checkbox" name="c3['foo']" value="10">
    <input type="checkbox" name="c3['foo']" value="11">
    <input type="checkbox" name="c3['bar']" value="12">
  </form>
</body>
</html>