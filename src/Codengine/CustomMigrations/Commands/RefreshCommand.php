<?php namespace Codengine\CustomMigrations\Commands;

use Codengine\CustomMigrations\BatchMigrationTrait;

class RefreshCommand extends \Illuminate\Database\Console\Migrations\RefreshCommand {
	use BatchMigrationTrait;

	public function call($command, array $arguments = array())
	{
		if($command === 'migrate' || $command === 'migrate:reset')
		{
			$arguments['--type'] = $this->input->getOption('type');

		}

		return parent::call($command, $arguments);
	}
} 