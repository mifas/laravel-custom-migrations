<?php namespace Codengine\CustomMigrations\Commands;

use Codengine\CustomMigrations\BatchMigrationTrait;

class ResetCommand extends \Illuminate\Database\Console\Migrations\ResetCommand {
	use BatchMigrationTrait;
} 