<?php 
// every file, we need to include this file to initialize db, session, file autoloading etc 

include_once 'core/init.php';
// var_dump( $_SESSION );
if ( Session::exists( 'success' ) ){
    echo Session::flash('success');    
}

// If cookie exists but not sesssion, then user wants us to remember him
if ( Cookie::exists( Config::get('remember/cookie_name') ) && ! Session::exists( Config::get('session/session_name') ) ) {
    // echo 'User asked us to remembered!';
    
    // now get hash from cookie 
    $hash = Cookie::get( Config::get('remember/cookie_name') );
    // and get hash from db
    $hash_check = DB::getInstance()->get('users_session', ['hash', '=', $hash ]);
    // var_dump($hash_check);
    // If found, then hash matched, then make the user logged in again    
    if ( $hash_check->count() ) {
        echo 'hash matches! Log user in!';
        // echo $hash_check->first()->user_id;
        $user = new User( $hash_check->first()->user_id );
        $user->login();
    }
    
}

$user = new User;
if ( $user->is_logged_in() ) { 
?>
    <p>Hello <a title="View profile" href="profile.php?user=<?php echo $user->data()->username;?>"><?php echo $user->data()->username; ?>!</a> </p>    
    <ul>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="update.php">Update Details</a></li>
        <li><a href="changepassword.php">Change Password</a></li>
    </ul>
 <?php } else { ?> 
    <p>You need to  <a href="login.php">log in</a> or <a href="register.php">Register</a></p>               
<?php }

if ( $user->hasPermission ( 'moderator' ) ) {
    echo 'You are an moderator!';
}

$user_all = DB::getInstance()->query( "SELECT * FROM users" );
print_r( $user_all->results() );

// echo Session::get( Config::get('session/session_name') );


// $update = DB::getInstance()->update('users', 3, ['password'=>'newpassword', 'salt'=>'salt', 'name' => 'Adury Afrose']);

// if ( $update ) {
    // echo 'Updated !';
// }

// $user = DB::getInstance()->query("SELECT * FROM users");

// if ( ! $user->error() ) {
//     echo 'OKay!';
// } else {
//     'No User';
// }


// $user = DB::getInstance()->action('SELECT *', 'users', ['username', '=', 'hasan']);
// $users = DB::getInstance()->get('users', ['username', '=', 'hasan'])->results();
// print_r($user);
// $users = DB::getInstance()->query("SELECT * FROM users")->results();
// foreach($users as $user) {
//     echo $user->username . " \n";
// }

// $user = DB::getInstance()->get('users', ['username', '=', 'hasan']);
// echo $user->results()[0]->username;
// $user = DB::getInstance()->get('users', ['username', '=', 'hasan']);
// echo $user->first()->username;

// DB::getInstance()->insert('users', [
//     'username' => 'sajib',
//     'password' => 'password',
//     'name' => 'Hasan Samaun',
// ]);
    
// var_dump( Config::get('mysql/password') );

// DB::getInstance();
// DB::getInstance();
// DB::getInstance();
// DB::getInstance();
