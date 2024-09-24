<?php

// Function to display the invoice page content
function invoice_add_invoice_page()
{
    global $general_setting;

    // Fetch invoice data
    $invoice_data = fetch_invoice_data($general_setting);
?>

    <div class="wrap">
        <form id="invoice-form">
            <!-- Invoice Form HTML -->
            <div class="invoice-container">
                <div class="header">
                    <!-- Display custom logo -->
                    <div class="logo-invoice"><?php the_custom_logo(); ?></div>
                    <div class="invoice-title">INVOICE</div>
                </div>

                <div class="form-row">
                    <div class="company-info">
                        <!-- Company information display -->
                        <div>Email: <?php echo esc_html($invoice_data['email']); ?></div>
                        <div>Phone: <?php echo esc_html($invoice_data['telphone'] ?? ''); ?></div>
                        <div>ABN: <?php echo esc_html($invoice_data['abnnumber']); ?></div>
                    </div>

                    <div class="form-group">
                        <!-- Date and Expiry Date input fields -->
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

                <h3 class="text-center-invoice pt pb-10">TAX INVOICE NO:</h3>
                <hr class="invoice-separator">

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

                <!-- Invoice Items Table -->
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
                        <p>
                            <?php echo esc_html($invoice_data['bank_name']); ?><br>
                            IFSE Code - <?php echo esc_html($invoice_data['ifscode']); ?><br>
                            Account No: <?php echo esc_html($invoice_data['accountno']); ?><br>
                            Account Holder: <?php echo esc_html($invoice_data['accountholder']); ?>
                        </p>
                    </div>
                    <div class="payment-details">
                        <!-- Payment details inputs -->
                        <div class="payment-row">
                            <label>Sub Total:</label>
                            <input type="text" name="subtotal">
                        </div>
                        <div class="payment-row">
                            <label>G.S.T.:</label>
                            <input type="text" name="gst">
                        </div>
                        <div class="payment-row">
                            <label>TOTAL AMOUNT:</label>
                            <input type="text" name="total_amount">
                        </div>

                        <!-- Submit button -->
                        <input type="submit" value="Submit" class="pt">
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
}


