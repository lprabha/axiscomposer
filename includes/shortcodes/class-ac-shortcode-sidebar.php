<?php
/**
 * Sidebar or Widget-Area Shortcode
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
 * AC_Shortcode_Sidebar Class
 */
class AC_Shortcode_Sidebar extends AC_Shortcode {

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
		$this->id        = 'sidebar';
		$this->title     = __( 'Widget Area', 'axiscomposer' );
		$this->tooltip   = __( 'Display one of the themes widget areas', 'axiscomposer' );
		$this->shortcode = array(
			'sort'    => 330,
			'type'    => 'content',
			'name'    => 'ac_sidebar',
			'icon'    => 'icon-sidebar',
			'image'   => AC()->plugin_url() . '/assets/images/content/sidebar.png', // Fallback if icon is missing :)
			'target'  => 'ac-target-insert',
			'tinyMCE' => array( 'instantInsert' => '[ac_sidebar widget_area="Displayed Everywhere"]' ),
		);
	}

	/**
	 * Editor Elements.
	 *
	 * This method defines the visual appearance of an element on the Builder canvas.
	 */
	public function editor_element( $params ) {

		// Fetch all registered sidebars
		$sidebars = ac_get_registered_sidebars();

		if ( empty( $params['args']['widget_area'] ) ) {
			$params['args']['widget_area'] = reset( $sidebars );
		}

		$params['innerHtml']  = '';
		$params['innerHtml'] .= ( isset( $this->shortcode['image'] ) && ! empty( $this->shortcode['image'] ) ) ? '<img src="' . $this->shortcode['image'] . '" alt="' . $this->title . '" />' : '<i class="' . $this->shortcode['icon'] . '"></i>';
		$params['innerHtml'] .= '<div class="ac-element-label">' . $this->title . '</div>';
		$params['innerHtml'] .= ac_select_html( 'axiscomposer_sidebar', array(
			'default'           => esc_html( $params['args']['widget_area'] ),
			'class'             => 'ac-recalc-shortcode ac-enhanced-select',
			'options'           => $sidebars,
			'custom_attributes' => array(
				'data-attr' => 'widget_area'
			)
		) );

		return (array) $params;
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

		if ( ! isset( $atts['widget_area'] ) ) {
			return $output;
		}

		if ( is_dynamic_sidebar( $atts['widget_area'] ) ) {
			ob_start();
			dynamic_sidebar( $atts['widget_area'] );
			$output = ac_remove_autop( ob_get_clean(), true );
		}

		if ( $output ) {
			$output = '<div class="axiscomposer ac_sidebar clearfix">' . $output . '</div>';
		}

		return $output;
	}
}
