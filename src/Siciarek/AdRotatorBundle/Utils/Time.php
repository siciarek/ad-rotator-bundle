<?php

namespace Siciarek\AdRotatorBundle\Utils;

class Time
{
    public static function getDate($interval = 0, $now = null) {
        $interval = intval($interval);
        $param = sprintf('P%dD', abs($interval));
        $date = $now === null ? new \DateTime() : clone($now);

        if ($interval > 0) {
            $date = $date->add(new \DateInterval($param));
        }

        if ($interval < 0) {
            $date = $date->sub(new \DateInterval($param));
        }

        return $date;
    }
}
