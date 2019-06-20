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
        $action = rcube_utils::get_input_value('_action', rcube_utils::INPUT_GET);

        // some pages should not use this plugin
        if (\in_array($action, ['get'])) {
            return;
        }

        $this->load_plugin_config();

        $this->add_texts('localization/', true);
        $this->register_action('plugin.all-folder-size', [$this, 'action_all_folder_size']);

        $this->include_stylesheet($this->local_skin_path() . '/' . __CLASS__ . '.css');
        $this->include_script('js/main.min.js');

        if ($this->config->get('auto_show_folder_size')) {
            $this->include_script('js/exec.min.js');
        }

        $this->add_plugin_button();
    }

    /**
     * The action handler for "plugin.all-folder-size".
     */
    public function action_all_folder_size()
    {
        $RCMAIL = rcmail::get_instance();
        $OUTPUT = $RCMAIL->output;

        $humanize = \filter_var(
            rcube_utils::get_input_value('_humanize', rcube_utils::INPUT_POST),
            \FILTER_VALIDATE_BOOLEAN
        );
        $humanize = isset($humanize) ? $humanize : true;

        $sizes = $this->get_all_folder_size($humanize);

        $OUTPUT->command('plugin.callback_all_folder_size', $sizes);
        $OUTPUT->send();
    }

    /**
     * Add a plugin button.
     */
    private function add_plugin_button()
    {
        if ($this->config->get('show_toolbar_button')) {
            $this->add_button([
                'label' => __CLASS__ . '.show_folder_size',
                'title' => __CLASS__ . '.show_folder_size',
                'href' => '#',
                'class' => 'button show-folder-size',
                'onclick' => 'pluginShowFolderSize();',
            ], 'toolbar');
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
     * Get size for all folders.
     *
     * @param bool $humanize format the result for human reading
     *
     * @return array an array in the form of [mailbox_1 => size_1, ...]
     */
    private function get_all_folder_size($humanize = false)
    {
        $RCMAIL = rcmail::get_instance();
        $STORAGE = $RCMAIL->storage;

        // somehow the listed folders are duplicated hence unique()
        $mailboxes = \array_unique($STORAGE->list_folders());
        $sizes = \array_map([$STORAGE, 'folder_size'], $mailboxes);

        if ($humanize) {
            $sizes = \array_map([$RCMAIL, 'show_bytes'], $sizes);
        }

        return \array_combine($mailboxes, $sizes);
    }
}
