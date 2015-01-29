<?php
/**
 * AxisBuilder General Settings
 *
 * @class       AB_Settings_General
 * @package     AxisBuilder/Admin
 * @category    Admin
 * @author      AxisThemes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AB_Settings_General CLass
 */
class AB_Settings_General extends AB_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'axisbuilder' );

		add_filter( 'axisbuilder_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'axisbuilder_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'axisbuilder_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters( 'axisbuilder_general_settings', array(

			array( 'title' => __( 'General Options', 'axisbuilder' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'   => __( 'Debug Mode', 'axisbuilder' ),
				'desc'    => __( 'Enable the Builder Debug Mode', 'axisbuilder' ),
				'id'      => 'axisbuilder_debug_enabled',
				'type'    => 'checkbox',
				'default' => 'no',
			),

			array( 'type' => 'sectionend', 'id' => 'general_options')

		) );

		return apply_filters( 'axisbuilder_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		$settings = $this->get_settings();
		AB_Admin_Settings::save_fields( $settings );
	}
}

return new AB_Settings_General();
