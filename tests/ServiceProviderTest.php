<?php
/**
 * Author: Stefan Hueg
 * Copyright: Codengine @ 2015
 */

class ServiceProviderTest extends Orchestra\Testbench\TestCase {
    protected function getPackageProviders($app)
	{
		return array(
			'Codengine\CustomMigrations\CustomMigrationsServiceProvider'
		);
	}

	public function testMigratorServiceDoesResolve()
	{
		$this->assertInstanceOf('Codengine\CustomMigrations\Migrator', $this->app->make('migrator'));
	}

	public function testMigrateCommandDoesResolve()
	{
		$this->assertInstanceOf('Codengine\CustomMigrations\Commands\MigrateCommand', $this->app->make('command.migrate'));
	}

	public function testMigrateCommandHasTypeOption()
	{
		/** @var \Codengine\CustomMigrations\Commands\MigrateCommand $command */
		$command = $this->app->make('command.migrate');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
	}

	public function testRefreshCommandDoesResolve()
	{
		$this->assertInstanceOf('Codengine\CustomMigrations\Commands\RefreshCommand', $this->app->make('command.migrate.refresh'));
	}

	public function testRefreshCommandHasTypeOption()
	{
		/** @var \Codengine\CustomMigrations\Commands\RefreshCommand $command */
		$command = $this->app->make('command.migrate.refresh');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
	}

	public function testResetCommandDoesResolve()
	{
		$this->assertInstanceOf('Codengine\CustomMigrations\Commands\ResetCommand', $this->app->make('command.migrate.reset'));
	}

	public function testResetCommandHasTypeOption()
	{
		/** @var \Codengine\CustomMigrations\Commands\ResetCommand $command */
		$command = $this->app->make('command.migrate.reset');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
	}

	public function testRollbackCommandDoesResolve()
	{
		$this->assertInstanceOf('Codengine\CustomMigrations\Commands\RollbackCommand', $this->app->make('command.migrate.rollback'));
	}

	public function testRollbackCommandHasTypeOption()
	{
		/** @var \Codengine\CustomMigrations\Commands\RollbackCommand $command */
		$command = $this->app->make('command.migrate.rollback');
		$this->assertTrue($command->getDefinition()->hasOption('type'));
	}
}