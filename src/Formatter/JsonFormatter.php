<?php

namespace BrighteCapital\Logger\Formatter;

use BrighteCapital\Logger\Utilities\Hash;

/**
 * Encodes whatever record data is passed to it as json
 *
 * This can be useful to log to databases or remote APIs
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class JsonFormatter extends \Monolog\Formatter\JsonFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        unset($record['datetime']);
        $record['@timestamp'] = date('Y-m-d\TH:i:s\Z');

        $context = &$record['context'];
        $context = Hash::flatten(['c' => $context], '>');
        $context = array_filter($context, function ($data, $key) {
            return substr_count($key, '>') < 4;
        }, ARRAY_FILTER_USE_BOTH);

        $context = array_map(function ($data) {
            return self::stringify($data);
        }, $context);

        return $this->toJson($this->normalize($record), true) . ($this->appendNewline ? "\n" : '');
    }

    /**
     * Stringify objects to avoid getting converted to json object. Its to avoid
     * elastic search to go over distinct filed limit.
     * @param $data
     * @param array $context
     * @return string|true
     */
    protected function stringify($data, array $context = [])
    {
        if (is_string($data)) {
            return $data;
        }

        return print_r($data, true);
    }
}
