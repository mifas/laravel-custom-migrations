<?php namespace Codengine\CustomMigrations;

/**
 * Class Migrator
 * The custom Migrator filters migrations of a specific type
 *
 * @package Codengine\CustomMigrations
 */
class Migrator extends \Illuminate\Database\Migrations\Migrator {
	/**
	 * @var string
	 */
	protected $migrationType = 'default';

	/**
	 * Sets the migration type filter
	 *
	 * @param string $type
	 */
	public function setMigrationType($type)
	{
		$this->migrationType = $type;
	}

	/**
	 * Returns the migration type filter
	 *
	 * @return string
	 */
	public function getMigrationType()
	{
		return $this->migrationType;
	}

	/**
	 * Resolves the migration and filters those that don't match the migration type
	 *
	 * @param string $migration
	 * @return bool Returns TRUE on a match, else FALSE
	 */
	protected function filterMigrations($migration)
	{
		$instance = $this->resolve($migration);
		if(empty($instance->type))
		{
			$instance->type = 'default';
		}

		if($this->migrationType != $instance->type)
		{
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Gets a filtered list of migrations and runs them
	 *
	 * @param array $migrations
	 * @param bool $pretend
	 */
	public function runMigrationList($migrations, $pretend = FALSE)
	{
		$this->note("Running " . ($this->migrationType == "default" ? "default" : "custom") . " migrations for DB " . $this->connection);
		$migrations = array_filter($migrations, array($this, "filterMigrations"));
		parent::runMigrationList($migrations, $pretend);
	}
} 