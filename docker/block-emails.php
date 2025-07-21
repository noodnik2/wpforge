<?php

//
// install with e.g.
//
// mkdir -p docker/volumes/wordpress/wp-content/mu-plugins
// cp docker/block-emails.php docker/volumes/wordpress/wp-content/mu-plugins
//

if (!function_exists('wp_mail')) {
    error_log(">>> block-emails mu-plugin: defining a dummy wp_mail function");
    function wp_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        error_log(">>> block-emails mu-plugin: email blocked; to: " . print_r($to, true));
        return true; // Pretend mail sent
    }
}

error_log(">>> block-emails mu-plugin: loaded");

// end of script
