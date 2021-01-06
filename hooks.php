<?php

use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

function activate_mcs_plugin() {
	mcs_migrate();
}

function mcs_migrate() {
	$cwd = getcwd();
	chdir( __DIR__ );
	$phinx = new PhinxApplication();
	$phinx->setAutoExit( false );
	$phinx->setCatchExceptions( false );
	try {
		$phinx->run( new StringInput( 'migrate' ), new ConsoleOutput() );
	} catch ( Exception $exception ) {

	}
	chdir( $cwd );
}

function deactivate_mcs_plugin() {

}

function uninstall_mcs_plugin() {

}

function mcs_register_options() {
	// Register a new setting for "mcs" page.
	register_setting( 'mcs', 'mcs_base_domain', [
		'type'        => 'string',
		'description' => 'Base domain of your site, f.e.: example.com',
		'default'     => ''
	] );

	// Register a new section in the "mcs" page.
	add_settings_section(
		'mcs_section_base',
		'Base options',
		null,
		'mcs'
	);

	// Register a new field in the "mcs_section_developers" section, inside the "mcs" page.
	add_settings_field(
		'mcs_base_domain', // As of WP 4.6 this value is used only internally.
		'Base domain',
		'mcs_base_domain_cb',
		'mcs',
		'mcs_section_base'
	);
}

function mcs_admin_enqueue_scripts() {
	wp_enqueue_script( 'mcs-bundle', 'http://localhost:3000/static/js/bundle.js', [], null, true );
	wp_localize_script( 'mcs-bundle', 'wpApiSettings', [
		'root'  => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' )
	] );
	wp_enqueue_script( 'mcs-chunk-0', 'http://localhost:3000/static/js/0.chunk.js', [], null, true );
	wp_enqueue_script( 'mcs-chunk-main', 'http://localhost:3000/static/js/main.chunk.js', [], null, true );
}

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
	wp_enqueue_style( 'mcs-styles', plugin_dir_url( '' ) . 'mcs/admin/src/App.css' );
/*	wp_add_inline_style( 'admin-bar', "
	#root {
    	all: initial;
		* {
			all: unset;
		}
}" );*/
	?>
	<div class="wrap">
		<h1><?= esc_html( get_admin_page_title() ); ?></h1>
		<noscript>You need to enable JavaScript to run this app.</noscript>
		<div id="root"></div>
	</div>
	<?php
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
		<h1><?= esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "mcs"
			settings_fields( 'mcs' );
			// output setting sections and their fields
			// (sections are registered for "mcs", each field is registered to a specific section)
			do_settings_sections( 'mcs' );
			// output save settings button
			submit_button( 'Save my Settings' );
			?>
		</form>
	</div>
	<?php
}
