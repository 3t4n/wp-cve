<?php


namespace WilokeCommandLine;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupPrefix extends CommonController
{
	protected $commandName = 'make:prefix';
	protected $commandDesc = 'Setup Prefix';

	protected $commandAutoloadDir     = 'autoloadDir';
	protected $commandAutoloadDirDesc = 'Enter "App Directory Name" that you defined in the composer autoload. EG: src or app';

	protected $prefixDefineName     = 'prefixDefine';
	protected $prefixDefineNameDesc = 'Enter name of prefix defined';

	/**
	 * @var mixed
	 */
	private $originalFileNames = ['AutoPrefix.php'];

	public function setRelativeComponentDir()
	{
		$this->relativeComponentDir = 'Prefix';
	}

	public function setOriginalRelativeDir()
	{
		$this->originalRelativeFileDir = 'Illuminate/Prefix';
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
				$this->prefixDefineName,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->prefixDefineNameDesc
			);
	}

	/**
	 * @throws \Exception
	 */
	private function createPostSkeletonComponent()
	{
		$this->generateNamespace();

		foreach ($this->originalFileNames as $fileName) {
			$this->content = file_get_contents($this->relativeComponentDir . $fileName);

			if (empty($this->content)) {
				throw new \Exception('We could not get ' . $fileName .
					' content. Please re-check read permission');
			}

			$this->replaceNamespace();

			$this->autoloadDir = trim($this->autoloadDir, '/') . '/';
			$fileDirectory = './' . $this->autoloadDir . $this->relativeTargetFileDir;

			if (!$this->oFileSystem->exists($fileDirectory)) {
				$this->oFileSystem->mkdir($fileDirectory);
			}

			$this->oFileSystem->dumpFile($fileDirectory . '/' . $fileName, $this->content);
		}
	}

	/**
	 * @param InputInterface $oInput
	 * @param OutputInterface $oOutput
	 * @return int|null
	 */
	protected function execute(InputInterface $oInput, OutputInterface $oOutput): ?int
	{
		$this->commonConfiguration($oInput, $oOutput);
		$this->setRelativeTargetFileDir();
		$this->relativeComponentDir = dirname(dirname(__FILE__)) . '/components/' . $this->relativeComponentDir . '/';

		$this->autoloadDir = $oInput->getArgument($this->commandAutoloadDir);
		$this->oFileSystem = new Filesystem();

		if (!$this->oFileSystem->exists($this->autoloadDir)) {
			$oOutput->writeln('The auto-load directory does not exists', OutputInterface::VERBOSITY_NORMAL);

			return false;
		} else {
			$this->originalNamespace = $oInput->getOption($this->commandOptionNameSpace);
			$this->prefixDefinedValue = $oInput->getOption($this->prefixDefineName);

			if (empty( $this->prefixDefinedValue)) {
				$oOutput->writeln('Please provide prefixDefine. EG: --prefixDefine=MY_PREFIX',
					OutputInterface::VERBOSITY_NORMAL);
				return false;
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
