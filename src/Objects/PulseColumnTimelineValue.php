<?php

/**
 * @copyright 2017 Vladimir Jimenez
 * @license   https://github.com/allejo/PhpPulse/blob/master/LICENSE.md MIT
 */

namespace allejo\DaPulse\Objects;

/**
 * Class PulseColumnTextValue
 *
 * @package allejo\DaPulse\Objects
 * @since   0.2.1
 */
class PulseColumnTimelineValue extends PulseColumnValue
{
    /**
     * Get a timeline column's content
     *
     * @api
     *
     * @since  0.2.1
     *
     * @return \DateTime[]|null The timeline's begin and end dates
     *
     *     array(
     *       'from' => \DateTime
     *       'to'   => \DateTime
     *     )
     */
    public function getValue ()
    {
        return parent::getValue();
    }

    /**
     * Update the values of a timeline column
     *
     * @api
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @since 0.3.0 \InvalidArgumentException is now thrown
     * @since 0.2.1
     *
     * @throws \InvalidArgumentException if $from or $to are not \DateTime instances
     */
    public function updateValue ($from, $to)
    {
        if (!($from instanceof \DateTime) || !($to instanceof \DateTime))
        {
            throw new \InvalidArgumentException('$from and $to are expected to be \\DateTime instances');
        }

        $url        = sprintf("%s/%d/columns/%s/timeline.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = [
            "pulse_id" => $this->pulse_id,
            "from"     => $from->format('Y-m-d'),
            "to"       => $to->format('Y-m-d')
        ];

        $result = self::sendPut($url, $postParams);
        $this->setValue($result);
    }

    protected function setValue ($response)
    {
        $this->column_value = [
            'from' => new \DateTime($response["value"]["from"]),
            'to'   => new \DateTime($response["value"]["to"])
        ];
    }
}