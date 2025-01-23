<?php 

// require_once   'core/init.php';

class Config {
    public static function get( $path )
    {
        if ( $path ) {
            $config = $GLOBALS['config'];
            $path = explode( '/', $path );
            //print_r( $path );

            // if path found in config file, then all set
            
            foreach( $path as $bit ) {
                // echo $bit . "\n";
                if ( isset($config[$bit]) ) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}