<?php
/**
 * Text Block Shortcode
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
 * AC_Shortcode_Textblock Class
 */
class AC_Shortcode_Textblock extends AC_Shortcode {

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
		$this->id        = 'textblock';
		$this->title     = __( 'Text Block', 'axiscomposer' );
		$this->tooltip   = __( 'Creates a simple text block', 'axiscomposer' );
		$this->shortcode = array(
			'sort'    => 60,
			'type'    => 'content',
			'name'    => 'ac_textblock',
			'icon'    => 'icon-textblock',
			'image'   => AC()->plugin_url() . '/assets/images/content/textblock.png', // Fallback if icon is missing :)
			'modal'   => array( 'modal-class' => 'normalscreen' ),
			'target'  => 'ac-target-insert',
			'tinyMCE' => array( 'disable' => true ),
		);
	}

	/**
	 * Get Settings.
	 * @return array
	 */
	public function get_settings() {

		$this->elements = array(
			// array(
			// 	'type'   => 'open_tab',
			// 	'nodesc' => true
			// ),

			// array(
			// 	'name'   => __( 'Content', 'axiscomposer' ),
			// 	'type'   => 'tab',
			// 	'nodesc' => true
			// ),

			array(
				'name'    => __( 'Content', 'axiscomposer' ),
				'desc'    => __( 'Enter some content for this textblock', 'axiscomposer' ),
				'id'      => 'content',
				'type'    => 'tinymce',
				'std'     => __( 'Click here to add your own text', 'axiscomposer' )
			),

			array(
				'name'    => __( 'Font Size', 'axiscomposer' ),
				'desc'    => __( 'Choose the font size of the text in px', 'axiscomposer' ),
				'type'    => 'number',
				'id'      => 'size',
				'min'     => '10',
				'max'     => '40',
				'std'     => '16'
			),

			// array(
			// 	'type'   => 'close_div',
			// 	'nodesc' => true
			// ),

			// array(
			// 	'name'   => __( 'Colors', 'axiscomposer' ),
			// 	'type'   => 'tab',
			// 	'nodesc' => true
			// ),

			array(
				'name'    => __( 'Font Colors', 'axiscomposer' ),
				'desc'    => __( 'Either use the themes default colors or apply some custom ones', 'axiscomposer' ),
				'id'      => 'font_color',
				'std'     => '',
				'type'    => 'select',
				'subtype' => array(
					__( 'Default', 'axiscomposer' ) => 'default',
					__( 'Define Custom Colors', 'axiscomposer' ) => 'custom'
				)
			),

			array(
				'name'     => __( 'Custom Font Color', 'axiscomposer' ),
				'desc'     => __( 'Select a custom font color. Leave empty to use the default', 'axiscomposer' ),
				'id'       => 'color',
				'std'      => '',
				'required' => array( 'font_color', 'equals', 'custom' ),
				'type'     => 'colorpicker'
			),

			// array(
			// 	'type'   => 'close_div',
			// 	'nodesc' => true
			// ),

			// array(
			// 	'type'   => 'close_div',
			// 	'nodesc' => true
			// ),
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
		$output = '';

		// Entire list of supported attributes and their defaults
		$pairs = array(
			'size'       => '',
			'font_color' => '',
			'color'      => ''
		);

		$atts = shortcode_atts( $pairs, $atts, $this->shortcode['name'] );

		extract( $atts );

		$class = empty( $meta['custom_class'] ) ? '' : $meta['custom_class'];

		if ( $size ) {
			$style = 'font-size: ' . $size . 'px; ';
		}

		if ( $font_color ) {
			$class .= 'ac-inherit-color';
			$style .= empty( $color ) ? '' : 'color: ' . $color . ';';
		}

		if ( $style ) {
			$style = 'style="' . $style . '"';
		}

		$output .= '<section class="axiscomposer textblock-section">';
		$output .= '<div class="axiscomposer-textblock ' . $class . '" ' . $style . '>' . ac_apply_autop( ac_remove_autop( $content ) ) . '</div>';
		$output .= '</section>';

		return $output;
	}
}