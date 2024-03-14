<?php

namespace WilokeCommandLine;

use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupGrumPHP extends Command
{
	protected $commandName = 'make:grumphp';
	protected $commandDesc = 'Setup GrumPHP';
	protected $phpGrumpPHPDir;

	/**
	 * @var Filesystem
	 */
	private $oFileSystem;

	protected function configure()
	{
		$this->setName($this->commandName)
			->setDescription($this->commandDesc);
	}

	private function createGrumpYML()
	{
		$filePath = $this->phpGrumpPHPDir . 'grumphp.xml';
		if ($this->oFileSystem->exists($filePath)) {
			return true;
		}


		$content = file_get_contents($this->phpGrumpPHPDir . 'grumphp.yml');
		$this->oFileSystem->dumpFile('grumphp.yml', $content);

		return true;
	}

	private function createPHPCsFixer()
	{
		$filePath = $this->phpGrumpPHPDir . '.php.cs';
		if ($this->oFileSystem->exists($filePath)) {
			return true;
		}


		$content = file_get_contents($this->phpGrumpPHPDir . '.php.cs.dst');
		$this->oFileSystem->dumpFile('.php.cs', $content);

		return true;
	}

	protected function execute(InputInterface $oInput, OutputInterface $oOutput)
	{
		$this->phpGrumpPHPDir = dirname(dirname(__FILE__)) . '/grumphp/';
		$this->oFileSystem = new Filesystem();

		try {
			$this->createGrumpYML();
			$this->createPHPCsFixer();

			$oOutput->writeln('GrumPHP has been setup successfully');
		}
		catch (\Exception $oE) {
			$oOutput->writeln($oE->getMessage(), OutputInterface::VERBOSITY_NORMAL);
		}
		return true;
	}
}
