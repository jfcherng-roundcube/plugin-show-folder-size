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
        'get-folder-size' => 'getFolderSizeAction',
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

        $this->add_texts($this->localizationDir, true);

        // only shown in the main "mail" page
        if ($this->rcmail->action === '') {
            $this->include_stylesheet("{$this->skinPath}/main.css");
            $this->include_script('assets/main.min.js');
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
     * Handler for plugin's "get-folder-size" action.
     */
    public function getFolderSizeAction(): void
    {
        /** @var rcmail_output_json */
        $output = $this->rcmail->output;
        $storage = $this->rcmail->get_storage();

        // sanitize: _callback
        $callback = \filter_input(\INPUT_POST, '_callback');

        // sanitize: _folders
        $folders = \filter_input(\INPUT_POST, '_folders') ?? '__ALL__';
        $folders = $folders === '__ALL__' ? $storage->list_folders() : (array) $folders;
        $folders = \array_unique($folders);

        // sanitize: _humanize
        $humanize = \filter_input(\INPUT_POST, '_humanize', \FILTER_VALIDATE_BOOLEAN) ?? true;

        $sizes = $this->getFolderSize($folders, $humanize);

        $callback && $output->command($callback, $sizes);
        $output->send();
    }

    /**
     * Add plugin buttons.
     */
    private function addPluginButtons(): void
    {
        $this->add_buttons_mailboxoptions([
            [
                'type' => 'link-menuitem',
                'label' => "{$this->ID}.show_folder_size",
                'class' => 'show-folder-size active',
                'href' => '#',
                'command' => "plugin.{$this->ID}.show-data",
            ],
        ]);
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
        $storage = $this->rcmail->get_storage();

        $folders = \array_unique($folders);
        $sizes = \array_map([$storage, 'folder_size'], $folders);

        if ($humanize) {
            $sizes = \array_map([$this->rcmail, 'show_bytes'], $sizes);
        }

        return \array_combine($folders, $sizes);
    }
}
