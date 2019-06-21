<?php

final class show_folder_size extends rcube_plugin
{
    /**
     * We only load this plugin in the 'mail' task.
     *
     * @var string
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
    public function init()
    {
        if ($this->can_stop_init()) {
            return;
        }

        $RCMAIL = rcmail::get_instance();
        $skin = $RCMAIL->config->get('skin');

        $this->load_plugin_config();

        $this->add_texts('locales', true);
        $this->register_action('plugin.folder-size', [$this, 'action_folder_size']);

        $this->add_plugin_assets();
        $this->add_plugin_buttons($skin);
    }

    /**
     * The action handler for "plugin.folder-size".
     */
    public function action_folder_size()
    {
        $RCMAIL = rcmail::get_instance();
        $OUTPUT = $RCMAIL->output;
        $STORAGE = $RCMAIL->storage;

        // sanitize: _folders
        $folders = rcube_utils::get_input_value('_folders', rcube_utils::INPUT_POST) ?: '__ALL__';
        if (\is_string($folders)) {
            $folders = $folders === '__ALL__' ? $STORAGE->list_folders() : [$folders];
        }

        // sanitize: _humanize
        $humanize = \filter_var(
            rcube_utils::get_input_value('_humanize', rcube_utils::INPUT_POST),
            \FILTER_VALIDATE_BOOLEAN
        );
        $humanize = isset($humanize) ? $humanize : true;

        $sizes = $this->get_folder_size($folders, $humanize);

        $OUTPUT->command('plugin.callback_folder_size', $sizes);
        $OUTPUT->send();
    }

    /**
     * Determine can we stop the plugin initialization.
     *
     * @return bool
     */
    private function can_stop_init()
    {
        $action = rcube_utils::get_input_value('_action', rcube_utils::INPUT_GET);

        // some pages should not use this plugin
        if (\in_array($action, ['get'])) {
            return true;
        }

        return false;
    }

    /**
     * Add plugin assets.
     */
    private function add_plugin_assets()
    {
        $this->include_stylesheet($this->local_skin_path() . '/' . __CLASS__ . '.css');
        $this->include_script('js/main.min.js');

        if ($this->config->get('auto_show_folder_size')) {
            $this->include_script('js/exec.min.js');
        }
    }

    /**
     * Add a plugin buttons.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons($skin)
    {
        if ($this->config->get('show_mailboxoptions_button')) {
            $attrs = [
                'type' => 'link-menuitem',
                'label' => __CLASS__ . '.show_folder_size (longer)',
                'title' => __CLASS__ . '.show_folder_size (longer)',
                'class' => 'show-folder-size active',
                'href' => '#',
                'onclick' => 'pluginShowFolderSize();',
            ];

            $this->add_button($attrs, 'mailboxoptions');
        }

        if ($this->config->get('show_toolbar_button')) {
            $attrs = [
                'type' => 'link',
                'label' => __CLASS__ . '.show_folder_size',
                'title' => __CLASS__ . '.show_folder_size',
                'class' => 'show-folder-size button',
                'href' => '#',
                'onclick' => 'pluginShowFolderSize();',
            ];

            if ($skin === 'elastic') {
                $attrs = \array_merge($attrs, [
                    'innerclass' => 'inner',
                ]);
            }

            $this->add_button($attrs, 'toolbar');
        }
    }

    /**
     * Load plugin configuration.
     */
    private function load_plugin_config()
    {
        $RCMAIL = rcmail::get_instance();

        $this->load_config('config.inc.php.dist');
        $this->load_config('config.inc.php');

        $this->config = $RCMAIL->config;
    }

    /**
     * Get size for folders.
     *
     * @param array $folders  folder names
     * @param bool  $humanize format the result for human reading
     *
     * @return int[]|string[] an array in the form of [folder_1 => size_1, ...]
     */
    private function get_folder_size(array $folders, $humanize = false)
    {
        $RCMAIL = rcmail::get_instance();
        $STORAGE = $RCMAIL->storage;

        // fast array_unique() for folders
        $folders = \array_keys(\array_count_values($folders));
        $sizes = \array_map([$STORAGE, 'folder_size'], $folders);

        if ($humanize) {
            $sizes = \array_map([$RCMAIL, 'show_bytes'], $sizes);
        }

        return \array_combine($folders, $sizes);
    }
}
