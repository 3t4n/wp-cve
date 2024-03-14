<?php

namespace WilokeCommandLine;

use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupPHPUNIT extends Command
{
	protected $commandName = 'make:unittest';
	protected $commandDesc = 'Setup PHPUNIT test for your project';

	protected $commandArgsType     = 'type';
	protected $commandArgsTypeDesc = 'Enter "plugin" if you want to setup ';

	protected $commandArgsName     = 'name';
	protected $commandArgsNameDesc = 'Enter in your plugin / theme folder name';

	protected $commandOptionHomeUrl     = 'homeurl';
	protected $commandOptionHomeUrlDesc = 'Enter in Project Url. EG: https://wiloke.com';

	protected $commandOptionRestBase         = 'rb';
	protected $commandOptionRestBaseRestBase = 'Provide your Rest Namespace. EG: wiloke/v2. ';

	protected $commandOptionTestNameSpace     = 'testnamespace';
	protected $commandOptionTestNameSpaceDesc = 'Provide your Your Unit Test Namespace. EG: Wiloke';

	protected $commandOptionApplicationPassword     = 'authpass';
	protected $commandOptionApplicationPasswordDesc = 'Provide your admin application password';

	protected $commandOptionAdminUsername     = 'admin_username';
	protected $commandOptionAdminUsernameDesc = 'Provide your admin username';

	protected $commandOptionAdminPassword     = 'admin_password';
	protected $commandOptionAdminPasswordDesc = 'Provide your admin password';

	/**
	 * @var Filesystem
	 */
	private $oFileSystem;

	private $phpUnitSampleDir;
	/**
	 * @var mixed
	 */
	private $restBase;
	/**
	 * @var mixed
	 */
	private $homeUrl;

	private $homeUrlPlaceHolder  = 'HOME_URL_VALUE';
	private $restBasePlaceHolder = 'REST_BASE_VALUE';
	/**
	 * @var mixed
	 */
	private $fileName;

	private $fileNamePlaceholder = 'projectname';
	/**
	 * @var mixed
	 */
	private $type;
	private $typePlaceholder = 'projecttype';
	/**
	 * @var mixed
	 */
	private $testNamespace;
	private $testNamespacePlaceholder = 'WilokeTest';
	private $aValidTypes          = ['themes', 'plugins'];
	/**
	 * @var mixed
	 */
	private $adminUsername;
	private $adminUsernamePlaceholder = 'ADMIN_USERNAME_VALUE';
	/**
	 * @var mixed
	 */
	private $authPassword;
	private $authPasswordPlaceholder = 'ADMIN_AUTH_PASS_VALUE';
	/**
	 * @var mixed
	 */
	private $adminPassword;
	private $adminPasswordPlaceholder = 'ADMIN_PASSWORD_VALUE';

	protected function configure()
	{
		$this->setName($this->commandName)
			->setDescription($this->commandDesc)
			->addArgument(
				$this->commandArgsType,
				InputArgument::REQUIRED,
				$this->commandArgsTypeDesc
			)
			->addArgument(
				$this->commandArgsName,
				InputArgument::REQUIRED,
				$this->commandArgsNameDesc
			)
			->addOption(
				$this->commandOptionHomeUrl,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionHomeUrlDesc
			)
			->addOption(
				$this->commandOptionTestNameSpace,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionTestNameSpaceDesc
			)
			->addOption(
				$this->commandOptionRestBase,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionRestBaseRestBase
			)
			->addOption(
				$this->commandOptionApplicationPassword,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionApplicationPasswordDesc
			)
			->addOption(
				$this->commandOptionAdminUsername,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionAdminUsernameDesc
			)
			->addOption(
				$this->commandOptionAdminPassword,
				null,
				InputOption::VALUE_OPTIONAL,
				$this->commandOptionAdminPasswordDesc
			);
	}

	private function createPHPUNITXML()
	{
		$content = file_get_contents($this->phpUnitSampleDir . 'phpunit.xml');
		if (empty($content)) {
			throw new \Exception('We could not get phpunit/phpunit.xml content. Please re-check read permission');
		}

		if ($this->restBase) {
			$content = str_replace($this->restBasePlaceHolder, $this->restBase, $content);
		}

		if ($this->homeUrl) {
			$content = str_replace($this->homeUrlPlaceHolder, $this->homeUrl, $content);
		}

		if ($this->adminUsername) {
			$content = str_replace($this->adminUsernamePlaceholder, $this->adminUsername, $content);
		}

		if ($this->adminPassword) {
			$content = str_replace($this->adminPasswordPlaceholder, $this->adminPassword, $content);
		}

		if ($this->authPassword) {
			$content = str_replace($this->authPasswordPlaceholder, $this->authPassword, $content);
		}

		$this->oFileSystem->dumpFile('phpunit.xml', $content);
	}

	private function createBootstrap()
	{
		if (!$this->oFileSystem->exists('tests')) {
			$this->oFileSystem->mkdir('tests');
		}

		$content = file_get_contents($this->phpUnitSampleDir . 'bootstrap.php');

		$content = str_replace($this->typePlaceholder, $this->type, $content);
		$content = str_replace($this->fileNamePlaceholder, $this->fileName, $content);

		$this->oFileSystem->dumpFile('tests/bootstrap.php', $content);
	}

	private function createHTTP()
	{
		$content = file_get_contents($this->phpUnitSampleDir . 'HTTP.php');

		if ($this->testNamespace) {
			$content = str_replace(
				[
					$this->testNamespacePlaceholder,
					'#'
				],
				[
					$this->testNamespace,
					''
				],
				$content
			);
		}

		$this->oFileSystem->dumpFile('tests/HTTP.php', $content);
	}

	private function createCommon()
	{
		$content = file_get_contents($this->phpUnitSampleDir . 'CommonController.php');

		if ($this->testNamespace) {
			$content = str_replace(
				[
					$this->testNamespacePlaceholder,
					'#'
				],
				[
					$this->testNamespace,
					''
				],
				$content
			);
		}

		$this->oFileSystem->dumpFile('tests/CommonController.php', $content);
	}

	protected function execute(InputInterface $oInput, OutputInterface $oOutput)
	{
		$this->phpUnitSampleDir = dirname(dirname(__FILE__)) . '/phpunit/';

		$this->type = $oInput->getArgument($this->commandArgsType);

		if (!in_array($this->type, $this->aValidTypes)) {
			$oOutput->writeln('The type must be: themes or plugins', OutputInterface::VERBOSITY_NORMAL);
		} else {
			$this->fileName = $oInput->getArgument($this->commandArgsName);
			$this->homeUrl = $oInput->getOption($this->commandOptionHomeUrl);
			$this->restBase = $oInput->getOption($this->commandOptionRestBase);
			$this->testNamespace = $oInput->getOption($this->commandOptionTestNameSpace);
			$this->adminUsername = $oInput->getOption($this->commandOptionAdminUsername);
			$this->adminPassword = $oInput->getOption($this->commandOptionAdminPassword);
			$this->authPassword = $oInput->getOption($this->commandOptionApplicationPassword);

			$this->oFileSystem = new Filesystem();

			try {
				$this->createPHPUNITXML();
				$this->createBootstrap();
				$this->createHTTP();
				$this->createCommon();

				$oOutput->writeln('Wiloke PHPUNIT has been setup successfully');
			}
			catch (\Exception $oE) {
				$oOutput->writeln($oE->getMessage(), OutputInterface::VERBOSITY_NORMAL);
			}
		}

		return true;
	}
}
