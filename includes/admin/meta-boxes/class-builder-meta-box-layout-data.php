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
	 * Output the meta box
	 */
	public static function output( $post ) {
		wp_nonce_field( 'axisbuilder_save_data', 'axisbuilder_meta_nonce' );

		?>
		<ul class="layout_data">

			<?php
				do_action( 'axisbuilder_layout_data_start', $post->ID );

				// Layout
				axisbuilder_wp_select( array( 'id' => 'layout', 'class' => 'select side show_if_sidebar', 'label' => __( 'Layout Settings', 'axisbuilder' ), 'options' => array(
					'default'       => __( 'Default Layout', 'axisbuilder' ),
					'fullsize'      => __( 'No Sidebar', 'axisbuilder' ),
					'sidebar_left'  => __( 'Left Sidebar', 'axisbuilder' ),
					'sidebar_right' => __( 'Right Sidebar', 'axisbuilder' )
				), 'desc_side' => true, 'desc_tip' => false, 'desc_class' => 'side', 'description' => __( 'Select the specific layout for this entry.', 'axisbuilder' ) ) );

				// Sidebar
				axisbuilder_wp_select( array( 'id' => 'sidebar', 'class' => 'select side', 'label' => __( 'Sidebar Settings', 'axisbuilder' ), 'desc_side' => true, 'desc_tip' => false, 'desc_class' => 'side', 'description' => __( 'Choose a custom sidebar for this entry.', 'axisbuilder' ), 'options' => axisbuilder_get_registered_sidebars( array( 'default' => 'Default Sidebar' ), array( 'Display Everywhere' ) ) ) );

				// Footer Settings
				axisbuilder_wp_select( array( 'id' => 'footer', 'class' => 'select side', 'label' => __( 'Footer Settings', 'axisbuilder' ), 'options' => array(
					'default'     => __( 'Default Socket and Widgets', 'axisbuilder' ),
					'footer_both' => __( 'Both Socket and Widgets', 'axisbuilder' ),
					'widget_only' => __( 'Only Widgets (No Socket)', 'axisbuilder' ),
					'socket_only' => __( 'Only Socket (No Widgets)', 'axisbuilder' ),
					'footer_hide' => __( 'Hide Socket and Widgets', 'axisbuilder' )
				), 'desc_side' => true, 'desc_tip' => false, 'desc_class' => 'side', 'description' => __( 'Display the socket and footer widgets?', 'axisbuilder' ) ) );

				// Title Bar
				axisbuilder_wp_select( array( 'id' => 'header_title_bar', 'class' => 'select side', 'label' => __( 'Title Bar Settings', 'axisbuilder' ), 'options' => array(
					'default'          => __( 'Default Title and Breadcrumb', 'axisbuilder' ),
					'header_crumb_bar' => __( 'Display Title and Breadcrumb', 'axisbuilder' ),
					'header_title_bar' => __( 'Display Title (No Breadcrumb)', 'axisbuilder' ),
					'hidden_title_bar' => __( 'Hide both Title and Breadcrumb', 'axisbuilder' )
				), 'desc_side' => true, 'desc_tip' => false, 'desc_class' => 'side', 'description' => __( 'Display the Title Bar with Page Title and Breadcrumb Navigation?', 'axisbuilder' ) ) );

				// Header Transparency
				axisbuilder_wp_select( array( 'id' => 'header_transparency', 'class' => 'select side', 'label' => __( 'Header visibility and transparency', 'axisbuilder' ), 'options' => array(
					'default'                          => __( 'No transparency', 'axisbuilder' ),
					'header_transparent'               => __( 'Transparent Header', 'axisbuilder' ),
					'header_transparent header_glassy' => __( 'Transparent & Glassy Header', 'axisbuilder' )
				), 'desc_side' => true, 'desc_tip' => false, 'desc_class' => 'side', 'description' => __( 'Several options to change the header transparency and visibility on this page.', 'axisbuilder' ) ) );

				do_action( 'axisbuilder_layout_data_end', $post->ID );
			?>
		</ul>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id ) {

		// Save the layout settings ;)
		$layout_post_meta = array( 'layout', 'sidebar', 'header_title_bar', 'footer', 'header_transparency' );

		foreach ( $layout_post_meta as $post_meta ) {
			if ( isset( $_POST[ $post_meta ] ) ) {
				update_post_meta( $post_id, $post_meta, $_POST[ $post_meta ] );
			}
		}
	}
}
