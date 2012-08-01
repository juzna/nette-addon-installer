<?php

namespace Nette\Addons;

use Composer\Package\PackageInterface;
use Composer\Autoload\AutoloadGenerator;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Custom installer of Nette Addons
 *
 * @author Jan Dolecek <juzna.cz@gmail.com>
 */
class Installer extends LibraryInstaller
{
	private static $supportedTypes = array(
		'nette-addon',
		'nette-assets',
	);

	/** @var InstalledRepositoryInterface */
	private $repo;

	/** @var PackageInterface package being used; cached in all public methods */
	private $package;

	/** @var array cache: extra config section */
	private $extra;

	/** @var array config from addons.neon */
	private $addonConfig;



	const HEADER = <<<EOT
# Addons installed by composer
#
# This file is generated automatically by composer from information in extra.nette-addon or extra.nette
# (actually it is not at the moment, but should be in the future)
EOT;



	public function supports($packageType)
	{
		return in_array($packageType, self::$supportedTypes);
	}



	public function getInstallPath(PackageInterface $package)
	{
		switch($package->getType()) {
			case 'nette-assets':
				throw new \Exception("Not implemented yet");

			case 'nette-addon':
				return parent::getInstallPath($package);

			default:
				throw new \Exception("Not recognized package type '{$package->getType()}'");
		}
	}



	public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		parent::install($repo, $package);

		// store to local fields, load existing config, do work, save patched config

		$this->repo = $repo;
		$this->usePackage($package);

		$this->loadAddonsNeon();

		$this->saveAddonInfo();
		$this->copyAssets();
		$this->runCustomInstallers();

		$this->saveAddonsNeon();
	}



	public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
	{
		parent::update($repo, $initial, $target);

		// store to local fields, load existing config, do work, save patched config

		$this->repo = $repo;
		$this->usePackage($target);

		$this->loadAddonsNeon();

		$this->saveAddonInfo();
		$this->copyAssets();
		$this->runCustomInstallers();

		$this->saveAddonsNeon();
	}



	/*****************  utils  *****************j*d*/

	/**
	 * Sets this class to work with a given package from now on; then all method calls are easier
	 */
	private function usePackage(PackageInterface $package)
	{
		$this->package = $package;
		$this->extra = NULL;
		$this->addonConfig = NULL;
	}



	/**
	 * @return array|null
	 */
	private function getExtra($section = NULL)
	{
		// Find nette extra section for the first time
		if ($this->extra === NULL) {
			$extra = $this->package->getExtra();

			if (isset($extra['nette-addon'])) $this->extra = $extra['nette-addon'];
			elseif (isset($extra['nette'])) $this->extra = $extra['nette'];
			else $this->extra = FALSE;
		}

		return $section === NULL ? $this->extra : @$this->extra[$section]; // @ - may not exist, we dont care
	}



	/**
	 * Get path to addons.neon config file
	 * @return string
	 */
	private function getAddonsNeonPath()
	{
		if ($x = $this->getExtra('addons-neon')) return $x; // explicit path given

		$appDir = $this->getExtra('app-dir') ?: 'app/';
		return "$appDir/config/addons.neon";
	}

	private function loadAddonsNeon()
	{
		$path = $this->getAddonsNeonPath();
		$this->addonConfig = file_exists($path) ? Neon::decode(file_get_contents($path)) : array();
	}

	private function saveAddonsNeon()
	{
		$path = $this->getAddonsNeonPath();
		file_put_contents($path, self::HEADER . "\n\n" . Neon::encode($this->addonConfig, Neon::BLOCK));
	}



	/*****************  custom workers  *****************j*d*/


	/**
	 * Save extras section into addons.neon
	 */
	private function saveAddonInfo()
	{
		$this->addonConfig['addons'][$this->package->getPrettyName()] = $this->getExtra();
	}



	/**
	 * Copy assets to designated directories
	 */
	private function copyAssets()
	{
		// TODO
	}



	/**
	 * Run custom installers (registered in extra.nette-addon.addon-section)
	 */
	private function runCustomInstallers()
	{
		$customInstallers = array();
		$autoloads = array();
		foreach ($this->repo->getPackages() as /** @var PackageInterface $pkg */ $pkg) {
			if ($x = @$pkg->getExtra()['nette-addon']['addon-section']) {
				$customInstallers += $x;
				$autoloads[] = array($pkg, parent::getInstallPath($pkg)); // FIXME: ugly!
			}
		}
		// echo "Registered nette custom installers:"; var_dump($customInstallers);

		// Class loader for custom installers
		$generator = new AutoloadGenerator;
		$map = $generator->parseAutoloads($autoloads);
		$classLoader = $generator->createLoader($map);
		$classLoader->register();

		$extra = $this->getExtra();
		foreach ($customInstallers as $section => $className) {
			if ( ! isset($extra[$section])) continue; // no section for this installer

			echo "\tNette Addon: Running custom installer for section '$section'\n"; // with config: "; var_dump($extra[$section]);

			/** @var \Nette\Addons\CustomInstallers\IInstaller $installer */
			$installer = new $className;
			$installer->install($this->repo, $this->package, $extra[$section]); // FIXME: update/uninstall
		}
	}

}
