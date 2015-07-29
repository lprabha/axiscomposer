<?php
/**
 * Button Shortcode
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
 * AC_Shortcode_Button Class
 */
class AC_Shortcode_Button extends AC_Shortcode {

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
		$this->id                 = 'button';
		$this->method_title       = __( 'Button', 'axiscomposer' );
		$this->method_description = __( 'Creates a colored button', 'axiscomposer' );
		$this->shortcode = array(
			'sort'    => 120,
			'type'    => 'content',
			'name'    => 'ac_button',
			'icon'    => 'icon-button',
			'image'   => AC()->plugin_url() . '/assets/images/content/button.png', // Fallback if icon is missing :)
			'target'  => 'ac-target-insert',
			'tinyMCE' => array( 'disable' => false ),
		);
	}

	/**
	 * Initialise Shortcode Settings Form Fields.
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'label' => array(
				'title'             => __( 'Button Label', 'axiscomposer' ),
				'description'       => __( 'This option lets you define button label.', 'axiscomposer' ),
				'default'           => __( 'Add your button label here.', 'axiscomposer' ),
				'type'              => 'text',
				'desc_tip'          => true
			),
			'link' => array(
				'title'             => __( 'Button Link', 'axiscomposer' ),
				'description'       => __( 'This option lets you enter button link.', 'axiscomposer' ),
				'type'              => 'text',
				'desc_tip'          => true,
				'default'           => ''
			),
			'size' => array(
				'title'             => __( 'Button Size', 'axiscomposer' ),
				'description'       => __( 'This sets the custom size of the button.', 'axiscomposer' ),
				'default'           => 'medium',
				'type'              => 'select',
				'class'             => 'ac-enhanced-select',
				'css'               => 'min-width: 350px;',
				'desc_tip'          => true,
				'options'           => array(
					'small'  => __( 'Small', 'axiscomposer' ),
					'medium' => __( 'Medium', 'axiscomposer' ),
					'large'  => __( 'Large', 'axiscomposer' )
				)
			),
			'position' => array(
				'title'             => __( 'Button Position', 'axiscomposer' ),
				'description'       => __( 'This sets the custom alignment of the button.', 'axiscomposer' ),
				'default'           => 'center',
				'type'              => 'select',
				'class'             => 'ac-enhanced-select',
				'css'               => 'min-width: 350px;',
				'desc_tip'          => true,
				'options'           => array(
					'left'   => __( 'Left Align', 'axiscomposer' ),
					'center' => __( 'Center Align', 'axiscomposer' ),
					'right'  => __( 'Right Align', 'axiscomposer' )
				)
			),
			'iconfont' => array(
				'title'             => __( 'Button Icon', 'axiscomposer' ),
				'description'       => __( 'Select an icon for your Button below.', 'axiscomposer' ),
				'type'              => 'iconfont',
				'default'           => 'entypo-fontello',
				'options'           => ac_get_iconfont_charlist()
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
		extract( shortcode_atts( array(
			'label'         => '',
			'link'          => '',
			'size'          => '',
			'position'      => '',
			'iconfont'      => ''
		), $atts, $this->shortcode['name'] ) );

		// Don't display if button label is missing
		if ( empty( $label ) ) {
			return;
		}

		$custom_class = empty( $meta['custom_class'] ) ? '' : $meta['custom_class'];

		ob_start();
		?>
		<section class="axiscomposer button-section">
			<div class="ac-button <?php echo esc_attr( $custom_class ); ?>">
				<a href="<?php echo esc_attr( $link ); ?>" class="<?php echo esc_attr( $size ); ?> <?php echo esc_attr( $position ); ?>" title="<?php echo esc_attr( $label ); ?>"><?php echo esc_attr( $label ); ?></a>
			</div>
		</section>
		<?php

		return ob_get_clean();
	}
}
