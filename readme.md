# Nette Addon Installer

This is a [custom installer](http://getcomposer.org/doc/articles/custom-installers.md) for Composer packaging system which
helps installing Nette Addons.



## Status

This projects is in very early stage of development, there is not much info yet.
Please contact me for further info.



## Usage

Please read Composer's manual on [custom installers](http://getcomposer.org/doc/articles/custom-installers.md) for an introduction.

To create a new **Nette Addon**, simply create it as a *Composer package*. There are only 3 differences from normal Composer packages:

1. Set *type* to `nette-addon`
2. Add this installer as a requirement: `nette/addon-installer`
3. Nette Addon related configuration goes into `extra` section, `nette-addon` subsection. Possible options are listed below.



## Examples

Here are examples of Nette Addons: [Visual Paginator](https://github.com/juzna/nette-visual-paginator/blob/master/composer.json)
and [Kdyby CURL](https://github.com/juzna/nette-visual-paginator/blob/master/composer.json).



## Options

Get inspired in *examples* section. Here is a brief summary about possible options:

- **assets** - CSS or JavaScript files required by addon; requires WebLoader addon to be installed
- **assets-optional** - like above, but only optional. User should be prompted whether to install them or not.
- **config-extensions** - extensions for Nette's Dependency Injection Container
- **extension-methods** - add new methods to existing objects
- **extras** - [custom installer](https://github.com/juzna/nette-extras) for common assets

More options to come
