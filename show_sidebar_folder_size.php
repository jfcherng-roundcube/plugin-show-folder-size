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
     * {@inheritdoc}
     */
    public function init()
    {
        $rcmail = rcmail::get_instance();
        $this->load_config();
        $this->include_script('js/main.js');
    }
}
