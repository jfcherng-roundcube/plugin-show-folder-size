# Roundcube Plugin: Show Folder Size

[![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/jfcherng-roundcube/plugin-show-folder-size/frontend/master?style=flat-square&label=build%20frontend)](https://github.com/jfcherng-roundcube/plugin-show-folder-size/actions)
[![GitHub Workflow Status (branch)](https://img.shields.io/github/workflow/status/jfcherng-roundcube/plugin-show-folder-size/backend/master?style=flat-square&label=build%20backend)](https://github.com/jfcherng-roundcube/plugin-show-folder-size/actions)
[![Packagist](https://img.shields.io/packagist/dt/jfcherng-roundcube/show-folder-size?style=flat-square)](https://packagist.org/packages/jfcherng-roundcube/show-folder-size)
[![Packagist Version](https://img.shields.io/packagist/v/jfcherng-roundcube/show-folder-size?style=flat-square)](https://packagist.org/packages/jfcherng-roundcube/show-folder-size)
[![Project license](https://img.shields.io/github/license/jfcherng-roundcube/plugin-show-folder-size?style=flat-square)](https://github.com/jfcherng-roundcube/plugin-show-folder-size/blob/v6/LICENSE)
[![GitHub stars](https://img.shields.io/github/stars/jfcherng-roundcube/plugin-show-folder-size?style=flat-square&logo=github)](https://github.com/jfcherng-roundcube/plugin-show-folder-size/stargazers)
[![Donate to this project using Paypal](https://img.shields.io/badge/paypal-donate-blue.svg?style=flat-square&logo=paypal)](https://www.paypal.me/jfcherng/5usd)

A Roundcube plugin which shows folder size.

## Requirements

I only test this plugin with following environments. Other setup may work with luck.

- PHP: >= `7.1.3`
- Roundcube: `1.3.9`, `1.4.0`
- Supported skins: `Classic`, `Larry`, `Elastic`

If you need support for PHP `5.4` ~ `7.0`, go to the
[php5](https://github.com/jfcherng-roundcube/plugin-show-folder-size/tree/php5)
branch or just let Composer decide the version to be installed. But note that
I am not meant to maintain the `php5` branch because even PHP 5.6 is quite outdated nowadays.

## Demo

![demo](https://raw.githubusercontent.com/jfcherng-roundcube/plugin-show-folder-size/master/docs/screenshot/demo.png)

## How to install this plugin in Roundcube

### Install via Composer (Recommended)

This plugin has been published on [Packagist](https://packagist.org) by the name of [jfcherng-roundcube/show-folder-size](https://packagist.org/packages/jfcherng-roundcube/show-folder-size).

1. Go to your `ROUNDCUBE_HOME` (i.e., the root directory of your Roundcube).
2. Run `composer require jfcherng-roundcube/show-folder-size`.
3. If you want to do plugin configuration, copy `config.inc.php.dist` to `config.inc.php` and then edit `config.inc.php`.

### Install manually

1. Create folder `show_folder_size` in `ROUNDCUBE_HOME/plugins` if it does not exist.
2. Copy all plugin files there.
3. If you want to do plugin configuration, copy `config.inc.php.dist` to `config.inc.php` and then edit `config.inc.php`.
   (You can skip this step since there is no configuration for this plugin at this moment.)
4. Edit your Roundcube's config file (`ROUNDCUBE_HOME/config/config.inc.php` or maybe `/etc/roundcube/config.inc.php`), locate `$config['plugins']` and add `'show_folder_size',`.

```php
<?php

// some other codes...

$config['plugins'] = [
    // some other plugins...
    'show_folder_size', // <-- add this line
];
```

## For Developers

### Compile Frontend Assets

- You will need to have [Node.js](https://nodejs.org) installed.
- Install dependencies via `npm install` (or `yarn` if you prefer)
- Compile assets with `./compile.sh`
