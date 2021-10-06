<?php

declare(strict_types=1);

namespace Jfcherng\Roundcube\Plugin\ShowFolderSize;

use rcube;

final class RoundcubeHelper
{
    /**
     * Get the site url.
     */
    public static function getSiteUrl(): string
    {
        static $url;

        if (isset($url)) {
            return $url;
        }

        $scheme = filter_var($_SERVER['HTTPS'] ?? 'off', \FILTER_VALIDATE_BOOLEAN) ? 'https' : 'http';
        $requestedUrl = "{$scheme}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $parts = parse_url($requestedUrl);

        // remove potential trailing index.php
        $parts['path'] = preg_replace('/\/index\.php$/iuS', '/', $parts['path']);
        unset($parts['query'], $parts['fragment']);

        return $url = self::unparseUrl($parts);
    }

    /**
     * Get the lowercase base skin name for the current skin.
     *
     * @return string the base skin name
     */
    public static function getBaseSkinName(): string
    {
        static $base_skins = ['classic', 'larry', 'elastic'];

        $rcube = rcube::get_instance();

        // information about current skin and extended skins (if any)
        $skins = (array) $rcube->output->skins;

        foreach ($base_skins as $base_skin) {
            if (isset($skins[$base_skin])) {
                return $base_skin;
            }
        }

        return $skins[0] ?? '';
    }

    /**
     * Assemble URL parts back to string URL.
     *
     * @param array $parts the parts
     */
    public static function unparseUrl(array $parts): string
    {
        return
            (isset($parts['scheme']) ? "{$parts['scheme']}://" : '') .
            ($parts['user'] ?? '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            ($parts['host'] ?? '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            ($parts['path'] ?? '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
}
