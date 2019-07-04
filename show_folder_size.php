<?php

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
    public function init()
    {
        if ($this->can_stop_init()) {
            return;
        }

        $this->load_plugin_config();

        $skin = $this->config->get('skin');
        $local_skin_path = $this->local_skin_path();

        $this->add_texts('locales', true);
        $this->register_action('plugin.folder-size', [$this, 'action_folder_size']);

        $this->add_plugin_assets($local_skin_path);
        $this->add_plugin_buttons($skin);
    }

    /**
     * The action handler for "plugin.folder-size".
     */
    public function action_folder_size()
    {
        $RCMAIL = rcmail::get_instance();
        $STORAGE = $RCMAIL->get_storage();
        $OUTPUT = $RCMAIL->output;

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
        $action = (string) rcube_utils::get_input_value('_action', rcube_utils::INPUT_GET);

        return $action !== '' && \stripos($action, 'plugin.') !== 0;
    }

    /**
     * Add plugin assets.
     *
     * @param string $local_skin_path the local skin path such as "skins/elastic"
     */
    private function add_plugin_assets($local_skin_path)
    {
        $this->include_stylesheet("{$local_skin_path}/main.css");
        $this->include_script('js/main.min.js');

        if ($this->config->get('auto_show_folder_size')) {
            $this->include_script('js/exec.min.js');
        }
    }

    /**
     * Add plugin buttons.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons($skin)
    {
        $this->add_plugin_buttons_mailboxoptions($skin);
        $this->add_plugin_buttons_toolbar($skin);
    }

    /**
     * Add plugin buttons to mailboxoptions.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_mailboxoptions($skin)
    {
        if ($this->config->get('show_mailboxoptions_button')) {
            $attrs = [
                'type' => 'link-menuitem',
                'label' => "{$this->ID}.show_folder_size (longer)",
                'title' => "{$this->ID}.show_folder_size (longer)",
                'class' => 'show-folder-size active',
                'href' => '#',
                'onclick' => 'plugin_show_folder_size()',
            ];

            $this->add_button($attrs, 'mailboxoptions');
        }
    }

    /**
     * Add plugin buttons to toolbar.
     *
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_toolbar($skin)
    {
        if ($this->config->get('show_toolbar_button')) {
            $attrs = [
                'type' => 'link',
                'label' => "{$this->ID}.show_folder_size",
                'title' => "{$this->ID}.show_folder_size (longer)",
                'class' => 'show-folder-size',
                'href' => '#',
                'onclick' => 'plugin_show_folder_size()',
            ];

            if ($skin === 'classic') {
                $attrs['class'] .= ' button';
            }

            if ($skin === 'elastic') {
                $attrs['innerclass'] = 'inner';
            }

            if ($skin === 'larry') {
                $attrs['class'] .= ' button';
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
        $STORAGE = $RCMAIL->get_storage();

        // fast array_unique() for folders
        $folders = \array_keys(\array_count_values($folders));
        $sizes = \array_map([$STORAGE, 'folder_size'], $folders);

        if ($humanize) {
            $sizes = \array_map([$RCMAIL, 'show_bytes'], $sizes);
        }

        return \array_combine($folders, $sizes);
    }
}
