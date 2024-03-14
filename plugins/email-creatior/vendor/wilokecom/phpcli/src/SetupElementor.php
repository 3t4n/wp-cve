<?php


namespace WilokeCommandLine;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupElementor extends CommonController
{
	protected $commandName          = 'make:elementor';
	protected $commandDesc          = 'Setup a Shortcode';
	protected $relativeComponentDir = 'Shortcode';

	protected $commandOptionNameSpace     = 'namespace';
	protected $commandOptionNameSpaceDesc = 'Provide your Your Unit Test Namespace. EG: Wiloke';

	protected $originalFilename = 'SampleElementor.php';
	protected $configFilename   = 'config.php';

	/**
	 * @var mixed
	 */
	private $filename;

	public function setRelativeComponentDir()
	{
		$this->relativeComponentDir = 'Elementor';
	}

	public function setOriginalRelativeDir()
	{
		$this->originalRelativeFileDir = 'Controllers/Elementor';
	}

	public function configure()
	{
		$this->setName($this->commandName)
			->setDescription($this->commandDesc)
			->addArgument(
				$this->commonClassName,
				InputArgument::REQUIRED,
				$this->commonClassNameDesc
			)
			->addOption(
				$this->commandOptionNameSpace,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionNameSpaceDesc
			);
	}

	protected function createShortcode(): bool
	{
		if (!$this->oFileSystem->exists($this->getAbsFileDir())) {
			$this->oFileSystem->mkdir($this->getAbsFileDir(), 755);
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
			$this->originalNamespace . '\\Controllers\\' . $this->relativeComponentDir,
			$this->filename
		);

		return true;
	}

	protected function createConfig()
	{
		$content = file_get_contents($this->getRelativeComponentDir() . $this->configFilename);

		$this->oFileSystem->dumpFile(
			$this->trailingslashit($this->relativeTargetFileDir) . $this->configFilename,
			$content
		);
	}

	public function execute(InputInterface $oInput, OutputInterface $oOutput)
	{
		$this->commonConfiguration($oInput, $oOutput);
		$this->className = $oInput->getArgument($this->commonClassName);
		$this->filename = $this->className . '.php';
		$this->originalRelativeFileDir = $this->trailingslashit($this->originalRelativeFileDir) . $this->className;
		$this->relativeTargetFileDir = $this->trailingslashit($this->relativeTargetFileDir) . $this->className;

		$this->createShortcode();
//		$this->createConfig();
		$this->outputMsg();
	}
}
