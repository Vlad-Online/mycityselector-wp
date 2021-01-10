<?php
declare( strict_types=1 );

use Phinx\Migration\AbstractMigration;

final class Init extends AbstractMigration {

	public function change() {
		$this->table( MCS_PREFIX . 'countries' )
		     ->addColumn( 'title', 'string', [ 'length' => 255 ] )
		     ->addColumn( 'subdomain', 'string', [ 'length' => 255 ] )
		     ->addIndex( 'subdomain' )
		     ->addColumn( 'published', 'boolean', [ 'default' => 0 ] )
		     ->addIndex( 'published' )
		     ->addColumn( 'ordering', 'smallinteger', [ 'default' => 100 ] )
		     ->addIndex( 'ordering' )
		     ->addColumn( 'code', 'string', [ 'length' => 2 ] )
		     ->addIndex( 'code' )
		     ->addColumn( 'domain', 'string', [ 'length' => 255, 'null' => true ] )
			//->addColumn( 'lat', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
			//->addColumn( 'lng', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
			 ->addColumn( 'default_city_id', 'integer', [ 'null' => true ] )
		     ->create();

		$this->table( MCS_PREFIX . 'provinces' )
		     ->addColumn( 'title', 'string', [ 'length' => 255 ] )
		     ->addColumn( 'country_id', 'integer' )
		     ->addForeignKey( 'country_id', MCS_PREFIX . 'countries' )
		     ->addColumn( 'subdomain', 'string', [ 'length' => 255 ] )
		     ->addIndex( 'subdomain' )
			//->addColumn( 'lat', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
			//->addColumn( 'lng', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
			 ->addColumn( 'published', 'boolean', [ 'default' => 0 ] )
		     ->addIndex( 'published' )
		     ->addColumn( 'ordering', 'smallinteger', [ 'default' => 100 ] )
		     ->addIndex( 'ordering' )
		     ->create();

		$this->table( MCS_PREFIX . 'cities' )
		     ->addColumn( 'title', 'string', [ 'length' => 255 ] )
		     ->addColumn( 'country_id', 'integer', [ 'null' => true ] )
		     ->addForeignKey( 'country_id', MCS_PREFIX . 'countries' )
		     ->addColumn( 'province_id', 'integer', [ 'null' => true ] )
		     ->addForeignKey( 'province_id', MCS_PREFIX . 'provinces' )
		     ->addColumn( 'subdomain', 'string', [ 'length' => 255 ] )
		     ->addIndex( 'subdomain' )
			//->addColumn( 'post_index', 'integer', [ 'null' => true ] )
			//->addColumn( 'lat', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
			//->addColumn( 'lng', 'decimal', [ 'precision' => 8, 'scale' => 2, 'null' => true ] )
			 ->addColumn( 'published', 'boolean', [ 'default' => 0 ] )
		     ->addIndex( 'published' )
		     ->addColumn( 'ordering', 'smallinteger', [ 'default' => 100 ] )
		     ->addIndex( 'ordering' )
		     ->create();

		$this->table( MCS_PREFIX . 'countries' )
		     ->addForeignKey( 'default_city_id', MCS_PREFIX . 'cities' )
		     ->update();

		/*$this->table( MCS_PREFIX . 'country_names' )
		     ->addColumn( 'country_id', 'integer' )
		     ->addForeignKey( 'country_id', MCS_PREFIX . 'countries' )
		     ->addColumn( 'lang_code', 'string', [ 'length' => 10 ] )
		     ->addIndex( 'lang_code' )
		     ->addColumn( 'name', 'string', [ 'length' => 100 ] )
		     ->create();

		$this->table( MCS_PREFIX . 'province_names' )
		     ->addColumn( 'province_id', 'integer' )
		     ->addForeignKey( 'province_id', MCS_PREFIX . 'provinces' )
		     ->addColumn( 'lang_code', 'string', [ 'length' => 10 ] )
		     ->addIndex( 'lang_code' )
		     ->addColumn( 'name', 'string', [ 'length' => 100 ] )
		     ->create();

		$this->table( MCS_PREFIX . 'city_names' )
		     ->addColumn( 'city_id', 'integer' )
		     ->addForeignKey( 'city_id', MCS_PREFIX . 'cities' )
		     ->addColumn( 'lang_code', 'string', [ 'length' => 10 ] )
		     ->addIndex( 'lang_code' )
		     ->addColumn( 'name', 'string', [ 'length' => 100 ] )
		     ->create();*/
	}
}
