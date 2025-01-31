<?php 
class Token {
    public static function generate() {
        return Session::put( Config::get('session/token_name') , md5( uniqid() ) );
    }
    
    // check session token and form token same or not
    public static function check( $token ) {
        $token_name = Config::get('session/token_name');
        
        if ( Session::exists( $token_name ) && $token == Session::get( $token_name ) ) {
            // delete session token
            // return ture
            Session::delete( $token_name );
            return true;
        }
    }
    
}
