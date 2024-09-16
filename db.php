
<?php  function my_plugin_create_table() {
    global $wpdb;

    // Define the table name with the WordPress table prefix
    $table_name = $wpdb->prefix . 'wp_admin_table';
    
    // SQL query to create the table
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        companyName varchar(100) NOT NULL,
        address varchar(100) NOT NULL,
        city varchar(100) NOT NULL,
        state varchar(100) NOT NULL,
        pincode varchar(100) NOT NULL,
        gst varchar(100) NOT NULL,
        fileuplord varchar(100) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
  
}

?>