//Contact
$('#working_form').submit(function() {

    var action = $(this).attr('action');

    $("#message").slideUp(750, function() {
        $('#submit').attr('disabled', 'disabled');
        $('#submit').attr('disabled', 'disabled');
        $('#contact_form_container').remove();
        $('#social_form').after(`
            <div class="col-lg-8 d-flex align-items-center">
                <div id="form_spinner" class="align-middle spinner"></div>
                <div id="contact_message"></div>
            </div>`)

        $.post(action, {
                name: $('#name').val(),
                email: $('#email').val(),
                comments: $('#comments').val(),
            },
            function(data) {
                $('#contact_message').innerHTML = data;
                $('#message').slideDown('slow');
                $('#form_spinner').fadeOut('slow', function() {
                    $(this).remove()
                });

                if ('success'.match(data.result) != null) $('#cform').slideUp('slow');
            }
        );

    });

    return false;

});



