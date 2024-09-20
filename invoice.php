<?php
/*
 * Plugin Name:       Invoice
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Himanshu
 * Author URI:        #
 * License:           GPL v2 or later
 * Text Domain:       Invoice
 * Domain Path:       /languages
 */

// Prevent direct access to the file
if (!defined('WPINC')) {
    die;
}

// Define the plugin directory URL if not already defined
if (!defined('INVOICE_PLUGIN_DIR')) {
    define('INVOICE_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

// Check if the function 'wpac_plugin_script' exists before defining it
if (!function_exists('wpac_plugin_script')) {
    /**
     * Enqueue the plugin's CSS and JS files.
     */
    function wpac_plugin_script() {
        // Enqueue CSS files
        wp_enqueue_style('custom-css', INVOICE_PLUGIN_DIR . 'assets/css/custom_style.css');
        wp_enqueue_style('bootstrap-css', INVOICE_PLUGIN_DIR . 'assets/css/bootstrap.min.css');

        // Enqueue JS files
        wp_enqueue_script('bootstrap-js', INVOICE_PLUGIN_DIR . 'assets/js/bootstrap.bundle.min.js', array(), null, true);
      
    wp_enqueue_script('my-ajax-script', plugin_dir_url(__FILE__) . 'assets/js/my-ajax-script.js');

    // Localize script with AJAX URL
    wp_localize_script('my-ajax-script', 'my_ajax_script', array(
        'ajax_script_url' => admin_url('admin-ajax.php')
    ));

  
    }
}

// Hook the 'wpac_plugin_script' function into the 'admin_enqueue_scripts' action
add_action('admin_enqueue_scripts', 'wpac_plugin_script');

// Include necessary PHP files from the 'includes' directory
require_once(plugin_dir_path(__FILE__) . 'includes/invoice-list.php');
require_once(plugin_dir_path(__FILE__) . 'includes/add_invoice.php');
require_once(plugin_dir_path(__FILE__) . 'includes/general_setting.php');

/**
 * Create a custom database table for the plugin.
 */

// Hook to the activation event


/**
 * Add menu items to the WordPress admin dashboard.
 */
function wpac_add_admin_menu() {
    // Add top-level menu
    add_menu_page(
        'Invoice',                // Page title
        'Invoices',               // Menu title
        'manage_options',         // Capability
        'invoice',                // Menu slug
        'invoice_add_invoice_page', // Callback function
        'dashicons-admin-generic' // Icon URL
    );

    // Add sub-menu items
    add_submenu_page(
        'invoice',                // Parent slug
        'Invoice List',           // Page title
        'Invoice List',           // Menu title
        'manage_options',         // Capability
        'invoice_list',           // Menu slug
        'invoice_plugin_list'     // Callback function
    );

    add_submenu_page(
        'invoice',                // Parent slug
        'Add New Invoice',        // Page title
        'Add New',                // Menu title
        'manage_options',         // Capability
        'add_invoice',            // Menu slug
        'invoice_add_invoice_page' // Callback function
    );

    add_submenu_page(
        'invoice',                // Parent slug
        'General Settings',       // Page title
        'Settings',               // Menu title
        'manage_options',         // Capability
        'general_settings',       // Menu slug
        'invoice_general_setting' // Callback function
    );
}

// Hook the 'wpac_add_admin_menu' function into the 'admin_menu' action
add_action('admin_menu', 'wpac_add_admin_menu');






function create_invoice_table() {
    global $wpdb;
    
    // Define table name with prefix
    $table_name = $wpdb->prefix . 'invoice_table';

    // SQL to create the table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        firstnamez varchar(255) NOT NULL,
        lastname varchar(255) NOT NULL,
        companyNames varchar(255) NOT NULL,
        addressz varchar(255) NOT NULL,
        cityz varchar(255) NOT NULL,
        statez varchar(255) NOT NULL,
        pincodez varchar(10) NOT NULL,
        gstz varchar(15) NOT NULL,
        company_logo_url varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Include the upgrade functions to create the table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // Create or update the table
    dbDelta($sql);
}

// Hook into the plugin activation to create the table
register_activation_hook(__FILE__, 'create_invoice_table');
