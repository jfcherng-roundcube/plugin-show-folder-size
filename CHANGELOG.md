
## VERSION 0  INIT

 * Version **0.7** - show in popup dialog
   * 2025-04-08 01:12  **0.7.21**  Update Italian
      * 3d85d14 chore: update deps
      * e5ae3c0 chore: update Italian translation
   * 2024-07-07 02:49  **0.7.20**  show total folder size
      * 1114a60 chore: recompile assets
      * f1c57e1 feat: show total folder size
      * d92fdf8 chore: update deps
   * 2022-06-29 08:43  **0.7.19**  pt_PT localization
      * 7e33cf2 chore: recompile assets
      * a042f4f chore: update deps
      * 0981b2b chore: show version (#32)
      * 5b481f2 Create pt_PT.inc (#31)
   * 2022-06-08 17:48  **0.7.18**  add cs_CZ localization
      * c25449b feat: add cs_CZ localization (by @RCejdik)
   * 2022-06-07 09:26  **0.7.17**  Update deps
      * bd74aa2 chore: recompile assets
      * b3abbca chore: update deps
   * 2022-03-09 20:50  **0.7.16**  add es_ES localization
      * bbe1a17 feat: add es_ES localization (by @nodo50)
   * 2022-03-09 19:44  **0.7.15**  fix for folder name is numeric
      * 5f777c3 fix: 500 error when folder name is numeric
      * 0450ce8 chore: recompile assets
      * 5d1248d chore: update deps
      * d2e7f8e chore: license year +1
      * 0dde4e0 chore(ci): fix "Error: Must use import to load ES Module"
      * 727afda chore(ci): fix frontend CI not working
      * c113850 chore(ci): also test against PHP 8.0
      * 48523a6 refactor: simplify CSS rules
   * 2021-11-30 01:05  **0.7.14**  fix color for RC 1.5 dark mode
      * 4f2d8f9 chore: recompile assets
      * 1d2cfc6 fix: bad folder name color with RC 1.5 dark theme
      * efc74e4 chore: update deps
   * 2021-08-11 12:19  **0.7.13**  fix deps
      * 88317af chore: update deps
      * 971afce chore: remove `replace` directive from composer.json (#27)
   * 2021-07-26 15:50  **0.7.12**  remove distributed RMT
      * e6001bb chore: no need to distribute RMT
   * 2021-07-25 12:27  **0.7.11**  fix versioning
      * 0033c78 chore: add RMT executable
      * 893ad15 chore: update deps
      * 009495d chore: remove version info from composer.json
      * 30d735d chore: update lisence year
      * 43b623e docs: there is no configuration available at this moment
   * 2021-02-19 19:09  **0.7.10**  Add ru_RU localization
      * 90e018a feat: add ru_RU localization (by @kotsar)
   * 2020-12-09 10:12  **0.7.9**  add Italian/German localizations
      * 504d214 chore: exclude node_modules for php-cs-fixer
      * 624ce23 chore: recompile assets
      * 0560ef1 chore: update deps
      * 6c12023 Added italian translation (#24)
      * 929a1a6 Added german translation (#23)
      * 6464dce chore: recompile assets
      * 9e381a0 chore: update deps
      * 5fe8518 chore(ci): Composer 2 no longer needs hirak/prestissimo
   * 2020-08-21 03:38  **0.7.8**  Update Hungarian localization
      * 50bd482 chore: update deps
      * cd95e3a Actualize hungarian translation (#21)
      * 05c58f6 chore: update deps
   * 2020-06-19 20:38  **0.7.7**  add fr_FR localization
      * 5995c08 feat: add fr_FR localization (by @rdacn)
      * 3d5b59c chore: nits
      * 9bce887 chore: update build script
   * 2020-06-17 22:42  **0.7.6**  fix: unsubscribed mailboxes make popup not shown
      * ed97a2c fix: unsubscribed mailboxes make popup not shown
      * 129afae chore: update deps
      * 3eaa32e docs: update readme for developers
      * e68e05d docs: update screenshot
   * 2020-06-11 00:33  **0.7.5**  fix: no way closing the popup dialog on Elastic mobile
      * bddd3b3 fix: no way closing the popup dialog on Elastic mobile
      * 1da4c63 refactor: tidy codes
      * a395f2a docs: remove unexpected chars
   * 2020-06-10 15:43  **0.7.4**  Show cumulative folder sizes
      * 6617d27 mod: use auto width table layout
      * d15efce docs: update screenshot
      * b2d837e chore: tidy codes
      * 9962ac7 feat: also show cumulative folder sizes
      * 3718ec7 chore: update deps
   * 2020-06-09 16:31  **0.7.3**  Add localization: hu_HU
      * 4dabd19 Create hu_HU.inc
      * 0ef98e0 chore(ci): improve CI scripts
      * 221e6af chore: update deps
   * 2020-05-23 03:26  **0.7.2**  feat: add translation: nb_NO
      * a206c1b Add translations nb_NO
   * 2020-05-21 18:21  **0.7.1**  clickable folder name & non-modal dialog
      * a1c11fd fix: use non-modal dialog
      * f4ccf09 feat: clickable folder name
   * 2020-05-21 17:34  **0.7.0**  initial release
      * 5cd2a1c chore: add translations: zh_CN and zh_TW
      * f50a568 refactor: tidy codes
      * cc7f9d9 chore: remove unused files and CSS rules
      * 6aa7a61 docs: update screenshot
      * 5e1965c refactor: improve popup dialog content
      * e240f4e refactor: use popup dialog to show folder size
      * c6ecdec chore: update deps
      * 044faea refactor: tidy codes

 * Version **0.6** - refactor
   * 2020-05-12 00:37  **0.6.4**  feat: add nb_NO translation
      * 6cac2be Add: Norwegian
      * c6cebba refactor: tidy codes
      * 51ac19e refactor: use "get-folder-size" as the action name
      * 877cba3 chore: update deps
      * a37e18a refactor: make intelephense happy
      * 1c1a494 chore: add CI coding style chceking
      * 9e0c1bf chore: tidy codes
      * 6b02998 chore: add babel-eslint for linting latest JS syntax
   * 2020-04-30 16:41  **0.6.3**  some refactoring
      * 572bfdb refactor: tidy codes
      * 457b474 chore: update RoundcubeHelper
      * e00efdb docs: update readme
      * ff31071 docs: update LICENSE year
      * aba6748 chore: allow installing with PHP 8 for test purpose
   * 2020-04-27 22:04  **0.6.2**  nits
      * 9cb453b refactor: tidy codes
   * 2020-04-26 10:22  **0.6.1**  some refactoring
      * 58de565 refactor: tidy codes
      * b909644 chore: update deps
      * 745329e chore: move js/ to assets/
      * 54bfa18 refactor: simplify codes for auto_show_folder_size
   * 2020-04-26 03:09  **0.6.0**  initial release
      * 4dcaec9 chore: update deps
      * e21c53b refactor: extract common methods between plugins
      * 1887d1f chore: move locales/ to localization/
      * 4cd40e4 chore: add FUNDING.yml
      * 6888b1d chore: update package informations
      * 9e111ba Add bg_BG translation
      * 4890b64 docs: make markdownlint happy
      * 0b6e330 docs: update badges to packagist's
      * 03c1bad chore: update deps
      * 34df60b [ImgBot] Optimize images (#11)

 * Version **0.5** - PHP 7.1
   * 2020-01-19 13:11  **0.5.4**  Add translations: de_DE and id_ID
      * 1336209 Release of new version 0.4.12
      * 6f57f1a Add translations: de_DE and id_ID
   * 2020-01-08 14:49  **0.5.3**  Add translation: lv_LV
      * f7a87c4 Release of new version 0.4.11
      * 226eb5a Update deps
      * 37d1e53 Update deps
      * 9957f49 Add translation: lv_LV
      * 96b0532 Update readme
   * 2019-11-04 22:41  **0.5.2**  Fix get_base_skin_name() for unsupported base skins
      * 2c79582 Adapt PHP 7 syntax
      * 95b1e10 Update deps
      * c719130 Release of new version 0.4.10
      * 3f99c1d Fix get_base_skin_name() for unsupported base skins
      * ef36a62 Update deps
      * 9f55195 Remove PHP ^7 from composer.json
      * a90b268 nits
      * ae1add0 $ composer fix
      * afe1e43 Update deps
      * c789a8a Fix readme for the php5 branch information
      * f657de6 Fix links in readme
      * 34c3769 Update readme (flat-square style badges)
      * 5dbf2ed nits
      * 0fcf32c Release of new version 0.4.9
      * eaee648 Lowercase variable names
      * 09a0497 Support extended skins
      * ecf3188 Fix PHP 5 compatibility
   * 2019-10-31 00:47  **0.5.1**  Support extended skins
      * 5e39ae8 Update deps
      * e49ef91 Use strict_types
      * a64854c Lowercase all variable names
      * 9a5c9ef Tidy code for allowing extended skins
      * b57b153 Support extended skins
   * 2019-10-13 17:19  **0.5.0**  initial release
      * a63095c Update deps
      * f7d3ab4 Bump min PHP version to 7.1.3
      * ccfb65c Update readme (flat-square style badges)
      * 663778d nits

 * Version **0.4** - Add mailbox options button
   * 2020-01-19 13:09  **0.4.12**  Add translations: de_DE and id_ID
      * 6f57f1a Add translations: de_DE and id_ID
   * 2020-01-08 14:47  **0.4.11**  Add translation: lv_LV
      * 226eb5a Update deps
      * 9957f49 Add translation: lv_LV
      * 96b0532 Update readme
   * 2019-11-04 22:34  **0.4.10**  Fix get_base_skin_name() for unsupported base skins
      * fec0acd Fix get_base_skin_name() for unsupported base skins
      * a90b268 nits
      * f657de6 Fix links in readme
      * 34c3769 Update readme (flat-square style badges)
      * 5dbf2ed nits
   * 2019-10-31 01:02  **0.4.9**  Fix PHP 5 compatibility
      * 97f2b94 test
      * eaee648 Lowercase variable names
      * 09a0497 Support extended skins
      * ecf3188 Fix PHP 5 compatibility
   * 2019-09-11 18:43  **0.4.8**  fr_FR translation
      * a837ddf Update compiled assets
      * 5898ac9 Update deps
      * c010634 Add translation: fr_FR
      * d380868 Update readme to use badges from shields.io
      * 9b977ee Update readme
   * 2019-08-12 20:43  **0.4.7**  nits
      * 91e0541 Update deps
      * ac4597c Better code structor (reuseable methods)
      * 9c11383 Fix compile.sh cleancss flag
      * f718a18 Update .gitignore
      * 9ad5dc4 Fix CVE-2019-10744 by lodash@^4.17.13
   * 2019-07-13 05:21  **0.4.6**  revert "Remove can_stop_init()":
      * b53fb93 Update compiled assets
      * 37ab049 Update deps
      * 0e398ce Small code tweak (easier understanding)
      * 9c502df Revert "Remove can_stop_init()"
   * 2019-07-07 08:43  **0.4.5**  nits
      * 64b7231 Remove can_stop_init()
      * 9bc87e4 Fix JSDoc
      * 2cf7d0b Some coding style tweaks
      * 5bdb7b8 Fix LESS indentation width
      * 6088e03 README.MD -> README.md
      * e21ecec Update readme
      * 2f2e4df nits
      * de2370d Re-compile assets
      * 53f3fc6 Simplify the JS compilation flow
      * fa48006 Add .gitattributes
   * 2019-07-02 00:25  **0.4.4**  Do not fire useless API request
      * 5864bf5 Update compiled assets
      * 3c0ffb1 Do not fire API request if there is no mailbox list in the UI
      * 09a5c39 Change "PluginShowFolderSize" to "plugin_show_folder_size"
      * b45c197 Fix the compilation flows of JS files
      * 6101b8c Simplify LESS codes
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