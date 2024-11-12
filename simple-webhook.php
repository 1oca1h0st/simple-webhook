<?php
/*
Plugin Name: Simple Webhook
Plugin URI: https://blog.mrtblogs.net/simple-webhook
Description: 当发布新文章时，向指定URL发送JSON数据。
Version: 1.0
Author: loca1h0st
Author URI: https://blog.mrtblogs.net
License: GPL2
*/

// 包含设置页面文件
require_once plugin_dir_path(__FILE__) . 'settings.php';

// 插件激活时的操作
function sw_activate() {
    // 可以在这里添加激活时的操作
}
register_activation_hook( __FILE__, 'sw_activate' );

// 插件停用时的操作
function sw_deactivate() {
    // 可以在这里添加停用时的操作
}
register_deactivation_hook( __FILE__, 'sw_deactivate' );

// 监听文章发布事件
function sw_send_notification($post_id) {
    // 检查是否是自动保存或修订版本
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // 获取文章的基本信息
    $post = get_post($post_id);
    $post_data = array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
        'author' => $post->post_author,
        'url' => get_permalink($post_id),
    );

    // 获取用户在设置页面中输入的API URL
    $api_url = get_option('sw_api_url');

    // 发送JSON请求
    $response = wp_remote_post($api_url, array(
        'headers' => array('Content-Type' => 'application/json'),
        'body' => json_encode($post_data),
    ));


    // 检查请求是否成功
    if (is_wp_error($response)) {
        // 处理错误
        error_log($response->get_error_message());
    }
}

// 将函数绑定到publish_post钩子
add_action('publish_post', 'sw_send_notification');
