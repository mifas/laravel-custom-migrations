<?php namespace Codengine\CustomMigrations\Commands;

use Codengine\CustomMigrations\BatchMigrationTrait;

class RollbackCommand extends \Illuminate\Database\Console\Migrations\RollbackCommand {
	use BatchMigrationTrait;
} 