<?php
/**
 * Product Grid Shortcode
 *
 * @extends     AC_Shortcode
 * @package     AxisComposer/Shortcodes
 * @category    Shortcodes
 * @author      AxisThemes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AC_Shortcode_Productgrid Class
 */
class AC_Shortcode_Productgrid extends AC_Shortcode {

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
		$this->id                 = 'productgrid';
		$this->method_title       = __( 'Product Grid', 'axiscomposer' );
		$this->method_description = __( 'Displays a grid of Product Entries', 'axiscomposer' );
		$this->shortcode = array(
			'sort'    => 510,
			'type'    => 'plugin',
			'name'    => 'ac_productgrid',
			'icon'    => 'icon-productgrid',
			'image'   => AC()->plugin_url() . '/assets/images/plugin/productgrid.png', // Fallback if icon is missing :)
			'target'  => 'ac-target-insert',
			'tinyMCE' => array( 'disable' => false ),
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
