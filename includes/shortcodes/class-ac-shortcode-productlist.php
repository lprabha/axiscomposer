<?php
/**
 * Product List Shortcode
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
 * AC_Shortcode_Productlist Class
 */
class AC_Shortcode_Productlist extends AC_Shortcode {

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
		$this->id                 = 'productlist';
		$this->method_title       = __( 'Product List', 'axiscomposer' );
		$this->method_description = __( 'Displays a list of Product Entries', 'axiscomposer' );
		$this->shortcode = array(
			'sort'     => 520,
			'type'     => 'plugin',
			'name'     => 'ac_productlist',
			'icon'     => 'icon-productlist',
			'image'    => AC()->plugin_url() . '/assets/images/plugin/productlist.png', // Fallback if icon is missing :)
			'target'   => 'ac-target-insert',
			'tinyMCE'  => array( 'disable' => false ),
			'specific' => array(
				'screen' => array( 'product' ),
				'notice' => __( 'This element can only be used on single product pages.', 'axiscomposer' )
			)
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
