<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model\Traits;

trait CacheTrait
{
    /**
     * Caches the result of a method call to a JSON file for a specified duration.
     * If the cached file exists and is still valid (based on cache time), the cached data is returned.
     * Otherwise, the method executes the specified method, caches its result, and returns the data.
     */
    public function cache(array $params, ?string $cacheTime = null): mixed
    {
        $directory = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'database';     
        $file      = "$directory/$params[0].json";
        $cacheTime = $cacheTime ?? config('cache.time', 'database');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (file_exists($file) && (strtotime($cacheTime, filemtime($file)) > time())) {
            return json_decode(file_get_contents($file), true);
        }

        $method = (strpos($params[0], '_') !== false) ? strstr($params[0], '_', true) : $params[0];
        $data   = (!array_key_exists(1, $params)) ? $this->$method() : $this->$method(...$params[1]);
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE));

        return $data;
    }

    /**
     * Clears cached files of a specified type or all types.
     * If a cache key is provided, only that specific cache file is deleted.
     */
    public function clearCache(string $type = 'database', ?string $key = null): void
    {
        $baseDir = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;

        if ($type === 'all') {
            $this->clearCache('database', $key);
            $this->clearCache('templates', $key);
            $this->clearCache('twig', $key);
            $this->clearCache('routes', $key);
            return;
        }

        if (!in_array($type, ['database', 'templates', 'twig', 'routes'], true)) {
            return;
        }

        $directory = $baseDir . $type;

        if ($key !== null) {
            // Delete one file
            $file = $directory . DIRECTORY_SEPARATOR . $key . '.json';
            if (is_file($file)) {
                unlink($file);
            }
            return;
        }

        // Delete all files in the directory
        if (is_dir($directory)) {
            foreach (glob("$directory/*.json") as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
