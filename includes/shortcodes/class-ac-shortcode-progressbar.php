<?php
/**
 * Progress Bar Shortcode
 *
 * @extends  AC_Shortcode
 * @version  1.0.0
 * @package  AxisComposer/Shortcodes
 * @category Shortcodes
 * @author   AxisThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AC_Shortcode_Progressbar Class
 */
class AC_Shortcode_Progressbar extends AC_Shortcode {

	/**
	 * Class Constructor Method.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Initialise shortcode.
	 */
	public function init_shortcode() {
		$this->id                 = 'progressbar';
		$this->method_title       = __( 'Progress Bars', 'axiscomposer' );
		$this->method_description = __( 'Create some progress bars', 'axiscomposer' );
		$this->shortcode = array(
			'sort'    => 160,
			'type'    => 'content',
			'name'    => 'ac_progressbar',
			'icon'    => 'icon-progressbar',
			'image'   => AC()->plugin_url() . '/assets/images/content/progressbar.png', // Fallback if icon is missing :)
			'target'  => 'ac-target-insert',
			'tinyMCE' => array( 'disable' => true ),
		);
	}

	/**
	 * Frontend Shortcode Handle.
	 * @param  array  $atts      Array of attributes.
	 * @param  string $content   Text within enclosing form of shortcode element.
	 * @param  string $shortcode The shortcode found, when == callback name.
	 * @param  string $meta      Meta data.
	 * @return string            Returns the modified html string.
	 */
	public function shortcode_handle( $atts, $content = '', $shortcode = '', $meta = '' ) {

	}
}
