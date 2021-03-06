<?php
/**
 * Contact Form Shortcode
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
 * AC_Shortcode_Contactform Class
 */
class AC_Shortcode_Contactform extends AC_Shortcode {

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
		$this->id                 = 'contactform';
		$this->method_title       = __( 'Contact Form', 'axiscomposer' );
		$this->method_description = __( 'Creates a customizable contact form', 'axiscomposer' );
		$this->shortcode = array(
			'sort'    => 250,
			'type'    => 'content',
			'name'    => 'ac_contactform',
			'icon'    => 'icon-contactform',
			'image'   => AC()->plugin_url() . '/assets/images/content/contactform.png', // Fallback if icon is missing :)
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
