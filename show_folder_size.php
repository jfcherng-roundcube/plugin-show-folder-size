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

        // sanitize: _callback
        $callback = \filter_input(\INPUT_POST, '_callback');

        $sizes = $this->getFolderSizes();

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
     * Get size for all folders.
     *
     * @return array an array in the form of [
     *               folder_1 => [size_1, size_1(humanized), cumulative_1, cumulative_1(humanized)],
     *               ... ]
     */
    private function getFolderSizes(): array
    {
        static $folderDelimiter = '/';

        $storage = $this->rcmail->get_storage();
        $folders = \array_unique($storage->list_folders());

        $foldersSorted = $folders;
        \usort($foldersSorted, function (string $folder1, string $folder2): int {
            return \strlen($folder1) <=> \strlen($folder2);
        });

        /** @var array<string,string[]> $children folder => its child folders */
        $children = [];
        for ($i = \count($foldersSorted) - 1; $i >= 0; --$i) {
            $folderChild = $foldersSorted[$i];

            for ($j = $i; $j >= 0; --$j) {
                $folderParent = $foldersSorted[$j];

                // test if $folderChild is a child of $folderParent
                if (0 === \strpos("{$folderChild}{$folderDelimiter}", "{$folderParent}{$folderDelimiter}")) {
                    $children[$folderParent] = $children[$folderParent] ?? [];
                    $children[$folderParent][] = $folderChild;
                }
            }
        }

        /** @var array<string,int> $rawSizes the non-cumulative folder sizes */
        $rawSizes = [];
        foreach ($folders as $folder) {
            $rawSizes[$folder] = $storage->folder_size($folder);
        }

        /** @var array<string,int> $sumSizes the cumulative folder sizes */
        $sumSizes = [];
        foreach ($folders as $folder) {
            $sumSizes[$folder] = 0;

            foreach ($children[$folder] as $child) {
                $sumSizes[$folder] += $rawSizes[$child];
            }
        }

        $ret = [];
        foreach ($folders as $folder) {
            $ret[$folder] = [
                $rawSizes[$folder],
                $this->rcmail->show_bytes($rawSizes[$folder]),
                $sumSizes[$folder],
                $this->rcmail->show_bytes($sumSizes[$folder]),
            ];
        }

        return $ret;
    }
}
