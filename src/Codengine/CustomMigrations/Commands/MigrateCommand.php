<?php namespace Codengine\CustomMigrations\Commands;

use Codengine\CustomMigrations\BatchMigrationTrait;

class MigrateCommand extends \Illuminate\Database\Console\Migrations\MigrateCommand {
	use BatchMigrationTrait;
}