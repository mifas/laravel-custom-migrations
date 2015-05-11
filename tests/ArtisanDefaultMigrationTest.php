<?php
/**
 * Author: Stefan Hueg
 * Copyright: Codengine @ 2015
 */

use \Illuminate\Support\Facades\Schema;

class ArtisanDefaultMigrationTest extends Orchestra\Testbench\TestCase {
	protected $orchestraFixtureBasePath;
	protected $testType = 'default';

	public function setUp()
	{
		parent::setUp();
		$this->orchestraFixtureBasePath = $this->getBasePath();
	}

	protected function getPackageProviders($app)
	{
		return array(
			'Codengine\CustomMigrations\CustomMigrationsServiceProvider'
		);
	}

	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'artisantest_default');
		$app['config']->set('database.connections.artisantest_default', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
			'prefix'   => '',
		]);

		$app['config']->set('database.connections.artisantest_custom', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
			'prefix'   => '',
			'migration_type' => 'custom'
		]);
	}

	protected function setLaravelPathsForMigration()
	{
		$this->app->setBasePath(realpath(__DIR__));
    }

	protected function resetLaravelPaths()
	{
        $this->app->setBasePath($this->orchestraFixtureBasePath);
	}

	protected function getArtisan()
	{
        /** @var \Illuminate\Foundation\Console\Kernel $artisan */
        $artisan = $this->app->make('Illuminate\Contracts\Console\Kernel');
        $artisan->bootstrap();
		//getArtisan has to be invoked, else the paths won't work
		$method = new ReflectionMethod($artisan, 'getArtisan');
		$method->setAccessible(true);
		$method->invoke($artisan);
		return $artisan;
	}

	protected function executeDefaultMigration($parameters = array())
	{
		$artisan = $this->getArtisan();
		$this->setLaravelPathsForMigration();
		$artisan->call('migrate', array_merge(array(
			'--type' => $this->testType
		), $parameters));
		return $artisan;
	}

	protected function getOppositeDbConnection()
	{
		return ($this->testType == 'default' ? 'custom' : 'default');
	}

	public function testArtisanMigrate()
	{
		$this->executeDefaultMigration();
		$this->resetLaravelPaths();

		$this->assertTrue(Schema::connection('artisantest_' . $this->testType)
			->hasTable('test_tbl'));
		$this->assertFalse(Schema::connection('artisantest_' . $this->getOppositeDbConnection())
			->hasTable('test_tbl'));

		$entry = $this->app['db']
			->connection('artisantest_' . $this->testType)
			->table('test_tbl')
			->where('id', 1)
			->first();
		$this->assertEquals($this->testType, $entry->test_val);
	}

	public function testArtisanRollback()
	{
		$this->executeDefaultMigration()
			->call('migrate:rollback', array(
				'--type' => $this->testType
			));
		$this->resetLaravelPaths();

		$this->assertFalse(Schema::connection('artisantest_' . $this->testType)
			->hasTable('test_tbl'));
	}

	public function testArtisanReset()
	{
		$this->executeDefaultMigration()
			->call('migrate:reset', array(
				'--type' => $this->testType
			));
		$this->resetLaravelPaths();

		$this->assertFalse(Schema::connection('artisantest_' . $this->testType)
			->hasTable('test_tbl'));
	}

	public function testArtisanRefresh()
	{
		$this->executeDefaultMigration()
			->call('migrate:refresh', array(
				'--type' => $this->testType
			));
		$this->resetLaravelPaths();

		$this->assertTrue(Schema::connection('artisantest_' . $this->testType)
			->hasTable('test_tbl'));

		$entry = $this->app['db']
			->connection('artisantest_' . $this->testType)
			->table('test_tbl')
			->where('id', 1)
			->first();
		$this->assertEquals($this->testType, $entry->test_val);
	}
}