<?php

namespace BrighteCapital\Logger\Utilities;

use Cake\Datasource\EntityInterface;

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
     * Collapses a multi-dimensional array into a single dimension, using a delimited array path for
     * each array element's key, i.e. [['Foo' => ['Bar' => 'Far']]] becomes
     * ['0.Foo.Bar' => 'Far'].)
     *
     * @param array $data Array to flatten
     * @param string $separator String used to separate array key elements in a path, defaults to '.'
     * @return array
     * @link https://book.cakephp.org/3.0/en/core-libraries/hash.html#Cake\Utility\Hash::flatten
     */
    public static function flatten(array $data, $separator = '.')
    {
        $result = [];
        $stack = [];
        $path = null;

        reset($data);
        while (!empty($data)) {
            $key = key($data);
            $element = $data[$key];
            unset($data[$key]);

            if (is_array($element) && !empty($element)) {
                if (!empty($data)) {
                    $stack[] = [$data, $path];
                }
                $data = $element;
                reset($data);
                $path .= $key . $separator;
            } else {
                $result[$path . $key] = $element;
            }

            if (empty($data) && !empty($stack)) {
                list($data, $path) = array_pop($stack);
                reset($data);
            }
        }

        return $result;
    }

    /**
     * Merge helper function to reduce duplicated code between merge() and expand().
     *
     * @param array $stack The stack of operations to work with.
     * @param array $return The return value to operate on.
     * @return void
     */
    protected static function merge($stack, &$return)
    {
        while (!empty($stack)) {
            foreach ($stack as $curKey => &$curMerge) {
                foreach ($curMerge[0] as $key => &$val) {
                    $isArray = is_array($curMerge[1]);
                    if (
                        $isArray && !empty($curMerge[1][$key])
                        && (array)$curMerge[1][$key] === $curMerge[1][$key] && (array)$val === $val
                    ) {
                        // Recurse into the current merge data as it is an array.
                        $stack[] = [&$val, &$curMerge[1][$key]];
                    } elseif ((int)$key === $key && $isArray && isset($curMerge[1][$key])) {
                        $curMerge[1][] = $val;
                    } else {
                        $curMerge[1][$key] = $val;
                    }
                }
                unset($stack[$curKey]);
            }
            unset($curMerge);
        }
    }
}
