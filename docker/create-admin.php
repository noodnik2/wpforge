<?php
require_once '/var/www/html/wp-load.php';

//
// install with e.g.
//
// cp docker/create-admin.php docker/volumes/wordpress
//

$username = 'admin';
$password = 'admin';
$email = 'admin@wpforge.local';

error_log(">>> create-admin: creating admin user if it doesn't exist already: " . $username);

$message = '';

if (!username_exists($username) && !email_exists($email)) {
    $user_id = wp_create_user($username, $password, $email);
    $user = new WP_User($user_id);
    $user->set_role('administrator');
    $message = ">>> create-admin: created admin user: {$username}";
} else {
    $message = ">>> create-admin: did not create admin user — username or email already exists: {$username}";
}

error_log($message);

// Output to browser
echo "<pre>{$message}</pre>";

// end of script
