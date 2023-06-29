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
            $mla_name = wp_get_current_user()->first_name.' '.wp_get_current_user()->last_name;
            $mla_content = $html;
            $mla_is_show = true;
//            do_action('mekatron-loginalert-extra-options', $mla_name, $mla_content, $mla_is_show);
            $mla = [
                'name' => $mla_name,
                'content' => $mla_content,
                'isshow' => $mla_is_show,
            ];
//            do_action('mekatron-loginalert-extra-options', $mla['name'], $mla['content'], $mla['isshow']);
            do_action_ref_array('mekatron-loginalert-extra-options', $mla);

            $mla_some_text = 'Kraken';
            do_action_ref_array('mekatron-loginalert-some-text', [&$mla_some_text]);
            echo 'Alternative Monster: '.$mla_some_text;

            $mla_monsters = new stdClass();
            $mla_monsters->name = 'Kraken';
            do_action_ref_array('mekatron-loginalert-some-object', [$mla_monsters]);
            echo 'Alternative Monster: '.$mla_monsters->name;

        });
});

add_action('mekatron-loginalert-some-object', function ($mla_monsters) {
    ?>
    <hr>
    <p>Monster:
        <?php
        echo $mla_monsters->name;
        $mla_monsters->name = 'Meg';
        ?>
    </p>
    <?php
});


add_action('mekatron-loginalert-some-text', function (&$mla_some_text) {
    ?>
    <hr>
    <p>Monster:
        <?php
            echo $mla_some_text;
            $mla_some_text = 'Godzilla';
        ?>
    </p>
    <?php
});

add_action('mekatron-loginalert-extra-options', function ($name, $content, $is_show) {
    if($is_show) {
        ?>
        <div dir="rtl" class="mekatron-conatiner">
            <hr>
            <h3>یک سری اطلاعات بیهوده که اینجا میزاریم تا بگیم که ما هم هستیم!</h3>
            <p>نویسنده:
                <?php echo $name; ?>
            </p>
            <?php
                $stripped_content = strip_tags($content);
                $word_count = str_word_count($stripped_content);
                // 300 word per minute -> 5 word per second
                define('WORD_READ_TIME_MINUTE', 300);
                $time_read = ceil($word_count/(WORD_READ_TIME_MINUTE/60));
            ?>
            <p>تعداد کلمات:
                <?php echo $word_count.' '; ?>کلمه
            </p>
            <p>زمان لازم برای خواندن این محتوا:
                <?php echo $time_read.' '; ?>ثانیه
            </p>
        </div>
        <?php
    }
}, 99999, 3);

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

/*
function mekatron_custom_head() {
    $my_action = current_action();
    echo '<!-- Mekatron => ' . $my_action . '-->' . PHP_EOL;
    if(doing_action('admin_head')) {
        echo '<!-- Welcome to Admin -->' . PHP_EOL;
    }

    var_dump(has_action('wp_footer', 'the_block_template_skip_link'));
    var_dump(has_action('wp_footer', 'wp_admin_bar_render'));
    var_dump(has_action('wp_footer', 'some_function'));
    var_dump(has_action('wp_footer'));

    var_dump(did_action('template_redirect'));
    var_dump(did_action('registered_post_type'));
    var_dump(did_action('plugin_loaded'));

    if(has_action('wp_footer', 'the_block_template_skip_link')) {
//    if(has_action('wp_footer', 'some_function')) {
//    if(has_action('wp_footer')) {
        echo '<!-- has action -->' . PHP_EOL;
    }
    else {
        echo '<!-- no action -->' . PHP_EOL;
    }
}
add_action('wp_head', 'mekatron_custom_head');
add_action('admin_head', 'mekatron_custom_head');
*/

// remove action wp_head with priority 10
/*remove_action('wp_head','mekatron_custom_head');*/

// remove action wp_head with priority 15
/*remove_action('wp_head','mekatron_custom_head',15);*/

// remove all actions of wp_head
/*remove_all_actions('wp_head');*/

// remove all actions of wp_head with priority 10
/*remove_all_actions('wp_head', 10);*/

/*
if(isset($_GET['show_filters'])) {
    global $wp_filter;
    print_r($wp_filter);
    exit;
}

if(isset($_GET['show_actions'])) {
    global $wp_actions;
    print_r($wp_actions);
    exit;
}
*/