<?php
// Specific constants
define( 'MAX_FILE',     100 );
define( 'ROOT_SEARCH',  '/home/test' );
define( 'PROJECT_NAME', 'WebSearch' );
define( 'SHOW_USER', true );
define( 'SHOW_DATE', true );
define( 'SHOW_SIZE', true );
define( 'SHOW_TYPE', true );

// Web site constants
@include_once '../config.php';

if ( ! defined( 'STATIC_URL' ) ) {
	define( 'STATIC_URL', 'static' );
}
if ( ! defined( 'STATIC_DIR' ) ) {
	define( 'STATIC_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'static' );
}
if ( ! defined( 'INCLUDE_DIR' ) ) {
	define( 'INCLUDE_DIR', dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'includes' );
}

spl_autoload_register( function ( $class ) {
	$path = INCLUDE_DIR . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . strtolower( $class ) . '.php';
	include_once $path;
} );