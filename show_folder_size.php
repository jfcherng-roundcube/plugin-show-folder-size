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

        $this->loadPluginConfig();
        $this->exposePluginConfig();

        $this->add_texts('localization/', false);
        $this->register_action('plugin.show_folder_size.get', [$this, 'actionGet']);

        $this->addPluginAssets();
        $this->addPluginButtons();
    }

    public function exposePluginConfig(): void
    {
        $rcmail = rcmail::get_instance();

        $rcmail->output->set_env(
            'show_folder_size.config',
            [
                'auto_show_folder_size' => $this->config->get('auto_show_folder_size'),
            ]
        );
    }

    /**
     * The action handler for "plugin.show_folder_size.get".
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
     * Determine can we stop the plugin initialization.
     */
    private function can_stop_init(): bool
    {
        $action = rcube_utils::get_input_value('_action', rcube_utils::INPUT_GET) ?? '';
        $isApiCall = \stripos($action, 'plugin.') === 0;

        return $action !== '' && !$isApiCall;
    }

    /**
     * Add plugin assets.
     */
    private function addPluginAssets(): void
    {
        $this->include_stylesheet($this->local_skin_path() . '/main.css');
        $this->include_script('assets/main.min.js');
    }

    /**
     * Add plugin buttons.
     */
    private function addPluginButtons(): void
    {
        if ($this->config->get('show_mailboxoptions_button')) {
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

        if ($this->config->get('show_toolbar_button')) {
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
     * Load plugin configuration.
     */
    private function loadPluginConfig(): void
    {
        $rcmail = rcmail::get_instance();

        $this->load_config('config.inc.php.dist');
        $this->load_config('config.inc.php');

        $this->config = $rcmail->config;
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
