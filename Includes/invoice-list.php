<?php 
// Function to display content on the page
function invoice_plugin_list() {
    ?>
    <div class="wpbody-content">
        <h1>Invoices</h1>
        <p>Welcome to the Invoices page. Here you can manage your invoices.</p>
    </div>
  


    
 <div class="container text-center mt-5">
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="custom-box">
          <i class="bi bi-house-door" style="font-size: 3rem;"></i>
          <a href="<?php echo admin_url( 'admin.php?page=add_invoice' ); ?>" class="heading-link">Add Invoice</a>


          <p>Welcome to our homepage!</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="custom-box">
          <i class="bi bi-telephone" style="font-size: 3rem;"></i>
          <h3>Contact</h3>
          <p>Reach out to us anytime.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="custom-box">
          <i class="bi bi-envelope" style="font-size: 3rem;"></i>
          <h3>Email</h3>
          <p>Send us an email for inquiries.</p>
        </div>
      </div>
    </div>
  </div>

  <style>
    .custom-box {
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }
    
    .custom-box:hover {
      transform: translateY(-5px);
    }
  </style>

<?php
}
?>