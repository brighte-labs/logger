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
    public $whiteListedFields;

    public function __construct(
        $batchMode = \Monolog\Formatter\JsonFormatter::BATCH_MODE_JSON,
        $appendNewline = true,
        $whiteListedFields = []
    )
    {
        \Monolog\Formatter\JsonFormatter::__construct($batchMode, $appendNewline);
        $this->whiteListedFields = $whiteListedFields;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        unset($record['datetime']);
        $record['@timestamp'] = date('Y-m-d\TH:i:s\Z');

        $context = $record['context'];
        $newContext = [];

        foreach ($this->whiteListedFields as $field) {
            if ($value = Hash::get($context, $field)) {
                $newContext[$field] = $this->stringify($value);
            }
        }

        $newContext['dataArchive'] = $this->stringify($context);
        $record['context'] = $newContext;

        return $this->toJson($this->normalize($record), true) . ($this->appendNewline ? "\n" : '');
    }

    /**
     * Stringify objects to avoid getting converted to json object. Its to avoid
     * elastic search to go over distinct filed limit.
     * @param $data
     * @return string|true
     */
    protected function stringify($data)
    {
        if (is_string($data)) {
            return $data;
        }

        return print_r($data, true);
    }
}
