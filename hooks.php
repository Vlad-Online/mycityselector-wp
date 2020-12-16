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

function mcs_admin_init() {

}
