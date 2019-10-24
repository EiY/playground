<?php
header( 'Content-type:text/plain' );
$file = '/root/htdocs/.well-known/pki-validation/fileauth.txt';
echo is_file( $file ) ? file_get_contents( $file ) : 'not yet';
