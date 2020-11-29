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
