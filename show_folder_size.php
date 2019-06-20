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
        if (in_array($action, ['get'])) {
            return;
        }

        $this->load_plugin_config();

        $this->add_texts('localization/', true);

        $this->include_stylesheet($this->local_skin_path() . '/' . __CLASS__ . '.css');
        $this->include_script('js/main.min.js');

        if ($this->config->get('auto_show_folder_size')) {
            $this->include_script('js/exec.min.js');
        }

        $this->add_plugin_button();
    }

    /**
     * Add a plugin button.
     *
     * @return self
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

        return $this;
    }

    /**
     * Load plugin configuration.
     */
    private function load_plugin_config()
    {
        $rcmail = rcmail::get_instance();

        $this->load_config('config.inc.php.dist');
        $this->load_config('config.inc.php');

        $this->config = $rcmail->config;
    }
}
