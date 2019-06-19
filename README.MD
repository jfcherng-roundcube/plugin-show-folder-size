A Roundcube plugin which shows folder size.


## Requirements

- PHP: >= `5.4.0`
- Roundcube: I only tested this plugin with `1.1.12`, `1.3.9` and `1.4-rc1`


## Demo

![](https://raw.githubusercontent.com/jfcherng/roundcube-show-folder-size-plugin/master/doc/screenshot/demo.png)


## How to install this plugin in Roundcube?

1. Create folder `show_folder_size` in `ROUNDCUBE_HOME/plugins` if it does not exist.
2. Copy all plugin files there.

3. If you want to do plugin configuration, copy `config.inc.php.dist` to `config.inc.php` and then edit `config.inc.php`.

```php
<?php

$config = [
    // auto show the folder size without clicking on the toolbar button
    'auto_show_folder_size' => true,
    // show the toolbar button
    'show_toolbar_button' => true,
];
```

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
