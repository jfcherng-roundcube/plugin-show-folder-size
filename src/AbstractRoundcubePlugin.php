<?php

declare(strict_types=1);

namespace Jfcherng\Roundcube\Plugin\ShowFolderSize;

use rcmail;
use rcube_plugin;

abstract class AbstractRoundcubePlugin extends rcube_plugin
{
    /**
     * Plugin actions and handlers.
     *
     * @var array<string,string>
     */
    public $actions = [];

    /**
     * Plugin hooks and handlers.
     *
     * @var array<string,string>
     */
    public $hooks = [];

    /**
     * The plugin configuration.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The plugin user preferences.
     *
     * @var array
     */
    protected $prefs = [];

    /**
     * The directory where localization files are located.
     *
     * @var string
     */
    protected $localizationDir = 'localization/';

    /**
     * The corresponding usable skin path for this plugin.
     *
     * @var string like "skins/larry"
     */
    protected $skinPath = '';

    /**
     * The corresponding usable skin name for this plugin.
     *
     * @var string like "larry"
     */
    protected $skinName = '';

    /**
     * The rcmail singleton.
     *
     * @var rcmail
     */
    protected $rcmail;

    /**
     * Get the default plugin preferences.
     *
     * @return array the default plugin preferences
     */
    abstract public function getDefaultPluginPreferences(): array;

    /**
     * The initiator method for child classes.
     *
     * This should be called in the very beginning of chile class' init() method.
     */
    public function init(): void
    {
        $this->rcmail = rcmail::get_instance();

        $this->loadPluginConfigurations();
        $this->loadPluginPreferences();
        $this->exposePluginPreferences();
        $this->registerPluginActions();
        $this->registerPluginHooks();

        $this->add_texts($this->localizationDir, false);

        $this->skinPath = $this->local_skin_path();
        $this->skinName = substr($this->skinPath, 6); // remove prefixed "skins/"
    }

    /**
     * Add buttons to the "attachmentmenu" container.
     *
     * @todo refactor this method into another helper class
     *
     * @param array[]     $btns the buttons
     * @param null|string $skin the skin name
     */
    public function add_buttons_attachmentmenu(array $btns, ?string $skin = null): void
    {
        $skin = $skin ?? RoundcubeHelper::getBaseSkinName();

        $btns = array_map(function (array $btn) use ($skin): array {
            $btn['_id'] = $btn['_id'] ?? 'WTF_NO_BASE_ID';
            $btn['class'] = $btn['class'] ?? '';
            $btn['classact'] = $btn['classact'] ?? '';
            $btn['innerclass'] = $btn['innerclass'] ?? '';

            $btn['type'] = 'link-menuitem';
            $btn['id'] = "attachmenu{$btn['_id']}";

            switch ($skin) {
                case 'classic':
                    $btn['class'] .= " {$btn['_id']}link";
                    $btn['classact'] .= " {$btn['_id']}link active";
                    $btn['innerclass'] .= " {$btn['_id']}link";
                    break;
                case 'elastic':
                    $btn['class'] .= " {$btn['_id']} disabled";
                    $btn['classact'] .= " {$btn['_id']} active";
                    break;
                case 'larry':
                    $btn['class'] .= ' icon';
                    $btn['classact'] .= ' icon active';
                    $btn['innerclass'] .= " icon {$btn['_id']}";
                    break;
                default:
                    break;
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'attachmentmenu');
        }
    }

    /**
     * Add buttons to the "loginfooter" container.
     *
     * @param array[]     $btns the buttons
     * @param null|string $skin the skin name
     */
    public function add_buttons_loginfooter(array $btns, ?string $skin = null): void
    {
        $skin = $skin ?? RoundcubeHelper::getBaseSkinName();

        $btns = array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['class'] = $btn['class'] ?? '';
            $btn['innerclass'] = $btn['innerclass'] ?? '';
            $btn['badgeType'] = $btn['badgeType'] ?? 'secondary';

            // should always has 'support-link' class
            $btn['class'] .= ' support-link';

            if ($skin === 'elastic') {
                $btn['class'] .= " badge badge-{$btn['badgeType']}";
                $btn['data-toggle'] = $btn['data-toggle'] ?? 'tooltip';
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'loginfooter');
        }
    }

    /**
     * Add buttons to the "mailboxoptions" container.
     *
     * @param array[]     $btns the buttons
     * @param null|string $skin the skin name
     */
    public function add_buttons_mailboxoptions(array $btns, ?string $skin = null): void
    {
        $skin = $skin ?? RoundcubeHelper::getBaseSkinName();

        foreach ($btns as $btn) {
            $this->add_button($btn, 'mailboxoptions');
        }
    }

    /**
     * Add buttons to the "taskbar" container.
     *
     * @param array[]     $btns the buttons
     * @param null|string $skin the skin name
     */
    public function add_buttons_taskbar(array $btns, ?string $skin = null): void
    {
        $skin = $skin ?? RoundcubeHelper::getBaseSkinName();

        $btns = array_map(function (array $btn) use ($skin): array {
            $btn['type'] = 'link';
            $btn['class'] = $btn['class'] ?? '';
            $btn['innerclass'] = $btn['innerclass'] ?? '';

            switch ($skin) {
                case 'classic':
                    $btn['class'] .= ' button-nthu-ee';
                    break;
                case 'elastic':
                    $btn['class'] .= ' nthu-ee manual';
                    $btn['innerclass'] .= ' inner';
                    break;
                case 'larry':
                    $btn['class'] .= ' button-nthu-ee';
                    $btn['innerclass'] .= ' button-inner';
                    break;
                default:
                    break;
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'taskbar');
        }
    }

    /**
     * Add buttons to the "toolbar" container.
     *
     * @param array[]     $btns the buttons
     * @param null|string $skin the skin name
     */
    public function add_buttons_toolbar(array $btns, ?string $skin = null): void
    {
        $skin = $skin ?? RoundcubeHelper::getBaseSkinName();

        $btns = array_map(function (array $btn) use ($skin): array {
            switch ($skin) {
                case 'classic':
                    $btn['class'] .= ' button';
                    break;
                case 'elastic':
                    $btn['innerclass'] .= ' inner';
                    break;
                case 'larry':
                    $btn['class'] .= ' button';
                    break;
                default:
                    break;
            }

            return $btn;
        }, $btns);

        foreach ($btns as $btn) {
            $this->add_button($btn, 'toolbar');
        }
    }

    /**
     * Load plugin configurations.
     */
    protected function loadPluginConfigurations(): void
    {
        $configFiles = [
            "{$this->home}/config.inc.php.dist",
            "{$this->home}/config.inc.php",
        ];

        $this->config = array_reduce(
            $configFiles,
            function (array $carry, string $file): array {
                is_file($file) && (include $file);

                return array_merge($carry, (array) ($config ?? []));
            },
            []
        );

        $this->rcmail->config->merge([$this->ID => $this->config]);
    }

    /**
     * Load user plugin preferences.
     */
    protected function loadPluginPreferences(): void
    {
        $this->prefs = array_merge(
            $this->getDefaultPluginPreferences(),
            $this->rcmail->user->get_prefs()[$this->ID] ?? []
        );
    }

    /**
     * Expose plugin configurations.
     *
     * @param null|array $keys the keys which will be exposed, null will expose all
     */
    protected function exposePluginConfigurations(?array $keys = null): void
    {
        if (null === $keys) {
            $config = $this->config;
        } else {
            $config = [];
            foreach ($keys as $key) {
                $config[$key] = $this->config[$key] ?? null;
            }
        }

        $this->rcmail->output->set_env("{$this->ID}.config", $config);
    }

    /**
     * Expose plugin preferences.
     *
     * @param null|array $keys the keys which will be exposed, null will expose all
     */
    protected function exposePluginPreferences(?array $keys = null): void
    {
        if (null === $keys) {
            $prefs = $this->prefs;
        } else {
            $prefs = [];
            foreach ($keys as $key) {
                $prefs[$key] = $this->prefs[$key] ?? null;
            }
        }

        $this->rcmail->output->set_env("{$this->ID}.prefs", $prefs);
    }

    /**
     * Register plugin actions.
     */
    protected function registerPluginActions(): void
    {
        $actions = (array) ($this->actions ?? []);

        foreach ($actions as $action => $handler) {
            $this->register_action("plugin.{$this->ID}.{$action}", [$this, $handler]);
        }
    }

    /**
     * Register plugin hooks.
     */
    protected function registerPluginHooks(): void
    {
        $hooks = (array) ($this->hooks ?? []);

        foreach ($hooks as $hook => $handler) {
            $this->add_hook($hook, [$this, $handler]);
        }
    }
}
