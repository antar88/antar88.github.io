$('#working_form').submit(function() {

    var action = $(this).attr('action');

    $("#message").slideUp(750, function() {
        $('#message').hide();
        $('#submit').attr('disabled', 'disabled').attr('value', 'Sending...');

        $.post(action, {
                name: $('#name').val(),
                email: $('#email').val(),
                comments: $('#comments').val(),
            },
            function(data) {
                data = JSON.parse(data);
                document.getElementById('message').innerHTML = data.html;
                $('#message').slideDown('slow');

                if ('success'.match(data.result) != null) {
                    $('#working_form').slideUp('slow');
                    $('#contact_form_container').addClass('d-flex').addClass('align-items-center');
                    $('#contact_form_container').find('div.contact_form').addClass('col-lg-12').addClass('align-middle');
                }
                else {
                    $('#submit').attr('value', 'Try again').removeAttr('disabled');
                }
            }
        );

    });

    return false;

});
