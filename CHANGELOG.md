
## VERSION 0  INIT

 * Version **0.4** - Add mailbox options button
   * 2019-06-26 18:10  **0.4.3**  es_ES localization
      * b949aee Add liip/rmt as a dev dep to composer.json
      * 1593af2 Locale coding style fix
      * a660bd2 Add es_ES
      * f9ccec7 Toolbar title uses "show_folder_size (longer)"
      * cdc604d Update deps
      * 0d7e235 Fix compilation stopped when an input .less file is empty
      * 3573c87 Remove folders size before send a API request
   * 2019-06-22 04:49  **0.4.2**  nits
      * 10eef4b Rename skins stylesheets to main.less
      * 389ebca Simplify CSS rules for Larray skin
      * a46100d Add skins/_variables.less
      * 1cc5035 Always uses initiated "rcube_storage" object
      * a362fb3 Stop init if _action is not set and not plugin API call
   * 2019-06-21 17:23  **0.4.1**  nits
      * 4233e56 Fix Elastic mailbox options icon does not show up
   * 2019-06-21 17:12  **0.4.0**  initial release
      * 5ed62f4 Update readme
      * 9aefe8f Update demo screenshot
      * fdb7663 Add a configurable button to mailbox options
      * 939a1d4 Update .editorconfig
      * 9efcbe2 API can response partial folder size (or all)
      * 7cc24e4 Shrink images

 * Version **0.3** - Support Elastic skin
   * 2019-06-21 08:50  **0.3.0**  initial release
      * 15b7107 nits
      * 0fa6239 Update .php_cs
      * 018fe5a Fix the Classic skin show twice folder size
      * 1b38e8b Disable plugin button before the API responses
      * 043fc8e Add support for Elastic skin (since RC 1.4)
      * 3c0bff5 Code tidy
      * 8b605b2 Update button image
      * c2674f7 Update composer.json (bump php-cs-fixer)
      * ae5ed3b Code tidy

 * Version **0.2** - nits
   * 2019-06-21 05:27  **0.2.8**  Reduce HTTP requests
      * 11ed356 Add a single API to get all folder sizes
      * e96553c Update .php_cs
      * 3eb4f54 Use markdown format for CHANGELOG
      * 5859dd3 Fix permission bits for compile.sh
      * 703ffaf Check asset files exists before compilation
      * 86d0e3f Rename repo to roundcube-plugin-show-folder-size
   * 2019-06-20 23:26  **0.2.7**  Fix subfolder's size
      * 0127546 Config "auto_show_folder_size" is now false by default
      * fa77dc0 Use modern frontend tool for assets
      * d3d2ef8 Fix not showing subfolder's size
      * e2d9b98 Code tidy
   * 2019-06-20 08:41  **0.2.6**  Fix composer package
      * c68fd00 Update readme
      * a1ef82f Try to fix installation via composer
      * 96c3e19 Code tidy
      * ff2b235 Update readme
   * 2019-06-19 02:47  **0.2.5**  RoundCube plugin installer
      * e189d10 Update composer.json for RoundCube plugin installer
   * 2019-06-18 21:04  **0.2.4**  fix tiny UI problem
      * 687b7f6 Bump version
      * 73b3f07 Do not show button on some pages
      * c3a7449 year++
      * f431360 Update README
      * a5bbb31 Rename CHANGELOG.md -> CHANGELOG
   * 2018-08-11 04:49  **0.2.3**  Fix typo
      * 2e07ea4 Fix typo
   * 2018-07-04 15:55  **0.2.2**  initial release
      * 7239895 Add .rmt.yml
      * 227e645 Fix php-cs-fix rules for PHP ^5.4.0
      * 327ba38 Replace "show_folder_size" with \_\_CLASS\_\_