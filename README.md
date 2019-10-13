# Roundcube Plugin: Show Folder Size

<a href="https://plugins.roundcube.net/packages/jfcherng/show-folder-size"><img alt="Roundcube Plugins" src="https://img.shields.io/badge/dynamic/json?style=flat-square&label=downloads&query=$.package.downloads.total&url=https://plugins.roundcube.net/packages/jfcherng/show-folder-size.json"></a>
<a href="https://github.com/jfcherng/roundcube-plugin-show-folder-size/tags"><img alt="GitHub tag (latest SemVer)" src="https://img.shields.io/github/tag/jfcherng/roundcube-plugin-show-folder-size?style=flat-square&logo=github"></a>
<a href="https://github.com/jfcherng/roundcube-plugin-show-folder-size/blob/master/LICENSE"><img alt="Project license" src="https://img.shields.io/github/license/jfcherng/roundcube-plugin-show-folder-size?style=flat-square&"></a>
<a href="https://github.com/jfcherng/roundcube-plugin-show-folder-size/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/jfcherng/roundcube-plugin-show-folder-size?style=flat-square&logo=github"></a>
<a href="https://www.paypal.me/jfcherng/5usd" title="Donate to this project using Paypal"><img src="https://img.shields.io/badge/paypal-donate-blue.svg?style=flat-square&logo=paypal" /></a>

A Roundcube plugin which shows folder size.


## Requirements

I only test this plugin with following environments. Other setup may work with luck.

- PHP: >= `7.1.3`
- Roundcube: `1.3.9`, `1.4-rc1`
- Supported skins: `Classic`, `Larry`, `Elastic`

If you need to support PHP `5.4` ~ `7.0`, go for [0.4.8](https://github.com/jfcherng/roundcube-plugin-show-folder-size/releases/tag/0.4.8) or simply use Composer to install.


## Demo

![demo](https://raw.githubusercontent.com/jfcherng/roundcube-show-folder-size-plugin/master/docs/screenshot/demo.png)


## How to install this plugin in Roundcube


### Install via Composer

This plugin has been published on [the official Roundcube plugin repository](https://plugins.roundcube.net) by the name of [jfcherng/show-folder-size](https://plugins.roundcube.net/packages/jfcherng/show-folder-size).

1. Go to your `ROUNDCUBE_HOME` (i.e., the root directory of your Roundcube).
2. Run `$ composer require jfcherng/show-folder-size`.
3. You may edit the `config.inc.php` under this plugin's directory if you want to do some configurations.


### Install manually

1. Create folder `show_folder_size` in `ROUNDCUBE_HOME/plugins` if it does not exist.
2. Copy all plugin files there.
3. If you want to do plugin configuration, copy `config.inc.php.dist` to `config.inc.php` and then edit `config.inc.php`.
4. Edit your Roundcube's config file (`ROUNDCUBE_HOME/config/config.inc.php` or maybe `/etc/roundcube/config.inc.php`), locate `$config['plugins']` and add `'show_folder_size',`.

```php
<?php

// some other codes...

$config['plugins'] = [
    // some other plugins...
    'show_folder_size', // <-- add this line
];
```
