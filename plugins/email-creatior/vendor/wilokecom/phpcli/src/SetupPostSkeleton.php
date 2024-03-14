<?php

namespace WilokeCommandLine;

use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupPostSkeleton extends CommonController
{
	protected $commandName = 'make:post-skeleton';
	protected $commandDesc = 'Setup Post Skeleton';

	protected $commandAutoloadDir     = 'autoloadDir';
	protected $commandAutoloadDirDesc = 'Enter "App Directory Name" that you defined in the composer autoload. EG: src or app';

	protected $commandOptionNameSpace     = 'namespace';
	protected $commandOptionNameSpaceDesc = 'Provide your Your Unit Test Namespace. EG: Wiloke';

	protected $commandOptionFileName     = 'fileName';
	protected $commandOptionFileNameDesc = 'You can change PostSkeleton to your Filename';

	/**
	 * @var mixed
	 */
	private $originalFileName = 'PostSkeleton.php';
	private $fileName         = 'PostSkeleton.php';

	public function setRelativeComponentDir()
	{
		$this->relativeComponentDir = '';
	}

	public function setOriginalRelativeDir()
	{
		$this->originalRelativeFileDir = 'Illuminate/Skeleton';
	}

	protected function configure()
	{
		$this->setName($this->commandName)
			->setDescription($this->commandDesc)
			->addArgument(
				$this->commandAutoloadDir,
				InputArgument::OPTIONAL,
				$this->commandAutoloadDirDesc,
				$this->autoloadDir
			)
			->addOption(
				$this->commandOptionNameSpace,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionNameSpaceDesc
			)
			->addOption(
				$this->commandOptionFileName,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionFileNameDesc
			);
	}

	/**
	 * @throws \Exception
	 */
	private function createPostSkeletonComponent()
	{
		$this->content = file_get_contents($this->relativeComponentDir . $this->originalFileName);
		$this->generateNamespace();

		if (empty($this->content)) {
			throw new \Exception('We could not get ' . $this->originalFileName .
				' content. Please re-check read permission');
		}
		$this->replaceNamespace();

		$fileDirectory = $this->getAutoloadDir() . $this->relativeTargetFileDir;

		if (!$this->oFileSystem->exists($fileDirectory)) {
			$this->oFileSystem->mkdir($fileDirectory);
		}

		$this->oFileSystem->dumpFile($fileDirectory . '/' . $this->fileName, $this->content);
	}

	/**
	 * @param InputInterface $oInput
	 * @param OutputInterface $oOutput
	 * @return int|null
	 */
	protected function execute(InputInterface $oInput, OutputInterface $oOutput): ?int
	{
		$this->setRelativeTargetFileDir();
		$this->relativeComponentDir = dirname(dirname(__FILE__)) . '/components/';
		$this->autoloadDir = $oInput->getArgument($this->commandAutoloadDir);
		$this->oFileSystem = new Filesystem();

		if (!$this->oFileSystem->exists($this->autoloadDir)) {
			$oOutput->writeln('The auto-load directory does not exists', OutputInterface::VERBOSITY_NORMAL);

			return false;
		} else {
			$this->originalNamespace = $oInput->getOption($this->commandOptionNameSpace);
			$fileName = $oInput->getOption($this->commandOptionFileName);

			if (!empty($fileName)) {
				$this->fileName = strpos($fileName, '.php') === false ? $fileName . '.php' : $fileName;
			}

			try {
				$this->createPostSkeletonComponent();
				$oOutput->writeln('Wiloke PHPUNIT has been setup successfully');
			}
			catch (\Exception $oE) {
				$oOutput->writeln($oE->getMessage(), OutputInterface::VERBOSITY_NORMAL);
			}
		}

		return true;
	}
}
