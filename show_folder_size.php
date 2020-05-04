<?php

declare(strict_types=1);

include __DIR__ . '/lib/vendor/autoload.php';

use Jfcherng\Roundcube\Plugin\ShowFolderSize\AbstractRoundcubePlugin;

final class show_folder_size extends AbstractRoundcubePlugin
{
    /**
     * {@inheritdoc}
     */
    public $task = 'mail';

    /**
     * {@inheritdoc}
     */
    public $actions = [
        'get' => 'actionGet',
    ];

    /**
     * {@inheritdoc}
     */
    public $hooks = [];

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $rcmail = rcmail::get_instance();

        $this->exposePluginConfigurations(['auto_show_folder_size']);
        $this->include_stylesheet("{$this->skinPath}/main.css");
        $this->include_script('assets/main.min.js');

        if ($rcmail->action === '' || $rcmail->action === 'show') {
            $this->addPluginButtons();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultPluginPreferences(): array
    {
        return [];
    }

    /**
     * Handler for plugin's "get" action.
     */
    public function actionGet(): void
    {
        $rcmail = rcmail::get_instance();
        /** @var rcmail_output_json */
        $output = $rcmail->output;
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

        $callback && $output->command($callback, $sizes);
        $output->send();
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
