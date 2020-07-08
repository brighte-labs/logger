<?php

namespace BrighteCapital\Logger\Formatter;

use BrighteCapital\Logger\Utilities\Hash;

/**
 * This formatter will be used for Elastic Search specifically. Will be formatting the message to json
 */
class JsonFormatter extends \Monolog\Formatter\LogstashFormatter
{
    public $whiteListedFields;

    public function __construct($whiteListedFields = [])
    {
        $this->whiteListedFields = $whiteListedFields;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        if ($context = $record['context']) {
            $newContext = [];
            foreach ($this->whiteListedFields as $field) {
                if ($value = Hash::get($context, $field)) {
                    $newContext[$field] = $this->stringify($value);
                }
            }
            $newContext['dataArchive'] = $this->stringify($context);
            $record['context'] = $newContext;
        }

        return $this->toJson($this->normalize($record), true);
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
