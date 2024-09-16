jQuery(document).ready(function($) {
    $('#my-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'handle_invoice_general_setting');
        formData.append('nonce', my_ajax_obj.nonce);

        $.ajax({
            url: my_ajax_obj.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('An error occurred.');
            }
        });
    });
});
