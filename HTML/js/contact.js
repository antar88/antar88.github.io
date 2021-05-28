//Contact
$('#working_form').submit(function() {

    var action = $(this).attr('action');

    $("#message").slideUp(750, function() {
        $('#submit').attr('disabled', 'disabled');

        var name = $('#name').val();
        var email = $('#email').val();
        var comments = $('#comments').val();
        //$('#contact_form_container').remove();
        $('#social_form').after(`
            <div class="col-lg-8 d-flex align-items-center">
                <div id="form_spinner" class="align-middle spinner"></div>
                <div id="contact_message"></div>
            </div>`)

        $.post(action, {name: name, email: email, comments: comments},
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



