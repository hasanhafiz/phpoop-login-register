<?php 

class Input {
    public static function exists( $type = 'post' ) {
        switch( $type ) {
            case 'post':               
                return ( !empty( $_POST ) ) ? true : false;
            break;
            
            case 'get':
                return ( !empty( $_GET ) ) ? true : false;
            break;
            
            default:
                return false;
            break;
        }
        
        
        
    }
    
    public static function get( $item ) {
        // print_r('---_POST---');
        // var_dump($_POST);
        // print_r('---GET---');
        // var_dump($_GET);         
               
        // echo $item;
        // echo '--------';
                       
        if ( isset( $_POST[$item] ) ) {
            return $_POST[$item];
        }
        elseif ( isset( $_GET[$item] ) ) {
            return $_GET[$item];
        }
        return '';
    }
}