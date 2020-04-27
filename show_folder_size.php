<?php

declare(strict_types=1);

include __DIR__ . '/lib/vendor/autoload.php';

use Jfcherng\Roundcube\Plugin\ShowFolderSize\RoundcubePluginTrait;

final class show_folder_size extends rcube_plugin
{
    use RoundcubePluginTrait;

    /**
     * {@inheritdoc}
     */
    public $task = 'mail';

    /**
     * Plugin actions and handlers.
     *
     * @var array<string,string>
     */
    public $actions = [
        'get' => 'actionGet',
    ];

    /**
     * The plugin user preferences.
     *
     * @var array
     */
    private $config = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $rcmail = rcmail::get_instance();

        $this->loadPluginConfigurations();
        $this->exposePluginConfigurations();
        $this->registerPluginActions();

        $this->add_texts('localization/', false);

        if ($rcmail->action === '' || $rcmail->action === 'show') {
            $this->addPluginButtons();
            $this->include_stylesheet($this->local_skin_path() . '/main.css');
            $this->include_script('assets/main.min.js');
        }
    }

    /**
     * Handler for plugin's "get" action.
     */
    public function actionGet(): void
    {
        $rcmail = rcmail::get_instance();
        $storage = $rcmail->get_storage();

        $callback = rcube_utils::get_input_value('_callback', rcube_utils::INPUT_POST);

        // sanitize: _folders
        $folders = rcube_utils::get_input_value('_folders', rcube_utils::INPUT_POST) ?? '__ALL__';
        $folders = $folders === '__ALL__' ? $storage->list_folders() : (array) $folders;

        // sanitize: _humanize
        $humanize = \filter_var(
            rcube_utils::get_input_value('_humanize', rcube_utils::INPUT_POST),
            \FILTER_VALIDATE_BOOLEAN
        ) ?? true;

        $sizes = $this->getFolderSize($folders, $humanize);

        $callback && $rcmail->output->command($callback, $sizes);
        $rcmail->output->send();
    }

    /**
     * Add plugin buttons.
     */
    private function addPluginButtons(): void
    {
        if ($this->config['show_mailboxoptions_button']) {
            $this->add_buttons_mailboxoptions([
                [
                    'type' => 'link-menuitem',
                    'label' => "{$this->ID}.show_folder_size (longer)",
                    'title' => "{$this->ID}.show_folder_size (longer)",
                    'class' => 'show-folder-size active',
                    'href' => '#',
                    'command' => 'plugin.show_folder_size.update-data',
                ],
            ]);
        }

        if ($this->config['show_toolbar_button']) {
            $this->add_buttons_toolbar([
                [
                    'type' => 'link',
                    'label' => "{$this->ID}.show_folder_size",
                    'title' => "{$this->ID}.show_folder_size (longer)",
                    'class' => 'show-folder-size',
                    'href' => '#',
                    'command' => 'plugin.show_folder_size.update-data',
                ],
            ]);
        }
    }

    /**
     * Get size for folders.
     *
     * @param array $folders  folder names
     * @param bool  $humanize format the result for human reading
     *
     * @return int[]|string[] an array in the form of [folder_1 => size_1, ...]
     */
    private function getFolderSize(array $folders, bool $humanize = false): array
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
