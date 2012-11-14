<?php

namespace Ice\ExternalUserBundle\Util;

use FOS\UserBundle\Util\CanonicalizerInterface;

class EmailCanonicalizer implements CanonicalizerInterface
{
    public function canonicalize($string)
    {
        $string = mb_convert_case($string, MB_CASE_LOWER, mb_detect_encoding($string));

        if ('' === $string) {
            $string = null;
        }

        return $string;
    }
}