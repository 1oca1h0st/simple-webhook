<?php
function sw_settings_page() {
    ?>
    <div class="wrap">
        <h1>Simple Webhook 设置</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sw_settings');
            do_settings_sections('sw-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function sw_register_settings() {
    register_setting('sw_settings', 'sw_api_url');
    add_settings_section('sw_api_url_section', 'API URL 设置', 'sw_api_url_section_callback', 'sw-settings');
    add_settings_field('sw_api_url_field', 'API URL', 'sw_api_url_field_callback', 'sw-settings', 'sw_api_url_section');
}

function sw_api_url_section_callback() {
    echo '请输入你的API URL：';
}

function sw_api_url_field_callback() {
    $api_url = get_option('sw_api_url');
    echo '<input type="text" id="sw_api_url" name="sw_api_url" value="' . esc_attr($api_url) . '" />';
}

// 将函数绑定到admin_menu钩子
add_action('admin_menu', 'sw_add_settings_page');

function sw_add_settings_page() {
    add_options_page('Simple Webhook 设置', 'Simple Webhook', 'manage_options', 'sw-settings', 'sw_settings_page');
}

// 将函数绑定到admin_init钩子
add_action('admin_init', 'sw_register_settings');
