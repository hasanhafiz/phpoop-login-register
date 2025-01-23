<?php 

class Cookie {
    public static function exists(string $name)
    {
        return  isset($_COOKIE[$name]) ? true : false;
    }
    public static function put(string $name, string $value, $expiry)
    {
        if ( setcookie( $name, $value, time() + $expiry , '/' ) ) {
            return true;
        }
        return false;
    }
    public static function get(string $name)
    {
        return $_COOKIE[$name];
    }
    public static function delete(string $name)
    {
        self::put( $name, '', time() - 1 );
    }
}