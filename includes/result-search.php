<?php
global $files;
$total = 0;
$finfo = finfo_open( FILEINFO_MIME_TYPE );
?><script src="<?php echo STATIC_URL . '/sortelements/index.js'; ?>"></script><?php
require_once INCLUDE_DIR . DIRECTORY_SEPARATOR . 'nav-search.php';
?><div class="list-group result" id="files"><?php
while ( false !== ( $file = $files->next() ) ) {
	$date = filectime( $file );
	$name = basename( $file );
	$filetype = finfo_file( $finfo, $file );
	?><div class="list-group-item item"
		data-date="<?php echo htmlspecialchars( $date ); ?>"
		data-name="<?php echo htmlspecialchars( $name ); ?>"
		>
		<div class="media">
			<div class="media-left media-middle">
				<img
					src="<?php echo STATIC_URL . DIRECTORY_SEPARATOR . 'mimetypes' . DIRECTORY_SEPARATOR . urlencode( substr( $filetype, 0, strpos( $filetype, '/' ) ) ); ?>.png"
					class="media-object" alt="<?php echo $filetype; ?>">
			</div>
			<div class="media-body">
				<h4 class="media-heading">
					<?php echo htmlspecialchars( $name ); ?>
				</h4>
				<p>
					<?php if ( defined( 'SHOW_TYPE' ) && SHOW_TYPE ) {
						?><span class="label label-info"><?php
						echo htmlspecialchars( $filetype );
						?></span><?php
					} ?>
					<?php if ( defined( 'SHOW_USER' ) && SHOW_USER ) {
						?><span class="label label-info"><?php
						echo htmlspecialchars( posix_getpwuid( fileowner( $file ) )['name'] );
						?></span><?php
					} ?>
					<?php if ( defined( 'SHOW_SIZE' ) && SHOW_SIZE ) {
						?><span class="label label-info"><?php
						echo htmlspecialchars( bytesConvert( filesize( $file ) ) );
						?></span><?php
					} ?>
					<?php if ( defined( 'SHOW_DATE' ) && SHOW_DATE ) {
						?><span class="label label-info"><?php
						echo htmlspecialchars( date( 'F d Y H:i:s', $date ) );
						?></span><?php
					} ?>
				</p>
				<p>
					<a class="btn btn-primary"
						href="view.php?path=<?php echo urlencode( $file ); ?>"
						attr="<?php echo $file; ?>"> <span
						class="glyphicon glyphicon-download"></span> Télécharger <span
						class="glyphicon glyphicon-download"></span>
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
?><script src="<?php echo STATIC_URL . '/search.js'; ?>"></script><?php
if ( ! $total ) {
	?>
<p>Aucun fichier trouvé.</p>
<?php
}