<?php
if ( empty( $_POST['search'] ) ) {
	http_response_code( 400 );
	die();
}

require_once 'config.php';
require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'head.php';
?>
<script src="<?php echo STATIC_URL . '/search.js'; ?>"></script>
<?php

/**
 * Converts bytes into human readable file size.
 *
 * @param string $bytes
 * @return string human readable file size (2,87 ÐœÐ±)
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
	Résultats pour <?php echo htmlentities( $search ); ?>
</h1><?php
require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'menu.php';

$files = new FileIterator( ROOT_SEARCH, $search, ! empty( $_POST['words'] ), ! empty( $_POST['is-regexp'] ) );
$total = 0;
$finfo = finfo_open( FILEINFO_MIME_TYPE );
?><div class="list-group"><?php
while ( false !== ( $file = $files->next() ) ) {
	$date     = filectime( $file );
	$filetype = finfo_file( $finfo, $file );
	?><div class="list-group-item .col-lg-6" data-date="<?php echo htmlspecialchars( $date ); ?>">
		<div class="media">
			<div class="media-left media-middle">
				<img
					src="<?php echo STATIC_URL . DIRECTORY_SEPARATOR . 'mimetypes' . DIRECTORY_SEPARATOR . urlencode( substr( $filetype, 0, strpos( $filetype, '/' ) ) ); ?>.png"
					class="media-object" alt="<?php echo $filetype; ?>">
			</div>
			<div class="media-body">
				<h4 class="media-heading">
					<?php echo htmlspecialchars( basename( $file ) ); ?>
				</h4>
				<p>
					<?php if ( defined( 'SHOW_TYPE' ) && SHOW_TYPE ) { ?>
						<span class="label label-info"><?php echo htmlspecialchars( $filetype ); ?></span>
					<?php } ?>
					<?php if ( defined( 'SHOW_USER' ) && SHOW_USER ) { ?>
						<span class="label label-info"><?php echo htmlspecialchars( posix_getpwuid( fileowner( $file ) )['name'] ); ?></span>
					<?php } ?>
					<?php if ( defined( 'SHOW_SIZE' ) && SHOW_SIZE ) { ?>
						<span class="label label-info"><?php echo htmlspecialchars( bytesConvert( filesize( $file ) ) ); ?></span>
					<?php } ?>
					<?php if ( defined( 'SHOW_DATE' ) && SHOW_DATE ) { ?>
						<span class="label label-info"><?php echo htmlspecialchars( date( 'F d Y H:i:s.', $date ) ); ?></span>
					<?php } ?>
				</p>
				<p>
					<a class="btn btn-primary"
						href="view.php?path=<?php echo urlencode( $file ); ?>"
						attr="<?php echo $file; ?>">
						<span class="glyphicon glyphicon-download"></span>
						Télécharger
						<span class="glyphicon glyphicon-download"></span>
					</a>
				</p>
			</div>
		</div>
	</div><?php
	if ( $total++ == MAX_FILE ) {
		break;
	}
}
?></div><?php
if ( ! $total ) {
	?>
<p>Aucun fichier trouvé.</p>
<?php
	$opts = 'RHnil';

	if ( ! empty( $_POST['words'] ) ) {
		$opts .= 'w';
	}
}

require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'foot.php';