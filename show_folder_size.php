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
        $callback = filter_input(\INPUT_POST, '_callback');

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
     *               folder_1 => [size_1, cumulative_1],
     *               ... ]
     */
    private function getFolderSizes(): array
    {
        $storage = $this->rcmail->get_storage();
        $folders = array_unique($storage->list_folders());

        /** @var array<string,int> $rawSizes the non-cumulative folder sizes */
        $rawSizes = [];
        foreach ($folders as $folder) {
            $rawSizes[$folder] = $storage->folder_size($folder);
        }

        $cumulativeSizes = $this->calcualteCumulativeSizes($rawSizes);

        $ret = [];
        foreach ($folders as $folder) {
            $ret[$folder] = [$rawSizes[$folder], $cumulativeSizes[$folder]];
        }

        return $ret;
    }

    /**
     * Calculates cumulative folder sizes from raw sizes.
     *
     * @param array $rawSizes the raw sizes
     *
     * @return array<string,int> the cumulative folder sizes
     */
    private function calcualteCumulativeSizes(array $rawSizes): array
    {
        /** @var string[] $folders sorted folder names by name length ascending */
        $folders = array_map('strval', array_keys($rawSizes));
        usort($folders, static function (string $folder1, string $folder2): int {
            return \strlen($folder1) <=> \strlen($folder2);
        });

        /** @var array<string,string[]> $children folder => its child folders */
        $children = [];
        for ($i = \count($folders) - 1; $i >= 0; --$i) {
            $child = $folders[$i];

            for ($j = $i; $j >= 0; --$j) {
                $parent = $folders[$j];

                if ($this->isFolderParentAndChild($parent, $child, true)) {
                    $children[$parent] = $children[$parent] ?? [];
                    $children[$parent][] = $child;
                }
            }
        }

        /** @var array<string,int> $sumSizes the cumulative folder sizes */
        $sumSizes = [];
        foreach ($folders as $folder) {
            $sumSizes[$folder] = 0;

            foreach ($children[$folder] as $child) {
                $sumSizes[$folder] += $rawSizes[$child];
            }
        }

        return $sumSizes;
    }

    /**
     * Determine if folders are in a parent-child relationship.
     *
     * @param string $parent     the parent
     * @param string $child      the child
     * @param bool   $sameIsTrue return true if folders are the same
     *
     * @return bool true if parent and child folder, false otherwise
     */
    private function isFolderParentAndChild(string $parent, string $child, bool $sameIsTrue = false): bool
    {
        static $delimiter = '/';

        if ($parent === $child) {
            return $sameIsTrue;
        }

        $parent .= $delimiter;

        return substr($child, 0, \strlen($parent)) === $parent;
    }
}
