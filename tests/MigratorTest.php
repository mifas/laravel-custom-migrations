<?php
/**
 * Author: Stefan Hueg
 * Copyright: Codengine @ 2015
 */

class MigratorTest extends PHPUnit_Framework_TestCase {
	/** @var \Codengine\CustomMigrations\Migrator|PHPUnit_Framework_MockObject_MockObject $migrator */
	protected $migrator;
	/** @var \Illuminate\Database\Migrations\MigrationRepositoryInterface|PHPUnit_Framework_MockObject_MockObject */
	protected $migrationRepository;
	/** @var \Illuminate\Database\ConnectionResolverInterface|PHPUnit_Framework_MockObject_MockObject $connectionResolver */
	protected $connectionResolver;
	/** @var \Illuminate\Filesystem\Filesystem|PHPUnit_Framework_MockObject_MockObject $fileSystem */
	protected $fileSystem;

	protected $migrationList = array(
		'2015_03_05_012633_default_test_migration',
		'2015_03_05_012634_custom_test_migration',
	);

	public function setUp()
	{
		parent::setUp();
		$this->migrationRepository = $this->getMock('\Illuminate\Database\Migrations\MigrationRepositoryInterface');
		$this->migrationRepository->expects($this->any())
			->method('log')
			->willReturn(null);

		$this->connectionResolver = $this->getMock('\Illuminate\Database\ConnectionResolverInterface');
		$this->fileSystem = $this->getMock('\Illuminate\Filesystem\Filesystem');

		$this->migrator = $this->getMockBuilder('Codengine\CustomMigrations\Migrator')
			->setConstructorArgs(array(
				$this->migrationRepository,
				$this->connectionResolver = $this->getMock('\Illuminate\Database\ConnectionResolverInterface'),
				$this->fileSystem
			))
			->setMethods(array('resolve'))
			->getMock();
	}

	protected function setMigrationResolves(array $resolves)
	{
		$this->migrator->expects($this->any())
			->method('resolve')
			->will($this->returnValueMap($resolves));
	}

	public function testMigratorHasDefaultMigrationType()
	{
		$this->assertEquals('default', $this->migrator->getMigrationType());
	}

	public function testMigratorSetsMigrationType()
	{
		$this->migrator->setMigrationType('custom');
		$this->assertEquals('custom', $this->migrator->getMigrationType());
	}

	public function testMigratorOnlyExecutesDefaultMigration()
	{
		$this->migrationRepository->expects($this->once())
			->method('getNextBatchNumber')
			->willReturn(1);

		$default = $this->getMockBuilder('\DefaultTestMigration')
			->setMethods(array('up'))
			->getMock();
		$default->expects($this->once())->method('up');

		$custom = $this->getMockBuilder('\CustomTestMigration')
			->setMethods(array('up'))
			->getMock();
		$custom->expects($this->never())->method('up');

		$this->setMigrationResolves(array(
			array('2015_03_05_012633_default_test_migration', $default),
			array('2015_03_05_012634_custom_test_migration', $custom)
		));

		$this->migrator->runMigrationList($this->migrationList, []);
	}

	public function testMigratorOnlyExecutesCustomMigration()
	{
		$this->migrator->setMigrationType('custom');

		$this->migrationRepository->expects($this->once())
			->method('getNextBatchNumber')
			->willReturn(1);

		$default = $this->getMockBuilder('\DefaultTestMigration')
			->setMethods(array('up'))
			->getMock();
		$default->expects($this->never())->method('up');

		$custom = $this->getMockBuilder('\CustomTestMigration')
			->setMethods(array('up'))
			->getMock();
		$custom->expects($this->once())->method('up');

		$this->setMigrationResolves(array(
			array('2015_03_05_012633_default_test_migration', $default),
			array('2015_03_05_012634_custom_test_migration', $custom)
		));

		$this->migrator->runMigrationList($this->migrationList, []);
	}
}