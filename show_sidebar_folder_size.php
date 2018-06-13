<?php

class show_sidebar_folder_size extends rcube_plugin
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
     * @var array
     */
    protected $config = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->loadPluginConfig();

        $this->add_texts('localization/', true);

        $this->include_stylesheet($this->local_skin_path() . '/show_sidebar_folder_size.css');
        $this->include_script('js/main.js');

        if ($this->config['auto_show_folder_size']) {
            $this->include_script('js/exec.js');
        }

        $this->addPluginButton();
    }

    /**
     * Add a plugin button.
     *
     * @return self
     */
    protected function addPluginButton()
    {
        if ($this->config['show_toolbar_button']) {
            $this->add_button([
                'label' => 'show_sidebar_folder_size.show_folder_size',
                'title' => 'show_sidebar_folder_size.show_folder_size',
                'href' => '#',
                'class' => 'button show-folder-size',
                'onclick' => 'pluginShowSidebarSize();',
            ], 'toolbar');
        }

        return $this;
    }

    /**
     * Load plugin configuration.
     *
     * @return self
     */
    protected function loadPluginConfig()
    {
        $rc = rcmail::get_instance();

        $userPerf = $this->load_config('config.inc.php')
            ? $rc->config->all()
            : [];

        $this->load_config('config.inc.php.dist');
        $rc->config->merge($userPerf);

        $this->config = $rc->config->all();

        return $this;
    }
}
