# Roundcube Plugin: Show Folder Size

[![Codacy grade](https://img.shields.io/codacy/grade/3a7a07d2ed67434e8e8582ea4ec9867b/v6?style=flat-square)](https://app.codacy.com/project/jfcherng/roundcube-plugin-show-folder-size/dashboard)
[![Packagist](https://img.shields.io/packagist/dt/jfcherng/show-folder-size?style=flat-square)](https://packagist.org/packages/jfcherng/show-folder-size)
[![Packagist Version](https://img.shields.io/packagist/v/jfcherng/show-folder-size?style=flat-square)](https://packagist.org/packages/jfcherng/show-folder-size)
[![Project license](https://img.shields.io/github/license/jfcherng/roundcube-plugin-show-folder-size?style=flat-square)](https://github.com/jfcherng/roundcube-plugin-show-folder-size/blob/v6/LICENSE)
[![GitHub stars](https://img.shields.io/github/stars/jfcherng/roundcube-plugin-show-folder-size?style=flat-square&logo=github)](https://github.com/jfcherng/roundcube-plugin-show-folder-size/stargazers)
[![Donate to this project using Paypal](https://img.shields.io/badge/paypal-donate-blue.svg?style=flat-square&logo=paypal)](https://www.paypal.me/jfcherng/5usd)

A Roundcube plugin which shows folder size.

## Requirements

I only test this plugin with following environments. Other setup may work with luck.

- PHP: >= `7.1.3`
- Roundcube: `1.3.9`, `1.4.0`
- Supported skins: `Classic`, `Larry`, `Elastic`

If you need support for PHP `5.4` ~ `7.0`, go to
[php5](https://github.com/jfcherng/roundcube-plugin-show-folder-size/tree/php5)
branch or just let Composer decide the version to be installed.

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
