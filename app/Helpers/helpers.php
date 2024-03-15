<?php

function extractCode($string) {
    // Use a regular expression to match the desired pattern
    $pattern = '/^([A-Z]{3}\d{3})/';

    if (preg_match($pattern, $string, $matches)) {
        // If the string matches the pattern, return the captured code
        return $matches[1];
    } else {
        // If the string doesn't match, return null or handle the error as needed
        return null;
    }
}
