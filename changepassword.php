<?php
include_once 'core/init.php';

$user = new User; 
if ( ! $user->is_logged_in() ) {
    Redirect::to('index.php');
}
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

  <title>Change password</title>
</head>

<body class="h-full bg-slate-100">
  <div class="flex flex-col justify-center min-h-full py-12 sm:px-6 lg:px-8">

    <!-- Message  -->

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
    </div>
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-2xl font-bold leading-9 tracking-tight text-center text-gray-900">Change your password</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
      <div class="px-6 py-12 bg-white shadow sm:rounded-lg sm:px-12">
        <ul>
          <?php
          
          if (Input::exists()) {            
            if (Token::check(Input::get('token'))) {
                                            
                  // validate form field like laravel
                  $validate = new Validate;
                  $validation = $validate->check(
                    $_POST,
                    [
                      'password_current' => [
                        'required' => true,
                        'min' => 6,
                      ],
                      'password_new' => [
                        'required' => true,
                        'min' => 6,
                      ],
                      'password_new_again' => [
                        'required' => true,
                        'min' => 6,
                        'matches' => 'password_new',
                      ],
                    ]
                  );                        
              // echo 'I have not been run!';              
              // validate form field like laravel
              $validate = new Validate;
              if ($validation->passed()) {
                // registered user               
                try {
                    // first match current password is matches with existing password
                    if ( Hash::make( Input::get('password_current'), $user->data()->salt ) == $user->data()->password ) {
                        // if match, the proceed for next
                        $salt = Hash::salt();
                        $user->update([
                            'password' => Hash::make( Input::get('password_new'), $salt ),
                            'salt' => $salt
                        ], $user->data()->id);
                        
                        Session::flash('success', 'Password has been changed successfully!');
                        Redirect::to('index.php');
                        
                    } else {
                        // current password is wrong
                        echo 'Your current password is wrong!';
                        // $validate->addError('Current password is wrong!');
                    }
                    
                    
                //   $user->update([
                //     'fullname' => Input::get( 'fullname' ),                                   
                //   ], $user->data()->id );                  
                } catch ( Exception $exception ){
                  die($exception->getMessage());
                }
                // Session::flash('success', 'You details have been updated!');
                // Redirect::to( location: 'index.php');                         
              } else {
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
            <label for="password_current" class="block text-sm font-medium leading-6 text-gray-900">Current Password</label>
            <div class="mt-2"><input id="password_current" name="password_current" type="text" value="" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>
                    
          <div>
            <label for="password_new" class="block text-sm font-medium leading-6 text-gray-900">New Password</label>
            <div class="mt-2"><input id="password_new" name="password_new" type="text" value="" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>
                    
          <div>
            <label for="password_new_again" class="block text-sm font-medium leading-6 text-gray-900">New Password Again</label>
            <div class="mt-2"><input id="password_new_again" name="password_new_again" type="text" value="" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 p-2" />
            </div>
          </div>
                    
          <div>
          <input type="hidden" id="token" name="token" value="<?php echo Token::generate(); ?>">
            <button type="submit" class="flex w-full justify-center rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Change</button>
          </div>
        </form>
      </div>
      
      <p class="mt-10 text-sm text-center text-gray-500"> Already a customer? <a href="login.php" class="font-semibold leading-6 text-emerald-600 hover:text-emerald-500">Sign-in</a></p>
    </div>
  </div>
</body>

</html>