jQuery(document).ready(function($) {
    $('#my-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this); // Collect form data
        formData.append('action', 'my_form_save_data'); // Append the action
        formData.append('security', ajax_object.security); // Append nonce from localized script

        // Start AJAX request
        $.ajax({
            url: ajax_object.ajax_url, // Use the localized admin-ajax URL
            type: 'POST',
            data: formData,
            contentType: false, // Required for FormData
            processData: false, // Required for FormData
            success: function(response) {
                if (response.success) {
                    alert(response.data); // Success message
                } else {
                    alert('There was an error processing the request.');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); // For debugging errors
                alert('An AJAX error occurred: ' + error);
            }
        });
    });
});
