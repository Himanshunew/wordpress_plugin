<?php
// Function to display the invoice page content
function invoice_add_invoice_page()
{
    ?>
    <div class="wrap">
    <form id="invoice-form">

        <!-- Invoice Form HTML -->
        <div class="invoice-container">
            <div class="header">
                <div class="logo-invoice"> <?php the_custom_logo(); ?></div>
                <div class="invoice-title">INVOICE</div>
            </div>

            <div class="form-row">
                <div class="company-info">

                    <div>Email: hdgroupoiling@gmail.com</div>
                    <div>Website: mgroupoiling.com.au</div>
                    <div>ABN: 51652559838</div>
                </div>



                <div class="form-group">
                    <div class="form-row">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date">
                    </div>
                    <div class="form-row">
                        <label for="expiry-date">Expiry Date:</label>
                        <input type="date" id="expiry-date" name="expiry-date">
                    </div>
                </div>

            </div>


            <h3 class="text-center-invoice pt pb-10 ">TAX INVOICE NO:</h3>
            <hr class>



            <div class="form-row">
                <div class="form-group">
                    <label for="customer">Customer:</label>
                    <input type="text" id="customer" name="customer">
                </div>
                <div class="form-group">
                    <label for="job-no">Job No.:</label>
                    <input type="text" id="job-no" name="job-no">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="abn">ABN:</label>
                    <input type="text" id="abn" name="abn">
                </div>
                <div class="form-group">
                    <label for="order-no">Order No.:</label>
                    <input type="text" id="order-no" name="order-no">
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>QTY.</th>
                        <th>DESCRIPTION</th>
                        <th>RATE</th>
                        <th>VALUE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="qty[]"></td>
                        <td><input type="text" name="description[]"></td>
                        <td><input type="text" name="rate[]"></td>
                        <td><input type="text" name="value[]"></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <div class="additional-info">
                <div class="bank-details">
                    <h3>Bank Details</h3>
                    <p>NAB<br>
                        BSB - 083004<br>
                        Account No: 157690599<br>
                        MZ group tiling pty Ltd</p>
                </div>
                <div class="payment-details">
                    <div class="payment-row">
                        <label>Sub Total</label>
                        <input type="text" name="subtotal">
                    </div>
                    <div class="payment-row">
                        <label>G.S.T.</label>
                        <input type="text" name="gst">
                    </div>
                    <div class="payment-row">
                        <label>TOTAL AMOUNT</label>
                        <input type="text" name="total_amount">
                    </div>

                    <input type="submit" value="Submit" class="pt">

                </div>
            </div>

        </div>


        
    </div>
    </form>
    <?php
}

function invoice_save_data() {
    // Verify nonce for security
    check_ajax_referer( 'invoice_nonce', 'nonce' );

    // Sanitize and get the input values
    $customer      = sanitize_text_field( $_POST['customer'] );
    $job_no        = sanitize_text_field( $_POST['job_no'] );
    $address       = sanitize_text_field( $_POST['address'] );
    $location      = sanitize_text_field( $_POST['location'] );
    $abn           = sanitize_text_field( $_POST['abn'] );
    $order_no      = sanitize_text_field( $_POST['order_no'] );
    $subtotal      = sanitize_text_field( $_POST['subtotal'] );
    $gst           = sanitize_text_field( $_POST['gst'] );
    $total_amount  = sanitize_text_field( $_POST['total_amount'] );

    // Prepare data to insert
    global $wpdb;
    $table_name = $wpdb->prefix . 'client_details_invoice';

    // Check if table exists
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        wp_send_json_error('Table does not exist.');
        return;
    }

    $data = array(
        'customer'      => $customer,
        'job_no'        => $job_no,
        'address'       => $address,
        'location'      => $location,
        'abn'           => $abn,
        'order_no'      => $order_no,
        'subtotal'      => $subtotal,
        'gst'           => $gst,
        'total_amount'  => $total_amount,
        'created_at'    => current_time('mysql')
    );

    // Insert the data
    $inserted = $wpdb->insert( $table_name, $data );

    if( $inserted === false ) {
        wp_send_json_error( 'Database insert failed: ' . $wpdb->last_error );
    } else {
        wp_send_json_success( 'Invoice saved successfully.' );
    }
}
add_action( 'wp_ajax_invoice_save_data', 'invoice_save_data' );
add_action( 'wp_ajax_nopriv_invoice_save_data', 'invoice_save_data' );


function invoice_enqueue_scripts() {
    wp_enqueue_script( 'invoice-ajax-script', get_template_directory_uri() . '/js/invoice-ajax.js', array('jquery'), null, true );

    // Localize script to pass AJAX URL to the JavaScript file
    wp_localize_script( 'invoice-ajax-script', 'invoice_ajax_obj', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'invoice_nonce' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'invoice_enqueue_scripts' );




?>