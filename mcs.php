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
if (!defined('MCS_PREFIX')) {
	define( 'MCS_PREFIX', 'mcs_' );
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/hooks.php';
require_once __DIR__.'/src/WpControllers/CountriesController.php';
require_once __DIR__.'/src/WpControllers/CitiesController.php';

register_activation_hook( __FILE__, 'activate_mcs_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_mcs_plugin' );
register_uninstall_hook( __FILE__, 'uninstall_mcs_plugin' );

add_action( 'admin_menu', 'mcs_options_page' );
add_action( 'init', 'mcs_start_ob' );
add_action( 'admin_init', 'mcs_admin_init' );
add_action( 'admin_init', 'mcs_register_options' );

function mcs_options_page() {
	add_menu_page( 'MyCitySelector Plugin',
		'MyCitySelector',
		'manage_options',
		plugin_dir_path( __FILE__ ),
		'mcs_main_html',
		'',
		20
	);
	add_submenu_page(
		plugin_dir_path( __FILE__ ),
		'MyCitySelector Plugin Options',
		'MyCitySelector Plugin Options',
		'manage_options',
		plugin_dir_path( __FILE__ ) . '/options',
		'mcs_options_page_html'
	);
}

function mcs_start_ob() {
	//spl_autoload_register(function ());
	if ( ! is_admin() ) {
		ob_start( function ( $body ) {
			return false;
		} );
	}
}



/**
 * Pill field callback function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 */
function mcs_base_domain_cb() {
	$setting = get_option( 'mcs_base_domain' );
	?>
	<label>
		<input type="text" name="mcs_base_domain" value="<?php echo isset( $setting ) ? esc_attr( $setting ) : ''; ?>">
	</label>
	<p class="description" id="tagline-description"><?php _e( 'Base domain of your site, f.e.: example.com' ); ?></p>
	<?php
}

function mcs_main_html() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	</div>
	<?
}

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
