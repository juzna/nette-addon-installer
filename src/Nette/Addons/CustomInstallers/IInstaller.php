<?php

namespace Nette\Addons\CustomInstallers;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;


/**
 * Interface for the package installation manager.
 * (inpired by Composer's installers)
 *
 * @author Jan Dolecek <juzna.cz@gmail.com>
 */
interface IInstaller
{
	/**
	 * Installs specific package.
	 *
	 * @param InstalledRepositoryInterface $repo    repository in which to check
	 * @param PackageInterface             $package package instance
	 */
	function install(InstalledRepositoryInterface $repo, PackageInterface $package);

	/**
	 * Updates specific package.
	 *
	 * @param InstalledRepositoryInterface $repo    repository in which to check
	 * @param PackageInterface             $initial already installed package version
	 * @param PackageInterface             $target  updated version
	 */
	function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target);

	/**
	 * Uninstalls specific package.
	 *
	 * @param InstalledRepositoryInterface $repo    repository in which to check
	 * @param PackageInterface             $package package instance
	 */
	function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package);

}
