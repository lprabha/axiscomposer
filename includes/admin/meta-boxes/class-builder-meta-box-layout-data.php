<?php
/**
 * Layout Data
 *
 * Display the layout data meta box.
 *
 * @class       AB_Meta_Box_Layout_Data
 * @package     AxisBuilder/Admin/Meta Boxes
 * @category    Admin
 * @author      AxisThemes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AB_Meta_Box_Layout_Data Class
 */
class AB_Meta_Box_Layout_Data {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'axisbuilder_save_data', 'axisbuilder_meta_nonce' );

		?>
		<ul class="layout_data">

			<?php
				do_action( 'axisbuilder_layout_data_start', $post->ID );

				// Layout
				axisbuilder_wp_select( array( 'id' => 'layout', 'label' => __( 'Layout', 'axisbuilder' ), 'options' => array(
					'default'       => __( 'Default Layout', 'axisbuilder' ),
					'fullsize'      => __( 'No Sidebar', 'axisbuilder' ),
					'sidebar_left'  => __( 'Left Sidebar', 'axisbuilder' ),
					'sidebar_right' => __( 'Right Sidebar', 'axisbuilder' )
				), 'desc_tip' => true, 'description' => __( 'Select the desired Page layout.', 'axisbuilder' ) ) );

				// Sidebar
				axisbuilder_wp_select( array( 'id' => 'sidebar', 'label' => __( 'Sidebar Settings', 'axisbuilder' ), 'desc_tip' => true, 'description' => __( 'Choose a custom sidebar for this entry.', 'axisbuilder' ), 'options' => axisbuilder_get_registered_sidebars() ) );

				// Title Bar
				axisbuilder_wp_select( array( 'id' => 'header_title_bar', 'label' => __( 'Title Bar Settings', 'axisbuilder' ), 'options' => array(
					'default'              => __( 'Default Layout', 'axisbuilder' ),
					'title_bar_breadcrumb' => __( 'Display title and breadcrumbs', 'axisbuilder' ),
					'title_bar'            => __( 'Display only title', 'axisbuilder' ),
					'hidden_title_bar'     => __( 'Hide both', 'axisbuilder' )
				), 'desc_tip' => true, 'description' => __( 'Display the Title Bar with Page Title and Breadcrumb Navigation?', 'axisbuilder' ) ) );

				// Header Transparency
				axisbuilder_wp_select( array( 'id' => 'header_transparency', 'label' => __( 'Activate Header transparency', 'axisbuilder' ), 'options' => array(
					'default'                          => __( 'No transparency', 'axisbuilder' ),
					'header_transparent'               => __( 'Transparent Header', 'axisbuilder' ),
					'header_transparent header_glassy' => __( 'Transparent & Glassy Header', 'axisbuilder' )
				), 'desc_tip' => true, 'description' => __( 'If checked the header will be transparent and once the user scrolls down it will fade in.', 'axisbuilder' ) ) );

				// Footer Settings
				axisbuilder_wp_select( array( 'id' => 'footer', 'label' => __( 'Footer Settings', 'axisbuilder' ), 'options' => array(
					'footer_both' => __( 'Both Widgets and Socket', 'axisbuilder' ),
					'widget_only' => __( 'Only Widgets (No Socket)', 'axisbuilder' ),
					'socket_only' => __( 'Only Socket (No Widgets)', 'axisbuilder' ),
					'footer_none' => __( 'Don\'t Display Both', 'axisbuilder' )
				), 'desc_tip' => true, 'description' => __( 'Display the footer widgets?', 'axisbuilder' ) ) );

				do_action( 'axisbuilder_layout_data_end', $post->ID );
			?>
		</ul>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {

	}
}
