<?php
/**
 * Section Shortcode
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
 * AC_Shortcode_Section Class
 */
class AC_Shortcode_Section extends AC_Shortcode {

	public static $section_close;
	public static $section_count = 0;

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
		$this->id        = 'axisbuilder_section';
		$this->title     = __( 'Color Section', 'axisbuilder' );
		$this->tooltip   = __( 'Creates a color section with custom styles', 'axisbuilder' );
		$this->shortcode = array(
			'sort'        => 11,
			'type'        => 'layout',
			'name'        => 'ab_section',
			'icon'        => 'icon-section',
			'image'       => AB()->plugin_url() . '/assets/images/layouts/section.png', // Fallback if icon is missing :)
			'target'      => 'axisbuilder-target-insert',
			'tinyMCE'     => array( 'disable' => true ),
			'drag-level'  => 1,
			'drop-level'  => 1,
			'html-render' => false
		);
	}

	/**
	 * Get Settings.
	 * @return array
	 */
	public function get_settings() {

		$this->elements = array(
			array(
				'name'     => __( 'Custom Background Color', 'axisbuilder' ),
				'desc'     => __( 'Select a custom background color for your Section here. Leave empty to use the default.', 'axisbuilder' ),
				'id'       => 'background_color',
				'type'     => 'colorpicker',
				'std'      => ''
			),
			array(
				'name'     => __( 'Custom Background Image', 'axisbuilder' ),
				'desc'     => __( 'Either upload a new, or choose an existing image from your media library. Leave empty if you want to use the background image of the color scheme defined above.', 'axisbuilder' ),
				'title'    => __( 'Insert Image', 'axisbuilder' ),
				'button'   => __( 'Insert', 'axisbuilder' ),
				'id'       => 'src',
				'std'      => '',
				'type'     => 'image'
			),
			array(
				'name'     => __( 'Background Attachment', 'axisbuilder' ),
				'desc'     => __( 'Background can either scroll with the page, be fixed or scroll with a parallax motion.', 'axisbuilder' ),
				'id'       => 'background_attachment',
				'std'      => 'scroll',
				'type'     => 'select',
				'required' => array( 'src', 'not', '' ),
				'subtype'  => array(
					__( 'Scroll', 'axisbuilder' )   => 'scroll',
					__( 'Fixed', 'axisbuilder' )    => 'fixed',
					__( 'Parallax', 'axisbuilder' ) => 'parallax'
				)
			),
			array(
				'name'     => __( 'Background Position', 'axisbuilder' ),
				'id'       => 'background_position',
				'std'      => 'top left',
				'type'     => 'select',
				'required' => array( 'src', 'not', '' ),
				'subtype'  => array(
					__( 'Top Left', 'axisbuilder' )       => 'top left',
					__( 'Top Center', 'axisbuilder' )     => 'top center',
					__( 'Top Right', 'axisbuilder' )      => 'top right',
					__( 'Bottom Left', 'axisbuilder' )    => 'bottom left',
					__( 'Bottom Center', 'axisbuilder' )  => 'bottom center',
					__( 'Bottom Right', 'axisbuilder' )   => 'bottom right',
					__( 'Center Left', 'axisbuilder' )    => 'center left',
					__( 'Center Center', 'axisbuilder' )  => 'center center',
					__( 'Center Right', 'axisbuilder' )   => 'center right'
				)
			),
			array(
				'name'     => __( 'Background Repeat', 'axisbuilder' ),
				'id'       => 'background_repeat',
				'std'      => 'no-repeat',
				'type'     => 'select',
				'required' => array( 'src', 'not', '' ),
				'subtype'  => array(
					__( 'No Repeat', 'axisbuilder' )         => 'no-repeat',
					__( 'Tile', 'axisbuilder' )              => 'repeat',
					__( 'Tile Horizontally', 'axisbuilder' ) => 'repeat-x',
					__( 'Tile Vertically', 'axisbuilder' )   => 'repeat-y',
					__( 'Stretch to Fit', 'axisbuilder' )    => 'stretch'
				)
			),
			// array(
			// 	'name'     => __( 'Background Video', 'axisbuilder' ),
			// 	'desc'     => __( 'You can also place a video as background for your section. Enter the URL to the Video. Currently supported are Youtube, Vimeo and direct linking of web-video files (mp4, webm, ogv)', 'axisbuilder' ) . '<br /><br />' . __( 'Working examples Vimeo &amp; YouTube:', 'axisbuilder' ) . '<br /><strong>http://vimeo.com/1084537</strong><br/><strong>https://www.youtube.com/watch?v=NJtPPbgdt7A</strong><br/><br/>',
			// 	'id'       => 'video',
			// 	'std'      => '',
			// 	'type'     => 'input'
			// ),
			// array(
			// 	'name'     => __( 'Video Aspect Ratio', 'axisbuilder' ),
			// 	'desc'     => __( 'In order to calculate the correct height and width for the video slide you need to enter a aspect ratio (width:height). usually: 16:9 or 4:3.', 'axisbuilder' ),
			// 	'id'       => 'video_ratio',
			// 	'std'      => '16:9',
			// 	'type'     => 'input',
			// 	'required' => array( 'video', 'not', '' ),
			// ),
			// array(
			// 	'name'     => __( 'Hide video on Mobile Devices?', 'axisbuilder' ),
			// 	'desc'     => __( 'You can chose to hide the video entirely on Mobile devices and instead display the Section Background image', 'axisbuilder' ) . '<br /><small>' . __( 'Most smartphones can\'t autoplay videos to prevent bandwidth problems for the user', 'axisbuilder' ) . '</small>',
			// 	'id'       => 'video_mobile_disabled',
			// 	'std'      => '',
			// 	'type'     => 'checkbox',
			// 	'required' => array( 'video', 'not', '' ),
			// ),
			array(
				'name'     => __( 'Section Minimum Height', 'axisbuilder' ),
				'desc'     => __( 'Define a minimum height for the section. Content within the section will be centered vertically within the section', 'axisbuilder' ),
				'id'       => 'min_height',
				'std'      => '',
				'type'     => 'select',
				'subtype'  => array(
					__( 'Use content within section to define Section height', 'axisbuilder' ) => 'default',
					// __( 'At least 100&percnt; of Browser Window height', 'axisbuilder' )       => '100',
					// __( 'At least 75&percnt; of Browser Window height', 'axisbuilder' )        => '75',
					// __( 'At least 50&percnt; of Browser Window height', 'axisbuilder' )        => '50',
					// __( 'At least 25&percnt; of Browser Window height', 'axisbuilder' )        => '25',
					__( 'Custom height in pixel', 'axisbuilder' )                              => 'custom'
				)
			),
			array(
				'name'     => __( 'Section custom height', 'axisbuilder' ),
				'desc'     => __( 'Define a minimum height for the section. Use a pixel value. eg: 500px', 'axisbuilder' ),
				'id'       => 'custom_min_height',
				'std'      => '500px',
				'type'     => 'input',
				'required' => array( 'min_height', 'equals', 'custom' ),
			),
			array(
				'name'     => __( 'Section Padding', 'axisbuilder' ),
				'desc'     => __( 'Define the sections top and bottom padding', 'axisbuilder' ),
				'id'       => 'padding',
				'std'      => 'default',
				'type'     => 'select',
				'subtype'  => array(
					__( 'No Padding', 'axisbuilder' )      => 'none',
					__( 'Small Padding', 'axisbuilder' )   => 'small',
					__( 'Large Padding', 'axisbuilder' )   => 'large',
					__( 'Default Padding', 'axisbuilder' ) => 'default',
				)
			),
			array(
				'name'     => __( 'Section Top Border Styling', 'axisbuilder' ),
				'desc'     => __( 'Chose a border styling for the top of your section', 'axisbuilder' ),
				'id'       => 'shadow',
				'std'      => 'no-shadow',
				'type'     => 'select',
				'subtype'  => array(
					__( 'Display simple top border', 'axisbuilder' ) => 'no-shadow',
					__( 'Display a small styling shadow at the top of the section', 'axisbuilder' ) => 'axisbuilder-shadow',
					__( 'No border styling', 'axisbuilder' ) => 'no-border-styling'
				)
			),
			array(
				'name'     => __( 'Section Bottom Border Styling', 'axisbuilder' ),
				'desc'     => __( 'Chose a border styling for the bottom of your section', 'axisbuilder' ),
				'id'       => 'bottom_border',
				'std'      => 'no-border-styling',
				'type'     => 'select',
				'subtype'  => array(
					__( 'No border styling', 'axisbuilder' ) => 'no-border-styling',
					__( 'Display a small arrow that points down to the next section', 'axisbuilder' ) => 'border-extra-arrow-down',
				)
			),
			array(
				'name'     => __( 'For Developers: Section ID', 'axisbuilder' ),
				'desc'     => __( 'Apply a custom ID Attribute to the section, so you can apply a unique style via CSS. This option is also helpful if you want to use anchor links to scroll to a sections when a link is clicked', 'axisbuilder' ) . '<br /><br />' . __( 'Use with caution and make sure to only use allowed characters. No special characters can be used.', 'axisbuilder' ),
				'id'       => 'id',
				'std'      => '',
				'type'     => 'input',
				'class'    => 'axisbuilder_input_id'
			)
		);
	}

	/**
	 * Editor Elements.
	 *
	 * This method defines the visual appearance of an element on the Builder canvas.
	 */
	public function editor_element( $params ) {
		extract( $params );

		$data['modal-title']       = $this->title;
		$data['modal-action']      = $this->shortcode['name'];
		$data['dragdrop-level']    = $this->shortcode['drag-level'];
		$data['shortcode-handler'] = $this->shortcode['name'];
		$data['shortcode-allowed'] = $this->shortcode['name'];

		$output = '<div class="axisbuilder-layout-section modal-animation axisbuilder-no-visual-updates axisbuilder-drag ' . $this->shortcode['name'] . '"' . ac_html_data_string( $data ) . '>';
			$output .= '<div class="axisbuilder-sorthandle menu-item-handle">';
				$output .= '<span class="axisbuilder-element-title">' . $this->title . '</span>';
				if ( isset( $this->shortcode['has_fields'] ) ) {
					$output .= '<a class="axisbuilder-edit edit-element-icon" href="#edit" title="' . __( 'Edit Section', 'axisbuilder' ) . '">' . __( 'Edit Section', 'axisbuilder' ) . '</a>';
				}
				$output .= '<a class="axisbuilder-trash trash-element-icon" href="#trash" title="' . __( 'Delete Section', 'axisbuilder' ) . '">' . __( 'Delete Section', 'axisbuilder' ) . '</a>';
				$output .= '<a class="axisbuilder-clone clone-element-icon" href="#clone" title="' . __( 'Clone Section',  'axisbuilder' ) . '">' . __( 'Clone Section',  'axisbuilder' ) . '</a>';
			$output .= '</div>';
			$output .= '<div class="axisbuilder-inner-shortcode axisbuilder-connect-sort axisbuilder-drop" data-dragdrop-level="' . $data['dragdrop-level'] . '">';
				$output .= '<textarea data-name="text-shortcode" rows="4" cols="20">' . ac_shortcode_data( $this->shortcode['name'], $content, $args ) . '</textarea>';
				if ( $content ) {
					$content = do_shortcode_builder( $content );
					$output .= $content;
				}
			$output .= '</div>';
		$output .= '</div>';

		return $output;
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
		global $axisbuilder_config;

		$params = array();
		$output = $background = '';

		self::$section_count ++;

		// Entire list of supported attributes and their defaults
		$pairs = array(
			'background_color'      => '',
			'src'                   => '',
			'background_attachment' => 'scroll',
			'background_position'   => 'top left',
			'background_repeat'     => 'no-repeat',
			'video'                 => '',
			'video_ratio'           => '16:9',
			'video_mobile_disabled' => '',
			'min_height'            => '',
			'custom_min_height'     => '500px',
			'padding'               => 'default',
			'shadow'                => 'no-shadow',
			'bottom_border'         => '',
			'id'                    => '',
			'custom_markup'         => '',
			'attachment'            => '',
			'attachment_size'       => ''
		);

		$atts = shortcode_atts( $pairs, $atts, $this->shortcode['name'] );

		extract( $atts );

		$class = 'axisbuilder-section section-padding-' . $padding . ' ' . $shadow . ' section-background-' . $background_attachment . '';

		$params['attach'] = '';
		$params['custom_markup'] = $meta['custom_markup'];
		$params['id'] = empty( $id ) ? 'axisbuilder-section-' . self::$section_count : sanitize_html_class( $id );

		// Set Attachment Image
		if ( ! empty( $attachment ) && ! empty( $attachment_size ) ) {
			$attachment_entry = get_post( $attachment );

			if ( ! empty( $attachment_size ) ) {
				$src = wp_get_attachment_image_src( $attachment_entry->ID, $attachment_size );
				$src = empty( $src[0] ) ? '' : $src[0];
			}
		} else {
			$attachment = false;
		}

		// Set Background Image
		if ( $src != '' ) {
			$background .= 'background-image: url(' . $src . '); ';
			$background .= 'background-position: ' . $background_position . '; ';
			$background .= ( $background_attachment == 'parallax' ) ? "background-attachment: scroll; " : 'background-attachment: ' . $background_attachment . '; ';

			if ( $background_repeat == 'stretch' ) {
				$class      .= 'axisbuilder-full-stretch';
				$background .= 'background-repeat: no-repeat; ';
			} else {
				$background .= 'background-repeat: ' . $background_repeat . '; ';
			}

			if ( $background_attachment == 'parallax' ) {
				$class .= 'axisbuilder-parallax-section';
				$speed  = apply_filters( 'axisbuilder_parallax_speed', '0.3', $params['id'] );
				$attachment_class  = ( $background_repeat == 'stretch' || $background_repeat == 'stretch' ) ? 'axisbuilder-full-stretch' : '';
				$params['attach'] .= '<div class="axisbuilder-parallax ' . $attachment_class . '" data-axisbuilder-parallax-ratio="' . $speed . '" style="' . $background . '"></div>';
				$background = '';
			}

			$params['data'] = 'data-section-background-repeat="' . $background_repeat . '"';
		}

		if ( $background_color != '' ) {
			$background .= 'background-color: ' . $background_color . ';';
		}

		if ( $background ) {
			$background = 'style="' . $background . '"';
		}

		$params['class'] = $class . ' ' . $meta['el_class'];
		$params['background'] = $background;
		$params['min_height'] = $min_height;
		$params['custom_min_height'] = $custom_min_height;
		$params['video'] = $video;
		$params['video_ratio'] = $video_ratio;
		$params['video_mobile_disabled'] = $video_mobile_disabled;

		if ( isset( $meta['counter'] ) ) {
			if ( $meta['counter'] == 0 ) {
				$params['main_container'] = true;
			}

			if ( $meta['counter'] == 0 ) {
				$params['close'] = false;
			}
		}

		$axisbuilder_config['layout_container'] = 'section';

		$output .= axisbuilder_new_section( $params );
		$output .= ac_remove_autop( $content, true );

		// Set Extra arrow element
		if ( strpos( $bottom_border, 'border-extra' ) !== false ) {
			$arrow_bg = empty( $background_color ) ? apply_filters( 'axisbuilder_background_color', '#fff' ) : $background_color;
			self::$section_close = '<div class="axisbuilder-extra-border-element ' . $bottom_border . '"><div class="arrow-wrap"><div class="arrow-inner" style="background-color: ' . $arrow_bg . '"></div></div></div>';
		} else {
			self::$section_close = '';
		}

		unset( $axisbuilder_config['layout_container'] );

		return $output;
	}
}

if ( ! function_exists( 'axisbuilder_new_section' ) ) :

/**
 * Structure New Section.
 */
function axisbuilder_new_section( $params = array() ) {
	global  $axisbuilder_config, $_axisbuilder_section_markup;
	$output = $post_class = $container_style = '';

	$defaults = array(
		'close'                 => true,
		'open'                  => true,
		'open_structure'        => true,
		'open_color_wrap'       => true,
		'main_container'        => false,
		'id'                    => '',
		'class'                 => '',
		'data'                  => '',
		'style'                 => '',
		'background'            => '',
		'video'                 => '',
		'video_ratio'           => '16:9',
		'video_mobile_disabled' => '',
		'min_height'            => '',
		'custom_min_height'     => '500px',
		'attach'                => '',
		'before_new'            => '',
		'custom_markup'         => ''
	);

	$defaults = array_merge( $defaults, $params );
	extract( $defaults );

	if ( $id ) {
		$id = 'id="' . $id . '"';
	}

	// Close the Section structure when previous element was a section ;)
	if ( $close ) {
		$output .= '</div></div>' . axisbuilder_section_markup_close() . '</div>' . AC_Shortcode_Section::$section_close . '</div>';
	}

	// Open the Section Structure
	if ( $open ) {
		$post_class = 'post-entry-' . get_the_ID();

		if ( $open_color_wrap ) {
			if ( ! empty( $min_height ) ) {
				$class .= ' section-min-height-' . $min_height;

				if ( $min_height == 'custom' && $custom_min_height != '' ) {
					$custom_min_height = (int) $custom_min_height;
					$container_style   = 'style="height: ' . $custom_min_height . 'px"';
				}
			}

			$output .= $before_new;
			$output .= '<div ' . $id . ' class="' . $class . ' container-wrap" ' . $background . $data . $style . '>';
			$output .= $attach;
			$output .= apply_filters( 'axisbuilder_add_section_container', '', $defaults );
		}
	}

	// This applies only for the sections. Other fullwidth elements don't need the container for centering ;)
	if ( $open_structure ) {
		if ( ! empty( $main_container ) ) {
			$markup = 'main';
			$_axisbuilder_section_markup = 'main';
		} else {
			$markup = 'div';
		}

		$output .= '<div class="container" ' . $container_style . '>';
		$output .= '<' . $markup . ' class="template-page content axisbuilder-content-full alpha units">';
		$output .= '<div class="post-entry post-entry-type-page ' . $post_class . '">';
		$output .= '<div class="entry-content-wrapper clearfix">';
	}

	return $output;
}

endif;

if ( ! function_exists( 'axisbuilder_section_markup_close' ) ) :

/**
 * Close Section Markup.
 */
function axisbuilder_section_markup_close() {
	global  $axisbuilder_config, $_axisbuilder_section_markup;

	if ( ! empty( $_axisbuilder_section_markup ) ) {
		$_axisbuilder_section_markup = false;
		$close_markup = '</main><!-- close content main element -->';
	} else {
		$close_markup = '</div><!-- close content main div -->';
	}

	return $close_markup;
}

endif;

if ( ! function_exists( 'axisbuilder_section_after_element_content' ) ) :

/**
 * Section after Element Content.
 * @param string $meta
 */
function axisbuilder_section_after_element_content( $meta, $second_id = '', $skip_second = false, $extra = '' ) {
	$output  = '</div><!-- Close Section -->';
	$output .= $extra;

	if ( empty( $skip_second ) ) {
		$output .= axisbuilder_new_section( array( 'close' => false, 'id' => $second_id ) );
	}

	return $output;
}

endif;
