<?php
/**
 * AxisComposer Sidebars
 *
 * Handles the building of the Sidebars on the fly.
 *
 * @class       AC_Sidebars
 * @package     AxisComposer/Classes
 * @category    Class
 * @author      AxisThemes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AC_Sidebars Class
 */
class AC_Sidebars {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'widgets_admin_page', array( $this, 'output_sidebar_tmpl' ) );
		add_action( 'load-widgets.php', array( $this, 'add_custom_sidebars' ), 100 );
		add_action( 'widgets_init', array( $this, 'register_custom_sidebar' ), 1000 );
	}

	/**
	 * Output Sidebar Templates.
	 */
	public function output_sidebar_tmpl() {
		include_once( 'admin/views/html-admin-tmpl-sidebars.php' );
	}

	/**
	 * Remove all sidebars.
	 */
	public static function remove_all_sidebars() {
		delete_option( 'axiscomposer_custom_sidebars' );
	}

	/**
	 * Add a sidebar.
	 * @param string $name
	 */
	public static function add_sidebar( $name ) {
		$sidebars = array_unique( array_merge( get_option( 'axiscomposer_custom_sidebars', array() ), array( $name ) ) );
		update_option( 'axiscomposer_custom_sidebars', $sidebars );
	}

	/**
	 * Remove a sidebar.
	 * @param string $name
	 */
	public static function remove_sidebar( $name ) {
		$sidebars = array_diff( get_option( 'axiscomposer_custom_sidebars', array() ), array( $name ) );
		update_option( 'axiscomposer_custom_sidebars', $sidebars );
	}

	/**
	 * See if a sidebar exists.
	 * @param  string  $name
	 * @return boolean
	 */
	public static function has_sidebar( $name ) {
		return in_array( $name, get_option( 'axiscomposer_custom_sidebars', array() ) );
	}

	/**
	 * Add a sidebar if the POST variable is set.
	 */
	public function add_custom_sidebars() {
		if ( isset( $_POST['axiscomposer-add-sidebar'] ) && isset( $_POST['_ac_sidebar_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['_ac_sidebar_nonce'], 'axiscomposer_add_sidebar' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'axiscomposer' ) );
			}

			if ( ! current_user_can( 'manage_axiscomposer' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'axiscomposer' ) );
			}

			self::add_sidebar( $this->validate_sidebar( $_POST['axiscomposer-add-sidebar'] ) );
			wp_redirect( admin_url( 'widgets.php' ) );
		}
	}

	/**
	 * Validate sidebar name to prevent collisions.
	 * @param  string $sidebar_name Raw sidebar name.
	 * @return string $sidebar_name Valid sidebar name.
	 */
	public function validate_sidebar( $sidebar_name ) {
		$sidebar_name = ac_clean( $sidebar_name );

		$sidebar_exists = array();
		if ( ! empty( $GLOBALS['wp_registered_sidebars'] ) ) {
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
				$sidebar_exists[] = $sidebar['name'];
			}
		}

		if ( in_array( $sidebar_name, $sidebar_exists ) ) {
			$count        = substr( $sidebar_name, -1 );
			$rename       = is_numeric( $count ) ? ( substr( $sidebar_name, 0, -1 ) . ( (int) $count + 1 ) ) : ( $sidebar_name . ' - 1' );
			$sidebar_name = $this->validate_sidebar( $rename );
		}

		return $sidebar_name;
	}

	/**
	 * Register Custom Widget Area (Sidebar).
	 */
	public function register_custom_sidebar() {
		$sidebars = get_option( 'axiscomposer_custom_sidebars', array() );

		$args = apply_filters( 'axiscomposer_custom_widget_args', array(
			'before_widget' => '<aside id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '<span class="seperator extralight-border"></span></aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		) );

		foreach ( (array) $sidebars as $id => $name ) {
			$args['name']        = $name;
			$args['id']          = 'axiscomposer-sidebar-' . ++$id;
			$args['class']       = 'axiscomposer-custom-widgets-area';
			$args['description'] = sprintf( __( 'Custom Widget Area of the site - %s ', 'axiscomposer' ), $name );
			register_sidebar( $args );
		}
	}
}

new AC_Sidebars();
