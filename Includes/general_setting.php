<?php



function invoice_general_setting($content)
{
    // Retrieve stored options
    $first_name = get_option('name', '');
    $last_name = get_option('email', '');
    $company_name = get_option('telphone', '');
    $company_address = get_option('address', '');
    $city = get_option('accountno', '');
    $state = get_option('ifscode', '');
    $pincode = get_option('accountholder', '');
    $gst = get_option('abnnumber', '');


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
                <!-- Name -->
                <div class="col-lg-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" placeholder="Your name.." value="<?php echo esc_attr($general_setting['name']); ?>">
                </div>

                <!-- Email -->
                <div class="col-lg-6 mb-3">
                    <label for="lname" class="form-label">Email</label>
                    <input type="email" id="lname" name="email" class="form-control" value="<?php echo esc_attr($general_setting['email']); ?>" placeholder="Your email.." required>
 
                </div>

                <!-- Phone Number -->
                <div class="col-lg-6 mb-3">
                    <label for="companyName" class="form-label">Phone Number</label>
                    <input type="tel" id="companyName" name="telphone" class="form-control" value="<?php echo esc_attr($general_setting['telphone']); ?>" placeholder="Your phone number.." pattern="[0-9]*" inputmode="numeric">

                </div>

                <!-- Company Address -->
                <div class="col-lg-6 mb-3">
                    <label for="address" class="form-label">Company Address</label>
                    <input type="text" id="address" name="address" class="form-control" value="<?php echo esc_attr($general_setting['address']); ?>" placeholder="Your Company Address..">
                </div>

                <!-- Bank Account -->
                <div class="col-lg-6 mb-3">
                    <label for="city" class="form-label">Bank Account</label>
                    <input type="text" id="city" name="accountno" class="form-control" value="<?php echo esc_attr($general_setting['accountno']); ?>" placeholder="Your Bank Account..">
                </div>

                <!-- Bank IFSC Code -->
                <div class="col-lg-6 mb-3">
                    <label for="state" class="form-label">Bank IFSC Code</label>
                    <input type="text" id="state" name="ifscode" class="form-control" value="<?php echo esc_attr($general_setting['ifscode']); ?>" placeholder="Your IFSC Code..">
                </div>

                <!-- Account Name -->
                <div class="col-lg-6 mb-3">
                    <label for="pincode" class="form-label">Account Name</label>
                    <input type="text" id="pincode" name="accountholder" class="form-control" value="<?php echo esc_attr($general_setting['accountholder']); ?>" placeholder="Your Account Holder Name..">
                </div>

                <!-- ABN -->
                <div class="col-lg-6 mb-3">
                    <label for="gst" class="form-label">ABN</label>
                    <input type="text" id="gst" name="abnnumber" class="form-control" value="<?php echo esc_attr($general_setting['abnnumber']); ?>" placeholder="Your ABN Number..">
                </div>

                <!-- Submit Button -->
                <div class="col-lg-12">
                    <input type="submit" value="Submit" class="btn btn-primary">
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
        'name'   => sanitize_text_field($_POST['name']),
        'email'      => sanitize_text_field($_POST['email']),
        'telphone'  => sanitize_text_field($_POST['telphone']),
        'address'      => sanitize_text_field($_POST['address']),
        'accountno'         => sanitize_text_field($_POST['accountno']),
        'ifscode'        => sanitize_text_field($_POST['ifscode']),
        'accountholder'      => sanitize_text_field($_POST['accountholder']),
        'abnnumber'          => sanitize_text_field($_POST['abnnumber']),

    );

    // Check if record exists (use appropriate WHERE clause for your logic)
    $table_name = $wpdb->prefix . 'admin_details_invoice';

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
    $table_name = $wpdb->prefix . 'admin_details_invoice';

    $invoice_data = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1"); // Adjust the WHERE clause as needed

    // Return the fetched data, or default values if no data found
    return array(
        'name' => $invoice_data->name ?? '',
        'email'    => $invoice_data->email ?? '',
        'telphone'=> $invoice_data->telphone ?? '',
        'address'    => $invoice_data->address ?? '',
        'accountno'       => $invoice_data->accountno ?? '',
        'ifscode'      => $invoice_data->ifscode ?? '',
        'accountholder'    => $invoice_data->accountholder ?? '',
        'abnnumber'        => $invoice_data->abnnumber ?? '',
      
    );
}


