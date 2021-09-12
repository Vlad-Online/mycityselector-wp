<?php
/**
 * Plugin Name:     MyCitySelector for WordPress
 * Plugin URI:      https://mycityselector.com
 * Description:     City selector plugin.
 * Author:          Vlad Smolensky
 * Author URI:      vlad@smolensky.info
 * Text Domain:     mcs
 * Domain Path:     /languages
 * Version:         0.0.1
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package         Mcs
 */

// If this file is called directly, abort.
use Mcs\WpModels\McsWidget;

if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! defined( 'MCS_PREFIX' ) ) {
	define( 'MCS_PREFIX', 'mcs_' );
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/hooks.php';

register_activation_hook( __FILE__, 'activate_mcs_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_mcs_plugin' );
register_uninstall_hook( __FILE__, 'uninstall_mcs_plugin' );

add_action( 'admin_menu', 'mcs_options_page' );
add_action( 'init', 'mcs_process' );
add_action( 'rest_api_init', 'mcs_register_routes' );
add_action( 'admin_enqueue_scripts', 'mcs_admin_enqueue_scripts' );
add_action( 'admin_init', 'mcs_register_options' );

//Widget registration
add_action( 'widgets_init', function () {
	register_widget( McsWidget::class );
	if ( ! is_admin() ) {
		wp_enqueue_style( 'mcs-widget-font', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap' );
		$assetFile = include( plugin_dir_path( __FILE__ ) . 'widget/build/index.asset.php' );
		wp_enqueue_script( 'mcs-widget-script', plugins_url( 'widget/build/index.js', __FILE__ ),
			$assetFile['dependencies'],
			$assetFile['version'],
			true );
	}
} );
