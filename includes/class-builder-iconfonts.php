<?php
/**
 * AxisBuilder Iconfonts
 *
 * Handles the Iconfonts Upload easily.
 *
 * @class       AB_Iconfonts
 * @package     AxisBuilder/Classes
 * @category    Class
 * @author      AxisThemes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AB_Iconfonts Class
 */
class AB_Iconfonts {

	public static $font_name = 'unknown';

	/**
	 * Hook in methods
	 */
	public static function init() {
		add_action( 'wp_head',    array( __CLASS__, 'iconfont_style' ) );
		add_action( 'admin_head', array( __CLASS__, 'iconfont_style' ) );
	}

	/**
	 * Outputs some styles in the wp <head> to load iconfonts font-face
	 */
	public function iconfont_style() {

		if ( ! current_user_can( 'manage_axisbuilder' ) ) return;
		?>
		<style type="text/css">
			/* This is sample only */
			.iconfonts {
				font-weight: normal;
			}
		</style>
		<?php
	}

	/**
	 * Check for capability
	 */
	public static function check_capability() {
		if ( ! current_user_can( 'manage_axisbuilder' ) ) {
			exit( __( 'Using this feature is reserved for Super Admins. You unfortunately don\'t have the necessary permissions.', 'axisbuilder' ) );
		}
	}

	/**
	 * Extract the zip file to get flat folder and remove the files that are not needed.
	 */
	public static function zip_flatten( $zipfile, $filter ) {

		@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

		$tempdir = AB_UPLOAD_DIR . '/axisfonts-temp';

		// Control temp directory?
		if ( is_dir( $tempdir ) ) {
			self::delete_files( $tempdir );
		} else {
			self::create_files( $tempdir );
		}

		// Create a ZipArchive instance
		$zip = new ZipArchive();

		// Open the Zip Archive
		if ( $zip->open( $zipfile ) === true ) {

			// Iterate the archive files array
			for ( $i = 0; $i < $zip->numFiles; $i++ ) {
				$entry = $zip->getNameIndex( $i );

				if ( ! empty( $filter ) ) {
					$delete  = true;
					$matches = array();

					foreach ( $filter as $regex ) {
						preg_match( '!' . $regex. '!', $entry, $matches );

						if ( ! empty( $matches ) ) {
							$delete = false;
							break;
						}
					}
				}

				// Skip directories and non matching files
				if ( ( substr( $entry, -1 ) == '/' ) || ( ! empty( $delete ) ) ) {
					continue;
				}

				$fp  = $zip->getStream( $entry );
				$ofp = fopen( $tempdir . '/' . basename( $entry ), 'w' );

				if ( ! $fp ) {
					exit( 'Unable to extract the file.' );
				}

				while ( ! feof( $fp ) ) {
					fwrite( $ofp, fread( $fp, 8192 ) );
				}

				fclose( $fp );
				fclose( $fop );
			}

			$zip->close();
		} else {
			exit( 'Failed to open the Zip Archive!' );
		}

		return true;
	}

	/**
	 * Iterate over xml file and extract the glyphs for the font.
	 */
	public static function create_config() {

	}

	/**
	 * Create files/directories
	 */
	public static function create_files( $folder ) {
		if ( is_dir( $folder ) ) {
			return true;
		}

		$created = wp_mkdir_p( trailingslashit( $folder ) );
		@chmod( $folder, 0777 );

		return $created;
	}

	/**
	 * Delete files/directories
	 */
	public static function delete_files( $folder ) {
		if ( is_dir( $folder ) ) {
			$scan = scandir( $folder );

			foreach ( $scan as $object ) {
				if ( $object != '.' && $object != '..' ) {
					unlink( $folder . '/' . $object );
				}
			}

			reset( $scan );
			rmdir( $folder );
		}
	}
}

AB_Iconfonts::init();
