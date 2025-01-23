<?php 

include_once 'core/init.php';

if ( $username = Input::get('user')  ) {
    // check user exists or not
    $user = new User( $username );
    if ( $user->exists() ) {
        $data = $user->data();
        echo "<h3>{$data->username}</h3>";
        echo "<p>Full Name: {$data->fullname}</p>";
        echo "<p>Email: {$data->email}</p>";
    } else {
        Redirect::to(404);
    }
} else {
    Redirect::to('index.php');
}


