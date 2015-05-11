<?php
/**
 * Author: Stefan Hueg
 * Copyright: Codengine @ 2015
 */

require_once('ArtisanDefaultMigrationTest.php');

class ArtisanCustomMigrationTest extends ArtisanDefaultMigrationTest {
	protected $testType = 'custom';

	public function getEnvironmentSetUp($app)
	{
		parent::getEnvironmentSetUp($app);
		$app['config']->set('database.connections.artisantest_custom_two', $app['config']->get('database.connections.artisantest_custom'));
	}

	public function testMigratorUsesSpecificDatabaseConnection()
	{
		$this->executeDefaultMigration(array(
			'--database' => 'artisantest_custom_two'
		));
		$this->resetLaravelPaths();

		$this->assertTrue(Schema::connection('artisantest_custom_two')
			->hasTable('test_tbl'));
		$this->assertFalse(Schema::connection('artisantest_default')
			->hasTable('test_tbl'));
		$this->assertFalse(Schema::connection('artisantest_custom')
			->hasTable('test_tbl'));

		$entry = $this->app['db']
			->connection('artisantest_custom_two')
			->table('test_tbl')
			->where('id', 1)
			->first();
		$this->assertEquals($this->testType, $entry->test_val);
	}
}