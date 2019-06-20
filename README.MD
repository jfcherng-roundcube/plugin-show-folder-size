A Roundcube plugin which shows folder size.


## Requirements

- PHP: >= `5.4.0`
- Roundcube: I only tested this plugin with `1.1.12`, `1.3.9` and `1.4-rc1`


## Demo

![](https://raw.githubusercontent.com/jfcherng/roundcube-show-folder-size-plugin/master/doc/screenshot/demo.png)


## How to install this plugin in Roundcube?


### Install via Composer

This plugin has been [published](https://plugins.roundcube.net/packages/jfcherng/show-folder-size) on the official Roundcube plugin repository.

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

$config['plugins'] = array(
    // some other plugins...
    'show_folder_size', // <-- add this
);
```


Supporters <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ATXYY9Y78EQ3Y" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" /></a>
==========

Thank you guys for sending me some cups of coffee.
