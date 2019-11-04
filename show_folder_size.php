<?php

declare(strict_types=1);

final class show_folder_size extends rcube_plugin
{
    /**
     * {@inheritdoc}
     */
    public $task = 'mail';

    /**
     * The loaded configuration.
     *
     * @var rcube_config
     */
    private $config;

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        if ($this->can_stop_init()) {
            return;
        }

        $this->load_plugin_config();

        // the current skin name like from `$this->config->get('skin')` is not much
        // helpful for extended skins, we try to get it's base skin name directly
        $base_skin = $this->get_base_skin_name();

        $this->add_texts('locales', true);
        $this->register_action('plugin.folder-size', [$this, 'action_folder_size']);

        $this
            ->add_plugin_assets($base_skin)
            ->add_plugin_buttons($base_skin);
    }

    /**
     * The action handler for "plugin.folder-size".
     */
    public function action_folder_size(): void
    {
        $rcmail = rcmail::get_instance();
        $storage = $rcmail->get_storage();
        $output = $rcmail->output;

        // sanitize: _folders
        $folders = rcube_utils::get_input_value('_folders', rcube_utils::INPUT_POST) ?: '__ALL__';
        $folders = $folders === '__ALL__' ? $storage->list_folders() : (array) $folders;

        // sanitize: _humanize
        $humanize = \filter_var(
            rcube_utils::get_input_value('_humanize', rcube_utils::INPUT_POST),
            \FILTER_VALIDATE_BOOLEAN
        ) ?? true;

        $sizes = $this->get_folder_size($folders, $humanize);

        $output->command('plugin.callback_folder_size', $sizes);
        $output->send();
    }

    /**
     * Determine can we stop the plugin initialization.
     */
    private function can_stop_init(): bool
    {
        $action = (string) rcube_utils::get_input_value('_action', rcube_utils::INPUT_GET);
        $is_api_call = \stripos($action, 'plugin.') === 0;

        return $action !== '' && !$is_api_call;
    }

    /**
     * Add plugin assets.
     *
     * @param string $skin the skin name
     */
    private function add_plugin_assets(string $skin): self
    {
        $this->include_stylesheet("skins/{$skin}/main.css");
        $this->include_script('js/main.min.js');

        if ($this->config->get('auto_show_folder_size')) {
            $this->include_script('js/exec.min.js');
        }

        return $this;
    }

    /**
     * Add plugin buttons.
     *
     * @param string $skin the skin name
     */
    private function add_plugin_buttons(string $skin): self
    {
        if ($this->config->get('show_mailboxoptions_button')) {
            $this->add_plugin_buttons_mailboxoptions([
                [
                    'type' => 'link-menuitem',
                    'label' => "{$this->ID}.show_folder_size (longer)",
                    'title' => "{$this->ID}.show_folder_size (longer)",
                    'class' => 'show-folder-size active',
                    'href' => '#',
                    'onclick' => 'plugin_show_folder_size()',
                ],
            ], $skin);
        }

        if ($this->config->get('show_toolbar_button')) {
            $this->add_plugin_buttons_toolbar([
                [
                    'type' => 'link',
                    'label' => "{$this->ID}.show_folder_size",
                    'title' => "{$this->ID}.show_folder_size (longer)",
                    'class' => 'show-folder-size',
                    'href' => '#',
                    'onclick' => 'plugin_show_folder_size()',
                ],
            ], $skin);
        }

        return $this;
    }

    /**
     * Add plugin buttons to mailboxoptions.
     *
     * @param array[] $btns the buttons
     * @param string  $skin the skin name
     */
    private function add_plugin_buttons_mailboxoptions(array $btns, string $skin): self
    {
        foreach ($btns as $btn) {
            $this->add_button($btn, 'mailboxoptions');
        }

        return $this;
    }

    /**
     * Add plugin buttons to toolbar.
     *
     * @param array[] $btns the buttons
     * @param string  $skin the skin name
     */
    private function add_plugin_buttons_toolbar(array $btns, string $skin): self
    {
        $btns = \array_map(function (array $btn) use ($skin): array {
            switch ($skin) {
                case 'classic':
                    $btn['class'] .= ' button';
                    break;
                case 'elastic':
                    $btn['innerclass'] = 'inner';
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

        return $this;
    }

    /**
     * Load plugin configuration.
     */
    private function load_plugin_config(): void
    {
        $rcmail = rcmail::get_instance();

        $this->load_config('config.inc.php.dist');
        $this->load_config('config.inc.php');

        $this->config = $rcmail->config;
    }

    /**
     * Get the lowercase base skin name for the current skin.
     *
     * @return string the base skin name
     */
    private function get_base_skin_name(): string
    {
        static $base_skins = ['classic', 'larry', 'elastic'];

        $rcube = rcube::get_instance();

        // information about current skin and extended skins (if any)
        $skins = (array) $rcube->output->skins;

        foreach ($base_skins as $base_skin) {
            if (isset($skins[$base_skin])) {
                return $base_skin;
            }
        }

        return isset($skins[0]) ? $skins[0] : '';
    }

    /**
     * Get size for folders.
     *
     * @param array $folders  folder names
     * @param bool  $humanize format the result for human reading
     *
     * @return int[]|string[] an array in the form of [folder_1 => size_1, ...]
     */
    private function get_folder_size(array $folders, bool $humanize = false): array
    {
        $rcmail = rcmail::get_instance();
        $storage = $rcmail->get_storage();

        $folders = \array_unique($folders);
        $sizes = \array_map([$storage, 'folder_size'], $folders);

        if ($humanize) {
            $sizes = \array_map([$rcmail, 'show_bytes'], $sizes);
        }

        return \array_combine($folders, $sizes);
    }
}
