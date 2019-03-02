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
     * Get the tags in this column.
     *
     * @return PulseTag[]
     */
    public function getValue ()
    {
        return parent::getValue();
    }

    /**
     * Override the existing tags in this column with the ones specified in this function call.
     *
     * If you'd like to remove just one tag or add a new one, you will have to do first call `getValue()` and manipulate
     * the array yourself; this is purposely done to prevent an excess of API calls for each minor change.
     *
     * @param PulseTag[]|string[] $tags
     *
     * @throws \InvalidArgumentException
     */
    public function updateValue (array $tags)
    {
        $normalizedTags = [];

        foreach ($tags as $tag)
        {
            if (is_string($tag))
            {
                $normalizedTags[] = $tag;
            }
            elseif ($tag instanceof PulseTag)
            {
                $normalizedTags[] = $tag->getName();
            }
            else
            {
                throw new \InvalidArgumentException('Only strings or PulseTag objects can be set as tags');
            }
        }

        $url        = sprintf("%s/%d/columns/%s/tags.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = [
            "pulse_id" => $this->pulse_id,
            "tags" => implode(',', $normalizedTags),
        ];

        $result = self::sendPut($url, $postParams);
        $this->setValue($result);
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
