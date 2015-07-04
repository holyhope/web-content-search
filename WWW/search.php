<?php
if ( empty( $_POST['search'] ) ) {
	http_response_code( 400 );
	die();
}

require_once 'config.php';
require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'head.php';


/**
 * Converts bytes into human readable file size.
 *
 * @param string $bytes
 * @return string human readable file size (2,87 Мб)
 * @author Mogilev Arseny
 */
function bytesConvert( $bytes ) {
	$bytes = floatval( $bytes );

	$arBytes = array(
		0 => array( 'UNIT' => 'TB', 'VALUE' => pow( 1024, 4 ) ),
		1 => array( 'UNIT' => 'GB', 'VALUE' => pow( 1024, 3 ) ),
		2 => array( 'UNIT' => 'MB', 'VALUE' => pow( 1024, 2 ) ),
		3 => array( 'UNIT' => 'KB', 'VALUE' => 1024 ),
		4 => array( 'UNIT' => 'B', 'VALUE' => 1 ) );

	foreach ( $arBytes as $arItem ) {
		if ( $bytes >= $arItem['VALUE'] ) {
			$result = $bytes / $arItem['VALUE'];
			$result = str_replace( '.', ',', strval( round( $result, 2 ) ) ) . ' ' . $arItem['UNIT'];
			break;
		}
	}

	return $result;
}

$search = $_POST['search'];

?><h1>
	R�sultats pour <?php echo htmlentities( $search ); ?>
</h1><?php
require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'menu.php';

global $files;
$files = new FileIterator( ROOT_SEARCH, $search, ! empty( $_POST['words'] ), ! empty( $_POST['is-regexp'] ) );

require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'result-search.php';

require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'foot.php';