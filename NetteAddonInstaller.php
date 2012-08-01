<?php

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Custom installer of Nette Addons
 *
 * @author Jan Dolecek <juzna.cz@gmail.com>
 */
class NetteAddonInstaller extends LibraryInstaller
{
	private static $supportedTypes = array(
		'nette-addon',
		'nette-assets',
	);

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
				throw new Exception("Not implemented yet");

			case 'nette-addon':
				return parent::getInstallPath($package);

			default:
				throw new Exception("Not recognized package type '{$package->getType()}'");
		}
	}



	public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
	{
		parent::install($repo, $package);

		$this->saveAddonInfo($package);
		$this->copyAssets($package);
	}



	public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
	{
		parent::update($repo, $initial, $target);

		$this->saveAddonInfo($target);
		$this->copyAssets($target);
	}



	/**
	 * @return array|null
	 */
	private function getExtra(PackageInterface $package, $section = NULL)
	{
		$extra = $package->getExtra();
		$ret = @$extra['nette-addon'] ?: @$extra['nette']; // @ - just to make it simple
		return $section === NULL ? $ret : @$ret[$section];
	}



	/*****************  custom workers  *****************j*d*/


	/**
	 * Save extras section into addons.neon
	 */
	private function saveAddonInfo(PackageInterface $package)
	{
		if($extra = $this->getExtra($package)) {
//			print_r($this->composer->getConfig());
			$appDir = isset($extra['app-dir']) ? $extra['app-dir'] : 'app/';

			$path = "$appDir/config/addons.neon";
			$addons  = file_exists($path) ? Neon::decode(file_get_contents($path)) : array();
			$addons['addons'][$package->getPrettyName()] = $extra;
			file_put_contents($path, self::HEADER . "\n\n" . Neon::encode($addons, Neon::BLOCK));
		}
	}



	/**
	 * Copy assets to designated directories
	 */
	private function copyAssets(PackageInterface $package)
	{
		// TODO
	}
}
