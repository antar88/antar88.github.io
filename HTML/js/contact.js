$('#working_form').submit(function() {

    var action = $(this).attr('action');

    $("#message").slideUp(750, function() {
        $('#message').hide();

        $('#submit')
            .before('<img id="contact_spinner" src="" class="spinner" />')
            .attr('disabled', 'disabled')
            .attr('value', 'Sending...');

        $.post(action, {
                name: $('#name').val(),
                email: $('#email').val(),
                comments: $('#comments').val(),
            },
            function(data) {
                data = JSON.parse(data);
                document.getElementById('message').innerHTML = data.html;
                $('#message').slideDown('slow');
                $('#contact_spinner').fadeOut('slow', function() {
                    $(this).remove()
                });

                if ('success'.match(data.result) != null) {
                    $('#working_form').slideUp('slow');
                }
                else {
                    $('#submit').attr('value', 'Try again').removeAttr('disabled');
                }
            }
        );

    });

    return false;

});
