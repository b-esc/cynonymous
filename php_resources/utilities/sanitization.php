<?php
    /**
     * Sanitizes text input
     *
     * @param string $text The input to sanitize.
     * @return string The sanitized version of the input.
     */
    function sanitize($text){
        $mytext = strip_tags($text);
        $mytext = htmlspecialchars($text);
        return $mytext;
    }