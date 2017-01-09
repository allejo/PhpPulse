<?php

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
     * @return \DateTime[] The timeline's begin and end dates
     *
     *     array(
     *       'from' => \DateTime
     *       'to'   => \DateTime
     *     )
     */
    public function getValue ()
    {
        if (!isset($this->column_value))
        {
            $this->column_value = array(
                'from' => new \DateTime($this->jsonResponse["value"]["from"]),
                'to'   => new \DateTime($this->jsonResponse["value"]["to"])
            );
        }

        return $this->column_value;
    }

    /**
     * Update the values of a timeline column
     *
     * @api
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @since 0.2.1
     */
    public function updateValue ($from, $to)
    {
        $url        = sprintf("%s/%d/columns/%s/timeline.json", self::apiEndpoint(), $this->board_id, $this->column_id);
        $postParams = array(
            "pulse_id" => $this->pulse_id,
            "from"     => $from->format('Y-m-d'),
            "to"       => $to->format('Y-m-d')
        );

        self::sendPut($url, $postParams);

        $this->column_value = array(
            'from' => $from,
            'to'   => $to
        );
    }
}