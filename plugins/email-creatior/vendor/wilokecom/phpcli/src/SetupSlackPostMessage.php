<?php

namespace WilokeCommandLine;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupSlackPostMessage extends CommonController
{
	protected $commandName          = 'make:slack-message';
	protected $commandDesc          = 'Setup Slack Post Message';
	protected $relativeComponentDir = 'Slack';

	protected $commandOptionNameSpace     = 'namespace';
	protected $commandOptionNameSpaceDesc = 'Provide your Your Unit Test Namespace. EG: Wiloke';

	protected $commandAutoloadDir     = 'autoloadDir';
	protected $commandAutoloadDirDesc = 'Enter "App Directory Name" that you defined in the composer autoload. EG: src or app';

	protected $originalFilename = 'PostMessage.php';
	protected $className        = 'PostMessage';

	protected $logModelFile      = 'LogModel.php';
	protected $logModelClassName = 'LogModel';

	/**
	 * @var mixed
	 */
	private $filename;

	public function setRelativeComponentDir()
	{
		$this->relativeComponentDir = 'Slack';
	}

	public function setOriginalRelativeDir()
	{
		$this->originalRelativeFileDir = 'Illuminate/Slack';
	}

	public function configure()
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
			);
	}

	protected function createPostMessageFile(): bool
	{
		if (!$this->oFileSystem->exists($this->getAbsFileDir())) {
			$this->oFileSystem->mkdir($this->getAbsFileDir());
		}

		if ($this->oFileSystem->exists($this->trailingslashit($this->getAbsFileDir()) . $this->filename)) {
			if (!$this->isContinue()) {
				return true;
			}
		}

		$this->dummyFile(
			$this->getRelativeComponentDir() . $this->originalFilename,
			$this->trailingslashit(
				$this->relativeTargetFileDir
			),
			$this->originalNamespace . '\\Illuminate\\' . $this->relativeComponentDir,
			$this->filename
		);


		return true;
	}

	protected function createLogModelFile(): bool
	{
		$this->dummyFile(
			$this->getRelativeComponentDir() . $this->logModelFile,
			$this->trailingslashit(
				$this->relativeTargetFileDir
			),
			$this->originalNamespace . '\\Illuminate\\' . $this->relativeComponentDir,
			$this->logModelFile
		);

		return true;
	}

	public function execute(InputInterface $oInput, OutputInterface $oOutput)
	{
		$this->commonConfiguration($oInput, $oOutput);
		$this->setRelativeTargetFileDir();
		$this->autoloadDir = $oInput->getArgument($this->commandAutoloadDir);
		$this->filename = $this->className . '.php';

		$this->createPostMessageFile();
		$this->createLogModelFile();
		$this->outputMsg();
	}
}
