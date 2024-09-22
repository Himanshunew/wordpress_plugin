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

// Define the plugin directory URL
if (!defined('INVOICE_PLUGIN_DIR')) {
    define('INVOICE_PLUGIN_DIR', plugin_dir_url(__FILE__));
}

// Enqueue the plugin's CSS and JS files
function wpac_plugin_script() {
    wp_enqueue_style('custom-css', INVOICE_PLUGIN_DIR . 'assets/css/custom_style.css');
    wp_enqueue_style('bootstrap-css', INVOICE_PLUGIN_DIR . 'assets/css/bootstrap.min.css');

    wp_enqueue_script('bootstrap-js', INVOICE_PLUGIN_DIR . 'assets/js/bootstrap.bundle.min.js', array(), null, true);
    wp_enqueue_script('my-ajax-script', INVOICE_PLUGIN_DIR . 'assets/js/my-ajax-script.js', array('jquery'), null, true);

    // Localize script with AJAX URL
    wp_localize_script('my-ajax-script', 'my_ajax_script', array(
        'ajax_script_url' => admin_url('admin-ajax.php')
    ));
}
add_action('admin_enqueue_scripts', 'wpac_plugin_script');

// Include necessary PHP files
require_once(plugin_dir_path(__FILE__) . 'includes/add_invoice.php');
require_once(plugin_dir_path(__FILE__) . 'includes/general_setting.php');

// Add menu items to the WordPress admin dashboard
function wpac_add_admin_menu() {
    add_menu_page(
        'Invoice',
        'Invoices',
        'manage_options',
        'invoice',
        'invoice_add_invoice_page',
        'dashicons-admin-generic'
    );

    add_submenu_page(
        'invoice',
        'Add New Invoice',
        'Add New',
        'manage_options',
        'add_invoice',
        'invoice_add_invoice_page'
    );

    add_submenu_page(
        'invoice',
        'General Settings',
        'Settings',
        'manage_options',
        'general_settings',
        'invoice_general_setting'
    );
}
add_action('admin_menu', 'wpac_add_admin_menu');

// Create custom database tables on activation
function create_invoice_tables() {
    create_invoice_table('admin_details_invoice');
    create_customer_invoice_table('client_details_invoice');
}
register_activation_hook(__FILE__, 'create_invoice_tables');

function create_invoice_table($table_name) {
    global $wpdb;

    // SQL to create the table if it doesn't exist
    $table_name = $wpdb->prefix . $table_name;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(255),
        telephone VARCHAR(50),
        address TEXT,
        accountno VARCHAR(50),
        ifscode VARCHAR(50),
        accountholder VARCHAR(255),
        abnnumber VARCHAR(50)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


function create_customer_invoice_table($table_name = 'client_details_invoice') {
    global $wpdb;

    // Ensure the table name uses the correct prefix
    $table_name = $wpdb->prefix . $table_name;

    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create the table
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        date date NOT NULL,
        expiry_date date NOT NULL,
        customer varchar(255) NOT NULL,
        job_no varchar(255),
        address varchar(255),
        location varchar(255),
        abn varchar(255),
        order_no varchar(255),
        qty decimal(10,2),
        description text,
        rate decimal(10,2),
        value decimal(10,2),
        subtotal decimal(10,2),
        gst decimal(10,2),
        total_amount decimal(10,2),
        PRIMARY KEY  (id),
        KEY customer_idx (customer),
        KEY date_idx (date)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
