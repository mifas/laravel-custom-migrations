<?php namespace Codengine\CustomMigrations;

use Symfony\Component\Console\Input\InputOption;

/**
 * Class BatchMigrationTrait
 * Provides batch migration functions for multiple database connections
 *
 * @package Codengine\CustomMigrations
 */
trait BatchMigrationTrait {
	/**
	 * @var string
	 */
	protected $migrationType = 'default';

	/**
	 * Extends the default options by type-option
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array_merge(parent::getOptions(), array(
			array('type', null, InputOption::VALUE_OPTIONAL, 'The migration type to be executed.', 'default'),
		));
	}

	/**
	 * Filters the connections and only returns the ones that match the migration type
	 *
	 * @param array $connection The database connections
	 * @return bool Returns TRUE on a match, else FALSE
	 */
	protected function filterConnections($connection)
	{
		switch($this->migrationType)
		{
			case 'default':
				return (empty($connection['migration_type']) || $connection['migration_type'] == 'default'); break;
			default:
				return (!empty($connection['migration_type']) && $connection['migration_type'] == $this->migrationType ? true : false); break;
		}
	}

	/**
	 * Returns the default DB connection
	 *
	 * @return array
	 */
	protected function getDefaultConnection()
	{
		$defaultConnection = \DB::getDefaultConnection();
		$connection = \Config::get('database.connections.' . $defaultConnection);
		return (empty($connection) ? array() : array($defaultConnection => $connection));
	}

	/**
	 * Retrieves database connections by type
	 *
	 * @param null|string $filter When specified (--database option), only this connection will be checked
	 * @return array An array containing the matching connections
	 */
	protected function getConnectionsByType($filter = null)
	{
		$connections = array();
		if($this->migrationType == "default" && empty($filter)) {
			return $this->getDefaultConnection();
		} elseif (!empty($filter)) {
			$connections = \Config::get('database.connections.' . $filter);
			if(!empty($connections))
			{
				$connections = array($filter => $connections);
			}
		} else {
			$connections = \Config::get('database.connections');
		}

		if(!empty($connections))
		{
			$connections = array_filter($connections, array($this, 'filterConnections'));
		}
		return (array)$connections;
	}

	/**
	 * Retrieves and sets the migration type
	 */
	protected function setMigrationType()
	{
		$this->migrationType = $this->input->getOption('type');
	}

	/**
	 * Run a batch migration on the specified connections
	 *
	 * @param array $connections
	 */
	protected function runMigrationsOnConnections($connections)
	{
		foreach($connections as $name => $connection)
		{
			$this->input->setOption('database', $name);
			if(isset($this->migrator))
			{
				$this->migrator->setMigrationType(array_get($connection, 'migration_type', 'default'));
			}
			parent::fire();
		}
	}

	/**
	 * Default command override
	 */
	public function fire()
	{
		$this->setMigrationType();
		$connections = $this->getConnectionsByType($this->input->getOption('database'));
		if(empty($connections))
		{
			$this->info("No connections found for the specified migration type");
		} else {
			$this->runMigrationsOnConnections($connections);
		}
	}
} 