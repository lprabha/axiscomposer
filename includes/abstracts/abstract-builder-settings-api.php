<?php
/**
 * Abstract Settings API Class
 *
 * Admin Settings API used by Shortcodes and Integrations.
 *
 * @class       AB_Settings_API
 * @package     AxisBuilder/Abstracts
 * @category    Abstract Class
 * @author      AxisThemes
 * @since       1.0.0
 */
abstract class AB_Settings_API {

	/**
	 * The plugin ID. Used for option names.
	 * @var string
	 */
	public $plugin_id = 'axisbuilder_';

	/**
	 * Method ID.
	 * @var string
	 */
	public $id = '';

	/**
	 * Method title.
	 * @var string
	 */
	public $method_title = '';

	/**
	 * Method description.
	 * @var string
	 */
	public $method_description = '';

	/**
	 * 'yes' if the method is enabled
	 * @var string
	 */
	public $enabled;

	/**
	 * Setting values.
	 * @var array
	 */
	public $settings = array();

	/**
	 * Form option fields.
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * Validation errors.
	 * @var array
	 */
	public $errors = array();

	/**
	 * Sanitized fields after validation.
	 * @var array
	 */
	public $sanitized_fields = array();

	/**
	 * Admin Options
	 *
	 * Setup the gateway settings screen.
	 * Override this in your gateway.
	 *
	 * @since 1.0.0
	 */
	public function admin_options() { ?>

		<h3><?php echo ( ! empty( $this->method_title ) ) ? $this->method_title : __( 'Settings', 'woocommerce' ) ; ?></h3>

		<?php echo ( ! empty( $this->method_description ) ) ? wpautop( $this->method_description ) : ''; ?>

		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
		</table><?php
	}

	/**
	 * Initialise Settings Form Fields
	 *
	 * Add an array of fields to be displayed
	 * on the gateway's settings screen.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function init_form_fields() {}

	/**
	 * Get the form fields after they are initialized
	 *
	 * @return array of options
	 */
	public function get_form_fields() {
		return apply_filters( 'axisbuilder_settings_api_form_fields_' . $this->id, $this->form_fields );
	}

	/**
	 * Admin Panel Options Processing
	 * - Saves the options to the DB
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	public function process_admin_options() {

		$this->validate_settings_fields();

		if ( count( $this->errors ) > 0 ) {
			$this->display_errors();
			return false;
		} else {
			update_option( $this->plugin_id . $this->id . '_settings', apply_filters( 'axisbuilder_settings_api_sanitized_fields_' . $this->id, $this->sanitized_fields ) );
			$this->init_settings();
			return true;
		}
	}

	/**
	 * Display admin error messages.
	 *
	 * @since 1.0.0
	 */
	public function display_errors() {}

	/**
	 * Initialise Settings
	 *
	 * Store all settings in a single database entry
	 * and make sure the $settings array is either the default
	 * or the settings stored in the database.
	 *
	 * @since 1.0.0
	 * @uses  get_option(), add_option()
	 */
	public function init_settings() {

		// Load form_field settings
		$this->settings = get_option( $this->plugin_id . $this->id . '_settings', null );

		if ( ! $this->settings || ! is_array( $this->settings ) ) {

			$this->settings = array();

			// If there are no settings defined, load defaults
			if ( $form_fields = $this->get_form_fields() ) {

				foreach ( $form_fields as $k => $v ) {
					$this->settings[ $k ] = isset( $v['default'] ) ? $v['default'] : '';
				}
			}
		}

		if ( ! empty( $this->settings ) && is_array( $this->settings ) ) {
			$this->settings = array_map( array( $this, 'format_settings' ), $this->settings );
			$this->enabled  = isset( $this->settings['enabled'] ) && $this->settings['enabled'] == 'yes' ? 'yes' : 'no';
		}
	}

	/**
	 * get_option function.
	 *
	 * Gets and option from the settings API, using defaults if necessary to prevent undefined notices.
	 *
	 * @param  string $key
	 * @param  mixed  $empty_value
	 * @return mixed  The value specified for the option or a default value for the option
	 */
	public function get_option( $key, $empty_value = null ) {

		if ( empty( $this->settings ) ) {
			$this->init_settings();
		}

		// Get option default if unset
		if ( ! isset( $this->settings[ $key ] ) ) {
			$form_fields            = $this->get_form_fields();
			$this->settings[ $key ] = isset( $form_fields[ $key ]['default'] ) ? $form_fields[ $key ]['default'] : '';
		}

		if ( ! is_null( $empty_value ) && empty( $this->settings[ $key ] ) ) {
			$this->settings[ $key ] = $empty_value;
		}

		return $this->settings[ $key ];
	}

	/**
	 * Decode values for settings.
	 *
	 * @param  mixed $value
	 * @return array
	 */
	public function format_settings( $value ) {
		return is_array( $value ) ? $value : $value;
	}

	/**
	 * Generate Settings HTML.
	 *
	 * Generate the HTML for the fields on the "settings" screen.
	 *
	 * @param  array $form_fields (default: array())
	 * @since  1.0.0
	 * @uses   method_exists()
	 * @return string the html for the settings
	 */
	public function generate_settings_html( $form_fields = array() ) {

		if ( empty( $form_fields ) ) {
			$form_fields = $this->get_form_fields();
		}

		$html = '';
		foreach ( $form_fields as $k => $v ) {

			if ( ! isset( $v['type'] ) || ( $v['type'] == '' ) ) {
				$v['type'] = 'text'; // Default to "text" field type.
			}

			if ( method_exists( $this, 'generate_' . $v['type'] . '_html' ) ) {
				$html .= $this->{'generate_' . $v['type'] . '_html'}( $k, $v );
			} else {
				$html .= $this->{'generate_text_html'}( $k, $v );
			}
		}

		echo $html;
	}

	/**
	 * Get HTML for tooltips
	 *
	 * @param  array $data
	 * @return string
	 */
	public function get_tooltip_html( $data ) {
		if ( $data['desc_tip'] === true ) {
			$tip = $data['description'];
		} elseif ( ! empty( $data['desc_tip'] ) ) {
			$tip = $data['desc_tip'];
		} else {
			$tip = '';
		}

		return $tip ? '<img class="help_tip" data-tip="' . esc_attr( axisbuilder_sanitize_tooltip( $tip ) ) . '" src="' . AB()->plugin_url() . '/assets/images/help.png" height="16" width="16" />' : '';
	}

	/**
	 * Get HTML for descriptions
	 *
	 * @param  array $data
	 * @return string
	 */
	public function get_description_html( $data ) {

		if ( $data['desc_tip'] === true ) {
			$description = '';
		} elseif ( ! empty( $data['desc_tip'] ) ) {
			$description = $data['description'];
		} elseif ( ! empty( $data['description'] ) ) {
			$description = $data['description'];
		} else {
			$description = '';
		}

		return $description ? '<p class="description">' . wp_kses_post( $description ) . '</p>' . "\n" : '';
	}

	/**
	 * Get custom attributes
	 *
	 * @param  array $data
	 * @return string
	 */
	public function get_custom_attribute_html( $data ) {

		$custom_attributes = array();

		if ( ! empty( $data['custom_attributes'] ) && is_array( $data['custom_attributes'] ) ) {

			foreach ( $data['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		return implode( ' ', $custom_attributes );
	}

	/**
	 * Generate Text Input HTML.
	 *
	 * @param  mixed $key
	 * @param  mixed $data
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_text_html( $key, $data ) {

		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<input class="input-text regular-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Password Input HTML.
	 *
	 * @param  mixed $key
	 * @param  mixed $data
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_password_html( $key, $data ) {
		$data['type'] = 'password';
		return $this->generate_text_html( $key, $data );
	}

	/**
	 * Generate Color Picker Input HTML.
	 *
	 * @param  mixed $key
	 * @param  mixed $data
	 * @since  1.0.0
	 * @return string
	 */
	public function generate_color_html( $key, $data ) {

		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<div class="color_box">
						<input class="colorpick <?php echo esc_attr( $data['class'] ); ?>" type="text" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="<?php echo esc_attr( $this->get_option( $key ) ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> />
						<div id="colorPickerDiv_<?php echo esc_attr( $field ); ?>" class="colorpickdiv" style="z-index: 100; background: #eee; border: 1px solid #ccc; position: absolute; display: none;"></div>
					</div>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Generate Textarea HTML.
	 *
	 * @param mixed $key
	 * @param mixed $data
	 * @since 1.0.0
	 * @return string
	 */
	public function generate_textarea_html( $key, $data ) {

		$field    = $this->plugin_id . $this->id . '_' . $key;
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<textarea rows="3" cols="20" class="input-text wide-input <?php echo esc_attr( $data['class'] ); ?>" type="<?php echo esc_attr( $data['type'] ); ?>" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php disabled( $data['disabled'], true ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo esc_textarea( $this->get_option( $key ) ); ?></textarea>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}
}
