<?php
declare( strict_types=1 );

use Phinx\Migration\AbstractMigration;

final class Init extends AbstractMigration {
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change(): void {
		$this->table( MCS_PREFIX . 'countries' )
		     ->addColumn( 'subdomain', 'string', [ 'length' => 255 ] )
		     ->addColumn( 'published', 'boolean', [ 'default' => 0 ] )
		     ->addColumn( 'ordering', 'smallinteger', [ 'default' => 100 ] )
		     ->addColumn( 'code', 'string', [ 'length' => 2 ] )
		     ->addColumn( 'domain', 'string', [ 'length' => 255, 'null' => true ] )
		     ->addColumn( 'lat', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
		     ->addColumn( 'lng', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
		     ->addColumn( 'default_city_id', 'integer', [ 'null' => true ] )
		     ->create();

		$this->table( MCS_PREFIX . 'provinces' )
		     ->addColumn( 'country_id', 'integer' )
		     ->addForeignKey( 'country_id', MCS_PREFIX . 'countries' )
		     ->addColumn( 'subdomain', 'string', [ 'length' => 255 ] )
		     ->addColumn( 'lat', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
		     ->addColumn( 'lng', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
		     ->addColumn( 'published', 'boolean', [ 'default' => 0 ] )
		     ->addColumn( 'ordering', 'smallinteger', [ 'default' => 100 ] )
		     ->create();

		$this->table( MCS_PREFIX . 'cities' )
		     ->addColumn( 'country_id', 'integer', [ 'null' => true ] )
		     ->addForeignKey( 'country_id', MCS_PREFIX . 'countries' )
		     ->addColumn( 'province_id', 'integer', [ 'null' => true ] )
		     ->addForeignKey( 'province_id', MCS_PREFIX . 'provinces' )
		     ->addColumn( 'subdomain', 'string', [ 'length' => 255 ] )
		     ->addColumn( 'post_index', 'integer', [ 'null' => true ] )
		     ->addColumn( 'lat', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
		     ->addColumn( 'lng', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
		     ->addColumn( 'published', 'boolean', [ 'default' => 0 ] )
		     ->addColumn( 'ordering', 'smallinteger', [ 'default' => 100 ] )
		     ->create();

		$this->table( MCS_PREFIX . 'countries' )
		     ->addForeignKey( 'default_city_id', MCS_PREFIX . 'cities' )
		     ->update();
	}
}
