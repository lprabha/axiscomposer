<?php
/**
 * Admin View: Page - Status Report
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="updated axisbuilder-message">
	<p><?php _e( 'Please copy and paste this information in your ticket when contacting support:', 'axisbuilder' ); ?> </p>
	<p class="submit"><a href="#" class="button-primary debug-report"><?php _e( 'Get System Report', 'axisbuilder' ); ?></a>
	<a class="skip button-primary" href="http://docs.axisthemes.com/document/understanding-the-axisbuilder-system-status-report/" target="_blank"><?php _e( 'Understanding the Status Report', 'axisbuilder' ); ?></a></p>
	<div id="debug-report">
		<textarea readonly="readonly"></textarea>
		<p class="submit"><button id="copy-for-support" class="button-primary" href="#" data-tip="<?php _e( 'Copied!', 'axisbuilder' ); ?>"><?php _e( 'Copy for Support', 'axisbuilder' ); ?></button></p>
	</div>
</div>
<br/>
<table class="axisbuilder_status_table widefat" cellspacing="0" id="status">
	<thead>
		<tr>
			<th colspan="3" data-export-label="WordPress Environment"><?php _e( 'WordPress Environment', 'axisbuilder' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Home URL"><?php _e( 'Home URL', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The URL of your site\'s homepage.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php echo home_url(); ?></td>
		</tr>
		<tr>
			<td data-export-label="Site URL"><?php _e( 'Site URL', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The root URL of your site.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php echo site_url(); ?></td>
		</tr>
		<tr>
			<td data-export-label="AB Version"><?php _e( 'AB Version', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of AxisBuilder installed on your site.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_html( AB()->version ); ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Version"><?php _e( 'WP Version', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of WordPress installed on your site.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php bloginfo('version'); ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Multisite"><?php _e( 'WP Multisite', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php if ( is_multisite() ) echo '&#10004;'; else echo '&ndash;'; ?></td>
		</tr>
		<tr>
			<td data-export-label="WP Memory Limit"><?php _e( 'WP Memory Limit', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php
				$memory = axisbuilder_let_to_num( WP_MEMORY_LIMIT );

				if ( $memory < 67108864 ) {
					echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 64MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'axisbuilder' ), size_format( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
				} else {
					echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
				}
			?></td>
		</tr>
		<tr>
			<td data-export-label="WP Debug Mode"><?php _e( 'WP Debug Mode', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . '&#10004;' . '</mark>'; else echo '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
		</tr>
		<tr>
			<td data-export-label="Language"><?php _e( 'Language', 'axisbuilder' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The current language used by WordPress. Default = English', 'axisbuilder' ) . '">[?]</a>'; ?></td>
			<td><?php echo get_locale() ?></td>
		</tr>
	</tbody>
</table>
<table class="axisbuilder_status_table widefat" cellspacing="0" id="status">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><?php _e( 'Active Plugins', 'axisbuilder' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		foreach ( $active_plugins as $plugin ) {

			$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$dirname        = dirname( $plugin );
			$version_string = '';
			$network_string = '';

			if ( ! empty( $plugin_data['Name'] ) ) {

				// link the plugin name to the plugin url if available
				$plugin_name = esc_html( $plugin_data['Name'] );

				if ( ! empty( $plugin_data['PluginURI'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'axisbuilder' ) . '" target="_blank">' . $plugin_name . '</a>';
				}

				if ( strstr( $dirname, 'axisbuilder-' ) ) {

					if ( false === ( $version_data = get_transient( md5( $plugin ) . '_version_data' ) ) ) {
						$changelog = wp_remote_get( 'http://axisthemes.com/changelogs/' . $dirname . '/changelog.txt' );
						$cl_lines  = explode( "\n", wp_remote_retrieve_body( $changelog ) );
						if ( ! empty( $cl_lines ) ) {
							foreach ( $cl_lines as $line_num => $cl_line ) {
								if ( preg_match( '/^[0-9]/', $cl_line ) ) {

									$date         = str_replace( '.' , '-' , trim( substr( $cl_line , 0 , strpos( $cl_line , '-' ) ) ) );
									$version      = preg_replace( '~[^0-9,.]~' , '' ,stristr( $cl_line , "version" ) );
									$update       = trim( str_replace( "*" , "" , $cl_lines[ $line_num + 1 ] ) );
									$version_data = array( 'date' => $date , 'version' => $version , 'update' => $update , 'changelog' => $changelog );
									set_transient( md5( $plugin ) . '_version_data', $version_data, DAY_IN_SECONDS );
									break;
								}
							}
						}
					}

					if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '>' ) ) {
						$version_string = ' &ndash; <strong style="color:red;">' . esc_html( sprintf( _x( '%s is available', 'Version info', 'axisbuilder' ), $version_data['version'] ) ) . '</strong>';
					}

					if ( $plugin_data['Network'] != false ) {
						$network_string = ' &ndash; <strong style="color:black;">' . __( 'Network enabled', 'axisbuilder' ) . '</strong>';
					}
				}

				?>
				<tr>
					<td><?php echo $plugin_name; ?></td>
					<td class="help">&nbsp;</td>
					<td><?php echo sprintf( _x( 'by %s', 'by author', 'axisbuilder' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>

<?php do_action( 'axisbuilder_system_status_report' ); ?>

<script type="text/javascript">

	jQuery( 'a.help_tip' ).click( function() {
		return false;
	});

	jQuery( 'a.debug-report' ).click( function() {

		var report = '';

		jQuery( '#status thead, #status tbody' ).each(function(){

			if ( jQuery( this ).is('thead') ) {

				var label = jQuery( this ).find( 'th:eq(0)' ).data( 'export-label' ) || jQuery( this ).text();
				report = report + "\n### " + jQuery.trim( label ) + " ###\n\n";

			} else {

				jQuery('tr', jQuery( this ) ).each(function(){

					var label       = jQuery( this ).find( 'td:eq(0)' ).data( 'export-label' ) || jQuery( this ).find( 'td:eq(0)' ).text();
					var the_name    = jQuery.trim( label ).replace( /(<([^>]+)>)/ig, '' ); // Remove HTML
					var the_value   = jQuery.trim( jQuery( this ).find( 'td:eq(2)' ).text() );
					var value_array = the_value.split( ', ' );

					if ( value_array.length > 1 ) {

						// If value have a list of plugins ','
						// Split to add new line
						var output = '';
						var temp_line ='';
						jQuery.each( value_array, function( key, line ){
							temp_line = temp_line + line + '\n';
						});

						the_value = temp_line;
					}

					report = report + '' + the_name + ': ' + the_value + "\n";
				});

			}
		});

		try {
			jQuery( "#debug-report" ).slideDown();
			jQuery( "#debug-report textarea" ).val( report ).focus().select();
			jQuery( this ).fadeOut();
			return false;
		} catch( e ){
			console.log( e );
		}

		return false;
	});

	jQuery( document ).ready( function ( $ ) {
		$( '#copy-for-support' ).tipTip({
			'attribute':  'data-tip',
			'activation': 'click',
			'fadeIn':     50,
			'fadeOut':    50,
			'delay':      0
		});

		$( 'body' ).on( 'copy', '#copy-for-support', function ( e ) {
			e.clipboardData.clearData();
			e.clipboardData.setData( 'text/plain', $( '#debug-report textarea' ).val() );
			e.preventDefault();
		});

	});

</script>
