<?php

use Composer\Package\PackageInterface;
use Nette\Utils\Neon;
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
		// Base install command
		parent::install($repo, $package);

		// Save extras section into addons.neon
		if ($extra = $this->getExtra($package)) {
			$appDir = isset($extra['app-dir']) ? $extra['app-dir'] : ($this->composer->getConfig()->get('home') . '/app/');
			echo "AppDir: $appDir\n";

			$path = "$appDir/addons.neon";
			$addons = file_exists($path) ? Neon::decode(file_get_contents($path)) : array();
			$addons['addons'][$package->getPrettyName()] = $extra;
			file_put_contents($path, Neon::encode($addons, Neon::BLOCK));
			echo "Saved\n";
		}

		// TODO: move resources to appropriate dirs
	}



	/**
	 * @return array|null
	 */
	private function getExtra(PackageInterface $package, $section = NULL)
	{
		$extra = $package->getExtra();
		$ret = @$extra['nette-addon'] ?: @$extra['nette']; // @ - just to make it simple
		return $ret && $section !== NULL ? @$ret[$section] : NULL;
	}
}
