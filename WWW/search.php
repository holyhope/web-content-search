<?php
if ( empty( $_POST['search'] ) ) {
	http_response_code( 400 );
	die();
}

require_once 'config.php';
require_once STATIC_DIR . DIRECTORY_SEPARATOR . 'head.php';

$search = $_POST['search'];

?><h1>
	Résultats pour <?php echo htmlentities( $search ); ?>
	<small>- <?php echo FileIterator::get_command(
		ROOT_SEARCH,
		$search,
		! empty( $_POST['words'] ),
		! empty( $_POST['is-regexp'] )
	); ?></small>
</h1><?php

$files = new FileIterator(
	ROOT_SEARCH,
	$search,
	! empty( $_POST['words'] ),
	! empty( $_POST['is-regexp'] )
);
$total = 0;
$finfo = finfo_open( FILEINFO_MIME_TYPE );
while ( false !== ( $file = $files->next() ) ) {
	$filetype = finfo_file( $finfo, $file ); ?>
	<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
		<img src="<?php echo STATIC_URL . DIRECTORY_SEPARATOR . urlencode( $filetype ); ?>" class="img-responsive" alt="<?php echo $filetype; ?>">
		<a href="view.php?path=<?php echo urlencode( $file ); ?>" attr="<?php echo $file; ?>">
			<span class="glyphicon glyphicon-save"></span>
			<?php echo basename( $file ); ?>
		</a>
	</div>
	<?php
	if ( $total++ == MAX_FILE ) {
		break;
	}
}

if ( ! $total ) { ?>
	<p>Aucun fichier trouvé.</p>
	<?php
		$opts = 'RHnil';

		if ( ! empty( $_POST['words'] ) ) {
			$opts .= 'w';
		}
}

require_once STATIC_DIR . DIRECTORY_SEPARATOR . 'foot.php';