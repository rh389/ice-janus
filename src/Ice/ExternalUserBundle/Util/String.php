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
        // remove any non-alpha characters
        $string = preg_replace('/[^\p{L} ]/u', '', $string);

        $initials = array();

        $stringParts = mb_split("\s", $string);

        foreach ($stringParts as $part) {
            $initials[] = mb_substr($part, 0, 1, 'UTF-8');
        }

        return implode("", $initials);
    }
}