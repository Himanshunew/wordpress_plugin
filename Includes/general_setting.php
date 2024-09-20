<?php

function invoice_general_setting($content)
{
    // Retrieve stored options
    $first_name = get_option('firstnamez', '');
    $last_name = get_option('lastname', '');
    $company_name = get_option('companyNames', '');
    $company_address = get_option('addressz', '');
    $city = get_option('cityz', '');
    $state = get_option('statez', '');
    $pincode = get_option('pincodez', '');
    $gst = get_option('gstz', '');


    // Generate nonce for security
    $nonce = wp_create_nonce('invoice_general_setting_nonce');
?>
    <div class="container p-2">
        <h1 class="fs-4">General Setting</h1>
    </div>


   
    <?php
$general_setting = array(); // Define an empty array or pass a valid argument
$general_setting = fetch_invoice_data($general_setting);
?>

    <form id="my-form" method="post" action="<?= esc_url(admin_url('admin-ajax.php')); ?>" enctype="multipart/form-data">
    <div class="container p-2">
        <div class="bg-white p-3 shadow-sm rounded">
            <h2 class="fs-5">Admin Details</h2>
            <div class="row">
                <!-- First Name -->
                <div class="col-lg-6 mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" name="firstnamez" placeholder="Your name.." value="<?php echo esc_attr($general_setting['firstnamez']); ?>">

                </div>

                <!-- Last Name -->
                <div class="col-lg-6 mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" id="lname" name="lastname" class="form-control" value="<?php echo esc_attr($general_setting['lastname']); ?>" placeholder="Your last name..">
                </div>

                <!-- Company Name -->
                <div class="col-lg-6 mb-3">
                    <label for="companyName" class="form-label">Company Name</label>
                    <input type="text" id="companyName" name="companyNames" class="form-control" value="<?php echo esc_attr($general_setting['companyNames']); ?>" placeholder="Your Company Name">
                </div>

                <!-- Company Address -->
                <div class="col-lg-6 mb-3">
                    <label for="address" class="form-label">Company Address</label>
                    <input type="text" id="address" name="addressz" class="form-control" value="<?php echo esc_attr($general_setting['addressz']); ?>" placeholder="Your Company Address..">
                </div>

                <!-- City -->
                <div class="col-lg-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" id="city" name="cityz" class="form-control" value="<?php echo esc_attr($general_setting['cityz']); ?>" placeholder="Your City">
                </div>

                <!-- State -->
                <div class="col-lg-6 mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" id="state" name="statez" class="form-control" value="<?php echo esc_attr($general_setting['statez']); ?>" placeholder="Your State..">
                </div>

                <!-- Pincode -->
                <div class="col-lg-6 mb-3">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" id="pincode" name="pincodez" class="form-control" value="<?php echo esc_attr($general_setting['pincodez']); ?>" placeholder="Pincode">
                </div>

                <!-- GST -->
                <div class="col-lg-6 mb-3">
                    <label for="gst" class="form-label">GST</label>
                    <input type="text" id="gst" name="gstz" class="form-control" value="<?php echo esc_attr($general_setting['gstz']); ?>" placeholder="Your GST Number">
                </div>

                <!-- Company Logo -->
                <div class="col-lg-6 mb-3">
                    <label for="fileInput" class="form-label">Your Company Logo</label>
                    <input type="file" id="fileInput" name="fileuplordz" class="form-control">
                </div>

                <!-- Submit Button -->
                <div class="col-lg-12">
                    <input type="submit" value="Submit">
                </div>
            </div>
        </div>
    </div>
</form>
<?php
}
function handle_invoice_general_setting_ajax() {
    global $wpdb;

    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'invoice_general_setting_nonce')) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
    }

    // Handle file upload
    $file_url = '';
    if (isset($_FILES['fileuplordz']) && !empty($_FILES['fileuplordz']['name'])) {
        $uploaded_file = $_FILES['fileuplordz'];
        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            $file_url = $movefile['url'];
        } else {
            wp_send_json_error(array('message' => $movefile['error']));
        }
    }

    // Sanitize input
    $data = array(
        'firstnamez'   => sanitize_text_field($_POST['firstnamez']),
        'lastname'      => sanitize_text_field($_POST['lastname']),
        'companyNames'  => sanitize_text_field($_POST['companyNames']),
        'addressz'      => sanitize_text_field($_POST['addressz']),
        'cityz'         => sanitize_text_field($_POST['cityz']),
        'statez'        => sanitize_text_field($_POST['statez']),
        'pincodez'      => sanitize_text_field($_POST['pincodez']),
        'gstz'          => sanitize_text_field($_POST['gstz']),
        'company_logo_url' => esc_url($file_url),
    );

    // Check if record exists (use appropriate WHERE clause for your logic)
    $table_name = $wpdb->prefix . 'invoice_table'; // Adjust table name with prefix
    $existing_record = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE id = %d", 1)); // Adjust as needed

    if ($existing_record > 0) {
        // Update existing record
        $wpdb->update(
            $table_name,
            $data,
            array('id' => 1) // Adjust the WHERE clause as needed
        );
    } else {
        // Insert new record
        $wpdb->insert($table_name, $data);
    }

    if ($wpdb->last_error) {
        wp_send_json_error(array('message' => $wpdb->last_error));
    }

    wp_send_json_success(array('message' => 'Settings saved successfully'));
}
add_action('wp_ajax_handle_invoice_general_setting', 'handle_invoice_general_setting_ajax');


function enqueue_my_custom_script() {
    wp_enqueue_script('my-custom-script', plugin_dir_url(__FILE__) . 'js/my-custom-script.js', array('jquery'), null, true);
    wp_localize_script('my-custom-script', 'my_ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('invoice_general_setting_nonce'),
        'fetch_nonce' => wp_create_nonce('fetch_invoice_data_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_my_custom_script');




function fetch_invoice_data($general_setting) {
    global $wpdb;

    // Retrieve the existing record from the database
    $table_name = $wpdb->prefix . 'invoice_table'; // Adjust the table name as needed
    $invoice_data = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1"); // Adjust the WHERE clause as needed

    // Return the fetched data, or default values if no data found
    return array(
        'firstnamez' => $invoice_data->firstnamez ?? '',
        'lastname'    => $invoice_data->lastname ?? '',
        'companyNames'=> $invoice_data->companyNames ?? '',
        'addressz'    => $invoice_data->addressz ?? '',
        'cityz'       => $invoice_data->cityz ?? '',
        'statez'      => $invoice_data->statez ?? '',
        'pincodez'    => $invoice_data->pincodez ?? '',
        'gstz'        => $invoice_data->gstz ?? '',
        'company_logo_url' => $invoice_data->company_logo_url ?? '',
    );
}
