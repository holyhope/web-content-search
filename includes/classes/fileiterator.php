<?php

class FileIterator implements Iterator {
	/**
	 *
	 * @var int
	 */
	const MAX_LENGTH = 255;

	/**
	 *
	 * @var int
	 */
	const MAX_FILE = 30;

	/**
	 *
	 * @var resource
	 */
	private $process;

	/**
	 *
	 * @var String[]
	 */
	private $files = array();

	/**
	 *
	 * @var ressource[]
	 */
	private $pipes = array();

	/**
	 *
	 * @var string
	 */
	private $path;

	/**
	 *
	 * @param string $path    - Path to locate files
	 * @param string $pattern - Content to search
	 * @param bool   $word    - True if $pattern must match words.
	 * @param bool   $regexp  - True if $pattern is a regexp.
	 */
	public function __construct( $path = '/', $pattern, $word = true, $regexp = true ) {
		assert( is_dir( $path ) );
		assert( is_readable( $path ) );

		$this->path = $path;

		$command = $this->get_command( $path, $pattern, $word, $regexp );

		$this->process = proc_open(
			$command,
			array( 1 => array( "pipe", "w" ), 2 => array( "pipe", "w" ) ),
			$this->pipes,
			$path
		);
	}

	public static function get_command( $path = '/', $pattern, $word = true, $regexp = true ) {
		if ( ! $regexp ) {
			$pattern = preg_quote( $pattern );
		}

		$opts = 'RHnil';

		if ( $word ) {
			$opts .= 'w';
		}

		$command = 'grep -' . $opts .' --max-count=1 ' . escapeshellarg( $pattern );

		return $command;
	}

	function current() {
		return current( $this->files );
	}

	function next() {
		$value = next( $this->files );
		if ( false === $value ) {
			$key = count( $this->files );
			$this->update_files();
			for ( $i = 0; $i <= $key; $i++ ) {
				$value = next( $this->files );
			}
		}
		return $value;
	}

	function key() {
		return key( $this->files );
	}

	public function valid() {
		return valid( $this->files );
	}

	public function rewind() {
		return rewind( $this->files );
	}

	/**
	 * Add files to FileList::$files.
	 */
	private function update_files() {
		static $content = '';

		$added    = 0;
		$last     = false;
		$reads    = array(
			$this->pipes[1],
		);
		$writes   = $except = null;
		$status   = proc_get_status( $this->process );
		$newfiles = array();
		while ( $status['running'] || ! $last ) {
			if ( ! $status['running'] ) {
				$last = true;
			}

			$nb = stream_select( $reads, $writes, $except, 10, 0 );

			if ( $nb ) {
				foreach ( $reads as $flow ) {
					$string = fread( $flow, self::MAX_LENGTH );
					if ( $string ) {
						$content .= $string;
						$values = explode( "\n", $content );

						$content = array_pop( $values );

						foreach ( $values as $value ) {
							$newfiles[] = $this->path . DIRECTORY_SEPARATOR . $value;
							$added++;
						}
					}
					if ( $last ) {
						$newfiles[] = $this->path . DIRECTORY_SEPARATOR . $content;
						$content    = '';
					}
				}
			}

			$status = proc_get_status( $this->process );
		}

		$this->files = array_merge( $this->files, $newfiles );

		return $added;
	}

	public function __destruct() {
		foreach ( $this->pipes as $pipe ) {
			fclose( $pipe );
		}

		proc_close( $this->process );
	}
}