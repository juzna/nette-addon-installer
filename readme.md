# Nette Addon Installer

This is a [custom installer](http://getcomposer.org/doc/articles/custom-installers.md) for Composer packaging system which
helps installing Nette Addons.



## Status
This projects is in very early stage of development, there is not much info yet.
Please contact me for further info.



## Usage
If you want to use an existing Nette Addon, please refer to [sandbox](https://github.com/juzna/nette-addons-sandbox) with integrated support.



## Creating an Addon
Please read Composer's manual on [custom installers](http://getcomposer.org/doc/articles/custom-installers.md) for an introduction.

To create a new **Nette Addon**, simply create it as a *Composer package*. There are only 3 differences from normal Composer packages:

1. Set *type* to `nette-addon`
2. Add this installer as a requirement: `nette/addon-installer`
3. Nette Addon related configuration goes into `extra` section, `nette-addon` subsection. Possible options are listed below.



## Examples
Here are examples of Nette Addons: [Visual Paginator](https://github.com/juzna/nette-visual-paginator/blob/master/composer.json)
and [Kdyby CURL](https://github.com/juzna/nette-visual-paginator/blob/master/composer.json).



## Options
[Options](https://github.com/juzna/nette-addon-installer/blob/master/options.md)



## Addons
Here is a list of experimental [addons](https://github.com/juzna/nette-addon-installer/blob/master/addons.md) which are ready for this installer.



## Changelog
This projects is in early development phase, many changes and compatibility breaks will occur.
Please see [change log](https://github.com/juzna/nette-addon-installer/blob/master/changelog.md) for details.
