<?php
class Sanitize
{
    public static function url($url)
    {
        $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
        return $url;
    }
}
