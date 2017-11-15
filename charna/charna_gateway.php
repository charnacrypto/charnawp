<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Include our Gateway Class and register Payment Gateway with WooCommerce
add_action('plugins_loaded', 'charna_init', 0);
function charna_init()
{
    /* If the class doesn't exist (== WooCommerce isn't installed), return NULL */
    if (!class_exists('WC_Payment_Gateway')) return;


    /* If we made it this far, then include our Gateway Class */
    include_once('include/charna_payments.php');
    require_once('library.php');

    // Lets add it too WooCommerce
    add_filter('woocommerce_payment_gateways', 'charna_gateway');
    function charna_gateway($methods)
    {
        $methods[] = 'Charna_Gateway';
        return $methods;
    }
}

/*
 * Add custom link
 * The url will be http://yourworpress/wp-admin/admin.php?=wc-settings&tab=checkout
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'charna_payment');
function charna_payment($links)
{
    $plugin_links = array(
        '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout') . '">' . __('Settings', 'charna_payment') . '</a>',
    );

    return array_merge($plugin_links, $links);
}

add_action('admin_menu', 'charna_create_menu');
function charna_create_menu()
{
    add_menu_page(
        __('CharnaCoin', 'textdomain'),
        'Charnacoin',
        'manage_options',
        'admin.php?page=wc-settings&tab=checkout&section=charna_gateway',
        '',
        plugins_url('charna/assets/icon.png'),
        56 // Position on menu, woocommerce has 55.5, products has 55.6

    );
}


