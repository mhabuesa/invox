<?php

if (!function_exists('formatPermission')) {
    function formatPermission($text)
    {
        return ucwords(str_replace('_', ' ', strtolower($text)));
    }
}
