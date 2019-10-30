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
        $is_api_call = \stripos($action, 'plugin.') === 0;

        return $action !== '' && !$is_api_call;
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
    }

    /**
     * Add plugin buttons to mailboxoptions.
     *
     * @param array  $btns the buttons
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_mailboxoptions(array $btns, $skin)
    {
        foreach ($btns as $btn) {
            $this->add_button($btn, 'mailboxoptions');
        }
    }

    /**
     * Add plugin buttons to toolbar.
     *
     * @param array  $btns the buttons
     * @param string $skin the current skin name
     */
    private function add_plugin_buttons_toolbar(array $btns, $skin)
    {
        $btns = \array_map(function (array $btn) use ($skin) {
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
