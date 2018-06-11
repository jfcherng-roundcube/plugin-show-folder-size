A Roundcube plugin which shows folder size in the sidebar.


## Requirements

- PHP >= 5.4.0
- Roundcube >= untested


## Demo

![](https://raw.githubusercontent.com/jfcherng/roundcube-show-sidebar-size-plugin/master/doc/screenshot/demo.png)


## How to install this plugin in Roundcube?

1. Create folder `quota` in `ROUNDCUBE_HOME/plugins` if it does not exist.
2. Copy all plugin files there.

3. Edit `ROUNDCUBE_HOME/conf/config.inc.php` locate `$config['plugins']` and add `'show_sidebar_folder_size',` there:
```php
<?php

// some other codes...

$config['plugins'] = array(
    // some other plugins...
    'show_sidebar_folder_size', // <-- add this
);
```
