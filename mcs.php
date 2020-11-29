<?php
/**
 * Plugin Name:     MyCitySelector
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     City selector plugin.
 * Author:          Vlad Smolensky
 * Author URI:      YOUR SITE HERE
 * Text Domain:     mcs
 * Domain Path:     /languages
 * Version:         0.0.1
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package         Mcs
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, 'activate_mcs_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_mcs_plugin' );
register_uninstall_hook( __FILE__, 'uninstall_mcs_plugin' );

add_action( 'admin_menu', 'mcs_options_page' );
add_action( 'init', 'mcs_start_ob' );

function mcs_options_page() {
	add_menu_page( 'MyCitySelector Plugin',
		'MyCitySelector',
		'manage_options',
		plugin_dir_path( __FILE__ ) . 'admin/view.php',
		null,
		'',
		20
	);
}

function activate_mcs_plugin() {

}

function deactivate_mcs_plugin() {

}

function uninstall_mcs_plugin() {

}

function mcs_start_ob() {
	//spl_autoload_register(function ());
	if (!is_admin()) {
		ob_start(function ($body) {
			return false;
		});
	}
}

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'mcs_settings_init' );


function mcs_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'mcs_messages', 'mcs_message', __( 'Settings Saved', 'mcs' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'mcs_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "mcs"
			settings_fields( 'mcs' );
			// output setting sections and their fields
			// (sections are registered for "mcs", each field is registered to a specific section)
			do_settings_sections( 'mcs' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}
