<?php namespace Codengine\CustomMigrations\Commands;

use Codengine\CustomMigrations\BatchMigrationTrait;

class RefreshCommand extends \Illuminate\Database\Console\Migrations\RefreshCommand {
	use BatchMigrationTrait;
} 