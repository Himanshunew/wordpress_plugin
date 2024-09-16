<?php

function invoice_general_setting($content) {
    $first_name = get_option('firstnamez', '');
    $last_name = get_option('lastname', '');
    $company_name = get_option('companyNames', '');
    $company_address = get_option('addressz', '');
    $city = get_option('cityz', '');
    $state = get_option('statez', '');
    $pincode = get_option('pincodez', '');
    $gst = get_option('gstz', '');

    // Generate nonce
    $nonce = wp_create_nonce('invoice_general_setting_nonce');

    ob_start(); // Start buffering for shortcode output
    ?>
    <div class="container p-2">
        <h1 class="fs-4">General Setting</h1>
    </div>

    <form id="my-form" method="post" enctype="multipart/form-data">
        <div class="container p-2">
            <div class="bg-white p-3 shadow-sm rounded">
                <h2 class="fs-5">Admin Details</h2>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" id="fname" name="firstnamez" class="form-control" value="<?php echo esc_attr($first_name); ?>" placeholder="Your name..">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" id="lname" name="lastname" class="form-control" value="<?php echo esc_attr($last_name); ?>" placeholder="Your last name..">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" id="companyName" name="companyNames" class="form-control" value="<?php echo esc_attr($company_name); ?>" placeholder="Your Company Name">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="address" class="form-label">Company Address</label>
                        <input type="text" id="address" name="addressz" class="form-control" value="<?php echo esc_attr($company_address); ?>" placeholder="Your Company Address..">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="cityz" class="form-control" value="<?php echo esc_attr($city); ?>" placeholder="Your City">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" id="state" name="statez" class="form-control" value="<?php echo esc_attr($state); ?>" placeholder="Your State..">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="text" id="pincode" name="pincodez" class="form-control" value="<?php echo esc_attr($pincode); ?>" placeholder="Pincode">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="gst" class="form-label">GST</label>
                        <input type="text" id="gst" name="gstz" class="form-control" value="<?php echo esc_attr($gst); ?>" placeholder="Your GST Number">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="fileInput" class="form-label">Your Company Logo</label>
                        <input type="file" id="fileInput" name="fileuplordz" class="form-control">
                    </div>
                    <div class="col-lg-12">
                        <input type="submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php
    return ob_get_clean(); // Return the buffered content
}

add_shortcode('invoice_general_setting', 'invoice_general_setting');




function my_form_save_data() {
    check_ajax_referer('invoice_general_setting_nonce', 'security');

    $first_name  = sanitize_text_field($_POST['firstnamez']);
    $last_name   = sanitize_text_field($_POST['lastname']);
    $company     = sanitize_text_field($_POST['companyNames']);
    $address     = sanitize_text_field($_POST['addressz']);
    $city        = sanitize_text_field($_POST['cityz']);
    $state       = sanitize_text_field($_POST['statez']);
    $pincode     = sanitize_text_field($_POST['pincodez']);
    $gst         = sanitize_text_field($_POST['gstz']);

    // Handle file upload
    if (!empty($_FILES['fileuplordz']['name'])) {
        $uploaded = wp_handle_upload($_FILES['fileuplordz'], array('test_form' => false));
        $file_url = $uploaded['url'];
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_details';
    $wpdb->insert(
        $table_name,
        array(
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'company'     => $company,
            'address'     => $address,
            'city'        => $city,
            'state'       => $state,
            'pincode'     => $pincode,
            'gst'         => $gst,
            'logo_url'    => isset($file_url) ? $file_url : ''
        )
    );

    wp_send_json_success('Data saved successfully');
}
add_action('wp_ajax_my_form_save_data', 'my_form_save_data');
add_action('wp_ajax_nopriv_my_form_save_data', 'my_form_save_data');




function my_form_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('my_form_ajax', plugin_dir_url(__FILE__) . 'js/form-handler.js', array('jquery'), null, true);

    // Localize script to pass AJAX URL and security nonce
    wp_localize_script('my_form_ajax', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('invoice_general_setting_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'my_form_enqueue_scripts');
