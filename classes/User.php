<?php 

class User {
    private $_db = null,
            $_data = null,
            $_session_name = null,
            $_cookie_name = null,
            $_is_logged_in = false;
    
    /**
     * User could be an user id or email
     *
     */
    public function __construct( $user = null )
    {
        $this->_db = DB::getInstance();
        $this->_session_name = Config::get('session/session_name');
        $this->_cookie_name = Config::get('remember/cookie_name');
        
        if ( ! $user ) {
            // load user from session data.
            if ( Session::get( $this->_session_name ) ) {
                
                // we usually store user_id in the session 
                $user = Session::get( $this->_session_name );
                
                // load the user if session data exists
                if ( $this->find( $user ) ) {
                    $this->_is_logged_in = true;
                } else {
                    // process for logout
                    $this->_is_logged_in = false;
                }
                
            }
        } else {
            // if user is defined
            $this->find( $user );
        }
        // var_dump( $user );
        
        // echo '<pre>';
        // print_r( $this );
        // echo '</pre>';
    }
    
    public function create( $fields = []): void
    {
        if ( ! $this->_db->insert('users', $fields) ) {
            throw new Exception( "There was a problem creating an account!" );
        }
    }
    
    public function update( $fields = [], $id = null): void
    {
        if ( ! $id &&  $this->is_logged_in() ) {
            $id = $this->data()->id;
        }
        if ( ! $this->_db->update('users', $id, $fields) ) {
            throw new Exception( "There was a problem updating an account!" );
        }
    }
        
    /**
     *  load user by id or email
     *
     * @param string $user either id or email
     * @return 
     */
    public function find( $user ){
        if ( $user ) {
            $field = is_numeric( $user ) ? 'id' : 'username';
            $data = $this->_db->get('users', [$field, '=', $user]);
            
			echo '<pre>';
			print_r($data);
			echo '</pre>';			
			
			
		if ( $data->count() ) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    
    public function data() {
        return $this->_data;
    }
    
    // $user_found = DB::getInstance()->query("SELECT * FROM users WHERE email = ? AND password = ?", [Input::get('email'), Input::get('password')]);        
    // Class Object ( [id] => 5 [username] => saif [password] => 9bc6f3df948901299b56d4b1c0d996f3907f2571d043490b07672efb2a1de766 [salt] => db5da1b71cdd4fe0ec89b24c [name] => [joined] => 2024-07-08 14:39:52 [group] => 1 [email] => saif@gmail.com ) 
    public function login(string $email = null, string $password = null, $remember = false)
    {
        
        if ( ! $email & ! $password && $this->exists() ) {
            // user exists, create a user session
            Session::put( $this->_session_name, $this->data()->id );            
        } else {                
            $user = $this->find($email);
            if ( $user ) {
                if ( $this->data()->password == Hash::make( $password, $this->data()->salt ) ) {
                    Session::put( $this->_session_name , $this->data()->id );
                    
                    if ( $remember ) {
                        // generate uniq hash and check it is exists on users_session table or not
                        
                        $hash = Hash::unique(); 
                        $hash_check = $this->_db->get( 'users_session', ['user_id', '=', $this->data()->id] );
                        
                        // var_dump($hash_check);
                        
                        // if record not found, then insert it
                        if ( ! $hash_check->count() ) {
                            $this->_db->insert( 'users_session', [
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ] );
                        } else {
                            $hash = $hash_check->first()->hash;
                        }
                        Cookie::put( $this->_cookie_name, $hash, Config::get('remember/cookie_expiry') );
                    } // end remember                
                    return true;
                }
            }
        }
        return false;
    }       
    
    public function is_logged_in() {
        return $this->_is_logged_in;
    }
    
    public function logout()
    {
        // remove cookie form database
        $this->_db->delete( 'users_session', ['user_id', '=', $this->data()->id ] );
        
        Session::delete( $this->_session_name );
        Cookie::delete( $this->_cookie_name );
    }
    
    public function exists() {
        return ( !empty( $this->_data ) ) ? true : false;
    }
    
    public function hasPermission( $key ) {
        // print_r($this->data());
        $group = $this->_db->get('groups', ['id', '=', $this->data()->group]);
        if ( $group->count() ) {
            $permissions = json_decode( $group->first()->permissions, true ); // conver json data to array
            if ( $permissions[$key] ) {
                return true;
            }
        }
        return false;
        // print_r( $group->first() );
    }
    
}