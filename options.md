# Options

Nette Addon related options are in `composer.json` in *extra.nette-addon* section. Example:
```js
{
	"name": "juzna/nette-visual-paginator",
	"type": "nette-addon",
	...
	"extra": {
		"nette-addon": {
			"config-extensions": {
				"webLoader": "WebLoader\\Nette\\Config\\WebLoaderExtension"
			}
			"assets": [
				"example.css"
			]
		}
	}
}
```

Get inspired in *examples* section. Here is a brief summary about possible options:


## Processing
Config section can be processed in two different places,
 (a) by *custom-installer* when installing an addon or
 (b) by *Configurator* when bootstrapping the application


### Nette-addon-installer
This package (repository) is a *custom-installer* for packages of *type* `nette-addon`.
 This means that [Installer](https://github.com/juzna/nette-addon-installer/blob/master/src/Nette/Addons/Installer.php)
 class is executed whenever you install or update an addon and it can do something with it, e.g. copy files to correct places.

 One of the things it does is storing information for the next part, the *Configurator*. This info is stored in `app/config/addons.neon` file.

 Check the code for details.


### Configurator
[Configurator](https://github.com/juzna/nette-addons-sandbox/blob/addons/app/model/Configurator.php) is invoked while bootstrapping your application
 and it is supposed to prepare the configuration for it. This is done by loading `config.neon` file by default. In addition the extended configurator
 loads `addons.neon` file (created by previous part, the *Nette-addon-installer*) and does some work which is not needed while installing the plugin.

 Check the code for *loadSection<sectionName>* for details.




## Recognized sections

Here is a reference list for all supported section, both of *Nette-addon-installer* and *Configurator*.


### assets
CSS, JavaScript files (or images, flags or anything) required by addon. They're copied into public web directory (`WWW_DIR/assets/<addonName>/`)
 so that they can be served easily by the web server.

 *Target directory* can be overriden by a parameter `assets-target-dir`.

 If you also want to include assets into web page header, refer to section *web-loader*.

**Example**:
```js
"nette-addon": {
	"assets": [ "example.css" ]
}
```


### assets-optional
Like above, but only optional. User should be prompted whether to install them or not.
(not yet implemented)


### config-extensions
Extensions for Nette's [Dependency Injection Container](http://doc.nette.org/en/dependency-injection). By this extension,
 an addon can register a new *section name* in `config.neon`.

 More extensions can be defined, each has to have an unique name.

**Example**:
```js
"nette-addon": {
	"config-extensions": {
		"webLoader": "WebLoader\\Nette\\Config\\WebLoaderExtension"
	}
}
```


### extension-methods
Add new methods to existing classes, e.g. `addDatePicker` to a form container. It uses [extensibility](http://doc.nette.org/en/php-language-enhancements)
 of `Nette\Object`.


**Example**:
```js
"nette-addon": {
	"extension-methods": [
		{
			"class": "Nette\\Forms\\Container",
			"method": "addDatePicker",
			"callback": "JanTvrdik\\Components\\DatePicker::_addDatePicker"
		}
	]
}
```


### extras
**Experimental!**

A [custom installer](https://github.com/juzna/nette-extras) for common assets



### initializer
Call a static method on every request (while bootstrapping the application). This allows addons with very specific needs
 to register whatever they want.

 Either a static method name (string) or list of those.

**Example**:
```js
"nette-addon": {
	"initializer": "Bazo\\Watchdog\\NetteLogger::register"
}
```



### web-loader
Add assets to web loader, so that they're automatically loaded into a web page header.
(Work in progress)
