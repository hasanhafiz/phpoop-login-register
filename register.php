<?php
include_once 'core/init.php';

// print_r($_SESSION);
// echo 'input get <br/>';
// echo Input::get('token');
// var_dump( Token::check( Input::get('token') ) );

?>
<!DOCTYPE html>
<html class="h-full bg-white" lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    * {
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont,
        'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans',
        'Helvetica Neue', sans-serif;
    }
  </style>

  <title>Create A New Account</title>
</head>

<body class="h-full bg-slate-100">
  <div class="flex flex-col justify-center min-h-full py-12 sm:px-6 lg:px-8">

    <!-- Message  -->

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <?php    
      // echo Input::get('name');
      ?>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-center text-gray-900">Create A New Account
      </h2>
    </div>


    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
      <div class="px-6 py-12 bg-white shadow sm:rounded-lg sm:px-12">
        <ul>
          <?php
          
          // echo Hash::salt() . PHP_EOL;
          // echo Hash::unique();
          // echo Hash::make()
          
          
          if (Input::exists()) {
            if (Token::check(Input::get('token'))) {
                  // validate form field like laravel
                  $validate = new Validate;
                  $validation = $validate->check(
                    $_POST,
                    [
                      'username' => [
                        'required' => true,
                        'min' => 2,
                        'max' => 20,
                        'unique' => 'users'
                      ],
                      'fullname' => [
                        'required' => true,
                      ],
                      'email' => [
                        'required' => true,
                        'unique' => 'users'
                      ],
                      'password' => [
                        'required' => true,
                        'min' => 6,
                      ],
                      'password_again' => [
                        'required' => true,
                        'matches' => 'password',
                      ]
                    ]
                  );
                        
              // echo 'I have not been run!';
              
              // validate form field like laravel
              $validate = new Validate;
              if ($validation->passed()) {
                // registered user
                $user = new User();
                $salt = Hash::salt();                
                try {
                  $user->create([
                    'username' => Input::get( 'username' ),
                    'fullname' => Input::get( 'fullname' ),
                    'email' => Input::get( 'email' ),                  
                    'salt' => $salt,                  
                    'password' => Hash::make( Input::get('password') , $salt ),                 
                    'group' => 1,                 
                    'joined' => date('Y-m-d H:i:s'),                 
                  ]);                  
                } catch ( Exception $exception ){
                  die($exception->getMessage());
                }
                Session::flash('success', 'You registered successfully!');
                // header("Location: index.php");   
                Redirect::to( location: 'index.php');             
                // Redirect::to( location: 404);             
                // if ( Session::exists( 'success' ) ) {                  
                // }                
              } else {
                // print_r( $validation->errors() );
                // output error
                foreach ($validation->errors() as $error) {
                  echo "<li>{$error}</li>";
                }
              }
            }
          }
          ?>
        </ul>

        <form novalidate class="space-y-6" action="#" method="POST">
          <div>
            <label for="username" class="block text-sm font-medium leading-6 text-gray-900">User Name</label>
            <div class="mt-2"><input id="username" name="username" type="text" value="<?php echo Input::get('username'); ?>" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>

          <div>
            <label for="fullname" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
            <div class="mt-2"><input id="fullname" name="fullname" type="text" value="<?php echo Input::get('fullname'); ?>" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
              address</label>
            <div class="mt-2"><input id="email" name="email" type="email" value="<?php echo Input::get('email'); ?>" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>
          
          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
            <div class="mt-2"><input id="password" name="password" type="password" value="<?php echo Input::get('password'); ?>" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>

          <div>
            <label for="password_again" class="block text-sm font-medium leading-6 text-gray-900">Password Again</label>
            <div class="mt-2"><input id="password_again" name="password_again" type="password" value="<?php echo Input::get('password_again'); ?>" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>
          
          <div>
            <input type="hidden" id="token" name="token" value="<?php echo Token::generate(); ?>">
            <button type="submit" class="flex w-full justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Register</button>
          </div>
        </form>
      </div>
      
      <p class="mt-10 text-sm text-center text-gray-500"> Already a customer? <a href="login.php" class="font-semibold leading-6 text-emerald-600 hover:text-emerald-500">Sign-in</a></p>
    </div>
  </div>
</body>

</html>