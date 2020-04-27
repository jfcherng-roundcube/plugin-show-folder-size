<?php

declare(strict_types=1);

namespace Jfcherng\Roundcube\Plugin\ShowFolderSize;

use rcmail;

trait RoundcubePluginTrait
{
    /**
     * Append a button to a certain container.
     *
     * @param array  $p         Hash array with named parameters (as used in skin templates)
     * @param string $container Container name where the buttons should be added to
     *
     * @see rcube_remplate::button()
     */
    abstract public function add_button(array $p, string $container);

    /**
     * Register a handler for a specific client-request action.
     *
     * The callback will be executed upon a request like /?_task=mail&_action=plugin.myaction
     *
     * @param string $action   Action name (should be unique)
     * @param mixed  $callback Callback function as string
     *                         or array with object reference and method name
     */
    abstract public function register_action(string $action, $callback);

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

        $btns = \array_map(function (array $btn) use ($skin): array {
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

        $btns = \array_map(function (array $btn) use ($skin): array {
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

        $btns = \array_map(function (array $btn) use ($skin): array {
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

        $btns = \array_map(function (array $btn) use ($skin): array {
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
    private function loadPluginConfigurations(): void
    {
        $configFiles = [
            "{$this->home}/config.inc.php.dist",
            "{$this->home}/config.inc.php",
        ];

        $rcmail = rcmail::get_instance();

        $this->config = \array_reduce(
            $configFiles,
            function (array $carry, string $file): array {
                \is_file($file) && (include $file);

                return \array_merge($carry, (array) ($config ?? []));
            },
            []
        );

        $rcmail->config->merge([$this->ID => $this->config]);
    }

    /**
     * Load user plugin preferences.
     */
    private function loadPluginPreferences(): void
    {
        $rcmail = rcmail::get_instance();

        $prefsDefault = [
            'enabled' => 1,
            'viewer' => $this->config['viewer'],
            'view_button_layouts' => $this->config['view_button_layouts'],
        ];

        $prefsUser = $rcmail->user->get_prefs()[$this->ID] ?? [];

        $this->prefs = \array_merge($prefsDefault, $prefsUser);
    }

    /**
     * Expose plugin configurations.
     *
     * @param null|array $keys the keys which will be exposed, null will expose all
     */
    private function exposePluginConfigurations(?array $keys = null): void
    {
        $rcmail = rcmail::get_instance();

        if (null === $keys) {
            $config = $this->config;
        } else {
            $config = [];
            foreach ($keys as $key) {
                $config[$key] = $this->config[$key];
            }
        }

        $rcmail->output->set_env("{$this->ID}.config", $config);
    }

    /**
     * Register plugin actions.
     */
    private function registerPluginActions(): void
    {
        $actions = (array) ($this->actions ?? []);

        foreach ($actions as $action => $handler) {
            $this->register_action("plugin.{$this->ID}.{$action}", [$this, $handler]);
        }
    }
}
