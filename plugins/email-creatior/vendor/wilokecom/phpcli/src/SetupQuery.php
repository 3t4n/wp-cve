<?php


namespace WilokeCommandLine;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupQuery extends CommonController
{
	protected $commandName                = 'make:query';
	protected $commandDesc                = 'Setup Query based on Strategy pattern';
	protected $commandOptionNameSpace     = 'namespace';
	protected $commandOptionNameSpaceDesc = 'Provide your Your Unit Test Namespace. EG: Wiloke';
	protected $helpersRelativeDir         = 'Illuminate/Helpers';
	protected $stringHelperFilename       = 'StringHelper.php';
	protected $helperComponentDir         = 'Helpers';
	protected $skeletonComponentDir       = 'Skeleton';
	protected $skeletonRelativeDir        = 'Illuminate/Skeleton';

	public function setOriginalRelativeDir()
	{
		$this->originalRelativeFileDir = 'Illuminate/Query';
	}

	public function setRelativeComponentDir()
	{
		$this->relativeComponentDir = 'Query';
	}

	public function configure()
	{
		$this->setName($this->commandName)
			->setDescription($this->commandDesc)
			->addOption(
				$this->commandOptionNameSpace,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionNameSpaceDesc
			);
	}

	protected function copyQueryFolder(): bool
	{
		if (!$this->oFileSystem->exists($this->getAbsFileDir())) {
			$this->oFileSystem->mkdir($this->getAbsFileDir(), 755);
		}

		$this->recursiveCopy($this->getRelativeComponentDir(), $this->getAbsFileDir());

		return true;
	}

	protected function copyHelperFolder(): bool
	{
		$this->setRelativeTargetFileDir($this->helpersRelativeDir);

		if (!$this->oFileSystem->exists($this->getAbsFileDir())) {
			$this->oFileSystem->mkdir($this->getAbsFileDir(), 755);
		}

		if ($this->oFileSystem->exists($this->trailingslashit($this->getAbsFileDir()) .
			$this->stringHelperFilename)) {
			$helperQuestion = sprintf('The file name %s is existed, Do you want to override it?',
				$this->stringHelperFilename);

			if (!$this->isContinue($helperQuestion)) {
				return true;
			}
		}

		$this->recursiveCopy($this->getRelativeComponentDir($this->helperComponentDir), $this->getAbsFileDir());

		return true;
	}

	protected function copySkeletonFolder(): bool
	{
		$this->setRelativeTargetFileDir($this->skeletonRelativeDir);
		if (!$this->oFileSystem->exists($this->getAbsFileDir())) {
			$this->oFileSystem->mkdir($this->getAbsFileDir(), 755);
		}

		$this->recursiveCopy($this->getRelativeComponentDir($this->skeletonComponentDir), $this->getAbsFileDir());

		return true;
	}

	public function execute(InputInterface $oInput, OutputInterface $oOutput)
	{
		$this->commonConfiguration($oInput, $oOutput);
		$this->copyQueryFolder();
		$this->copyHelperFolder();
		$this->copySkeletonFolder();
		$this->outputMsg();
	}
}
