<?php

namespace Ice\ExternalUserBundle\Util;

class String
{
    /**
     * Returns a string containing the initial letters of each word (each of which must separated by a space.)
     *
     * E.g. "This String" would return "TS"
     *
     * @param string $string
     * @return string
     */
    public static function getInitials($string)
    {
        $initials = array();

        $stringParts = explode(" ", $string);

        foreach ($stringParts as $part) {
            $initials[] = substr($part, 0, 1);
        }

        return implode("", $initials);
    }
}