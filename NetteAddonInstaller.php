<?php

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

/**
 * Custom installer of Nette Addons
 *
 * @author Jan Dolecek <juzna.cz@gmail.com>
 */
class NetteAddonInstaller extends LibraryInstaller
{

	public function getInstallPath(PackageInterface $package)
	{
		throw new Exception("Not implemented yet");
	}


	public function supports($packageType)
	{
		return $packageType === 'nette-addon';
	}

}
