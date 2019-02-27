<?php

/**
 * @copyright 2019 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

use allejo\DaPulse\PulseTag;

class PulseColumnTagValue extends PulseColumnValue
{
    /**
     * @return PulseTag[]
     */
    public function getValue ()
    {
        return parent::getValue();
    }

    /**
     * @param string[] $tags
     */
    public function updateValue (array $tags)
    {
        throw new \Exception('This method has not been implemented yet');
    }

    /**
     * Cast and set the appropriate value for this column
     *
     * @param $response
     */
    protected function setValue ($response)
    {
        if (!isset($response['value']['tag_ids'])) {
            $this->column_value = [];

            return;
        }

        $this->column_value = $response['value']['tag_ids'];

        self::lazyCastAll($this->column_value, 'PulseTag', true);
    }
}
