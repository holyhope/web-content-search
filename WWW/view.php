<?php
if ( empty( $_GET['path'] ) ) {
	http_response_code( 400 );
	die();
}

require_once 'config.php';

$file = $_GET['path'];

if ( ! file_exists( $file ) ) {
	http_response_code( 404 );
	die();
}

header( 'Content-Description: File Transfer' );
header( 'Content-Type: application/octet-stream' );
header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
header( 'Expires: 3600' );
header( 'Cache-Control: must-revalidate' );
header( 'Pragma: public' );
header( 'Content-Length: ' . filesize( $file ) );
readfile( $file );