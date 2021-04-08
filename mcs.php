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
require_once __DIR__.'/src/WpControllers/ProvincesController.php';
require_once __DIR__.'/src/WpControllers/CitiesController.php';
require_once __DIR__.'/src/WpControllers/FieldsController.php';
require_once __DIR__.'/src/WpControllers/FieldValuesController.php';
require_once __DIR__.'/src/WpControllers/CountryFieldValuesController.php';
require_once __DIR__.'/src/WpControllers/ProvinceFieldValuesController.php';
require_once __DIR__.'/src/WpControllers/CityFieldValuesController.php';
require_once __DIR__.'/src/WpControllers/OptionsController.php';

register_activation_hook( __FILE__, 'activate_mcs_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_mcs_plugin' );
register_uninstall_hook( __FILE__, 'uninstall_mcs_plugin' );

add_action( 'admin_menu', 'mcs_options_page' );
add_action( 'init', 'mcs_start_ob' );
add_action( 'admin_enqueue_scripts', 'mcs_admin_enqueue_scripts' );
add_action( 'admin_init', 'mcs_register_options' );
