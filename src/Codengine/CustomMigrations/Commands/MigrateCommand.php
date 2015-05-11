<?php namespace Codengine\CustomMigrations\Commands;

use Codengine\CustomMigrations\BatchMigrationTrait;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends \Illuminate\Database\Console\Migrations\MigrateCommand {
	use BatchMigrationTrait;
}