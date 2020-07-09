<?php

namespace BrighteCapital\Logger\Utilities;

use ArrayAccess;

/**
 * Library of array functions for manipulating and extracting data
 * from arrays or 'sets' of data.
 *
 * `Hash` provides an improved interface, more consistent and
 * predictable set of features over `Set`. While it lacks the spotty
 * support for pseudo Xpath, its more fully featured dot notation provides
 * similar features in a more consistent implementation.
 *
 * @link https://book.cakephp.org/3.0/en/core-libraries/hash.html
 */
class Hash
{
    /**
     * Get a single value specified by $path out of $data.
     * Does not support the full dot notation feature set,
     * but is faster for simple read operations.
     *
     * @param array|\ArrayAccess $data Array of data or object implementing
     *   \ArrayAccess interface to operate on.
     * @param string|array $path The path being searched for. Either a dot
     *   separated string, or an array of path segments.
     * @param mixed $default The return value when the path does not exist
     * @throws \InvalidArgumentException
     * @return mixed The value fetched from the array, or null.
     * @link https://book.cakephp.org/3.0/en/core-libraries/hash.html#Cake\Utility\Hash::get
     */
    public static function get($data, $path, $default = null)
    {
        if (!(is_array($data) || $data instanceof ArrayAccess)) {
            throw new \InvalidArgumentException(
                'Invalid data type, must be an array or \ArrayAccess instance.'
            );
        }

        if (empty($data) || $path === null) {
            return $default;
        }

        if (is_string($path) || is_numeric($path)) {
            $parts = explode('.', $path);
        } else {
            if (!is_array($path)) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid Parameter %s, should be dot separated path or array.',
                    $path
                ));
            }

            $parts = $path;
        }

        switch (count($parts)) {
            case 1:
                return isset($data[$parts[0]]) ? $data[$parts[0]] : $default;
            case 2:
                return isset($data[$parts[0]][$parts[1]]) ? $data[$parts[0]][$parts[1]] : $default;
            case 3:
                return
                    isset($data[$parts[0]][$parts[1]][$parts[2]]) ? $data[$parts[0]][$parts[1]][$parts[2]] : $default;
            default:
                foreach ($parts as $key) {
                    if ((is_array($data) || $data instanceof ArrayAccess) && isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        return $default;
                    }
                }
        }

        return $data;
    }
}
