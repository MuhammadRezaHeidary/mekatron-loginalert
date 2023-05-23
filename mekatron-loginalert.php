<?php

/*
Plugin Name: Mekatron Login Alert
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Muhammmad Reza Heidary
Author URI: http://URI_Of_The_Plugin_Author
License: MIT
*/


include_once(plugin_dir_path(__FILE__).'libs/notificator-telegram-bot.php');


add_action('wp_login_failed', function ($username, $error) {
    telegram_notificator_send_message('Login Failed for '.$username.PHP_EOL.$error->get_error_message ());
}, 10, 2);

add_action('wp_login', function ($user_login, $user ) {
    telegram_notificator_send_message('نام کاربری: '.$user_login.PHP_EOL.'نام: '.$user->first_name.' '.PHP_EOL.'فامیل: '.$user->last_name.PHP_EOL.'توضیحات: '.$user->description);
}, 10, 2);


add_action('wp_head', function () {
    echo "<!-- This is Header -->".PHP_EOL;
}, 999);

add_action('wp_footer', function () {
    echo "<!-- This is Footer -->".PHP_EOL;
}, 9999);