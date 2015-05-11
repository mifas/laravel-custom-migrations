<?php namespace Codengine\CustomMigrations;

use Codengine\CustomMigrations\Commands\MigrateCommand;
use Codengine\CustomMigrations\Commands\RefreshCommand;
use Codengine\CustomMigrations\Commands\ResetCommand;
use Codengine\CustomMigrations\Commands\RollbackCommand;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Support\ServiceProvider;

class CustomMigrationsServiceProvider extends MigrationServiceProvider {
	/**
	 * Register the migrator service.
	 *
	 * @return void
	 */
	protected function registerMigrator()
	{
		// The migrator is responsible for actually running and rollback the migration
		// files in the application. We'll pass in our database connection resolver
		// so the migrator can resolve any of these connections when it needs to.
		$this->app->bind('migrator', function($app)
		{
			$repository = $app['migration.repository'];
			return new Migrator($repository, $app['db'], $app['files']);
		}, true);
	}

	/**
	 * Register the "migrate" migration command.
	 *
	 * @return void
	 */
	protected function registerMigrateCommand()
	{
		$this->app->bind('command.migrate', function($app)
		{
			$packagePath = $app['path.base'].'/vendor';

			return new MigrateCommand($app['migrator'], $packagePath);
		}, true);
	}

	/**
	 * Register the "rollback" migration command.
	 *
	 * @return void
	 */
	protected function registerRollbackCommand()
	{
		$this->app->bind('command.migrate.rollback', function($app)
		{
			return new RollbackCommand($app['migrator']);
		}, true);
	}

	/**
	 * Register the "reset" migration command.
	 *
	 * @return void
	 */
	protected function registerResetCommand()
	{
		$this->app->bind('command.migrate.reset', function($app)
		{
			return new ResetCommand($app['migrator']);
		}, true);
	}

	/**
	 * Register the "refresh" migration command.
	 *
	 * @return void
	 */
	protected function registerRefreshCommand()
	{
		$this->app->bind('command.migrate.refresh', function()
		{
			return new RefreshCommand;
		}, true);
	}
}
