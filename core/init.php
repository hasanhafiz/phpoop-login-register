<?php 

session_start();

$GLOBALS['config'] = [
    'mysql'     => [
        'host'  => 'localhost',
        'username'  => 'root',
        'password'  => '',
        'db'  => 'phpooplogin_reg_skillfield'
    ],
    'remember'  => [
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800
    ],
    'session'   => [
        'session_name' => 'user',   
        'token_name' => 'token'
    ]
];

// require_once 'classes/Config.php';
// require_once 'classes/Cookie.php';
// require_once 'classes/DB.php';
// require_once 'classes/Hash.php';
// require_once 'classes/Input.php';
// require_once 'classes/Redirect.php';
// require_once 'classes/Session.php';
// require_once 'classes/Token.php';
// require_once 'classes/User.php';
// require_once 'classes/Validation.php';

spl_autoload_register( function( $class ){
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';