<?php
if(!$_POST) $_POST = json_decode(file_get_contents("php://input"), true);

if(!$_POST) exit;

// Email address verification, do not edit.
function isEmail($email) {
	return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
}

if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

$name     = $_POST['name'];
$email    = $_POST['email'];
$comments = $_POST['comments'];

function print_err ($msg) {
    $err = ['result' => 'error', 'html' => '<div class="error_msg">' . $msg . '</div>'];
    echo json_encode($err);
    exit();
}

if(trim($name) == '') {
	print_err('You must enter your name.');
} else if(trim($email) == '') {
	print_err('Please enter a valid email address.');
} else if(!isEmail($email)) {
	print_err('You have entered an invalid e-mail address. Please try again.');
}
if(trim($comments) == '') {
	print_err('Please enter your message.');
}

if(get_magic_quotes_gpc()) {
	$comments = stripslashes($comments);
}

// Configuration option.
// i.e. The standard subject will appear as, "You've been contacted by John Doe."

// Example, $e_subject = '$name . ' has contacted you via Your Website.';

$subject = 'You have been contacted by ' . $name . '.';

// Configuration option.
// You can change this if you feel that you need to.
// Developers, you may wish to add more fields to the form, in which case you must be sure to add them here.

$e_body = "You have been contacted by $name. Their additional message is as follows." . PHP_EOL . PHP_EOL;
$e_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
$e_reply = "You can contact $name via email, $email";

$body = wordwrap( $e_body . $e_content . $e_reply, 70 );

require_once "Mail.php";
if (!file_exists("./contact.json")) {
    print_err('Internal server error. Please contact me via <a href="mailto:antar@antarmf.com"> email </a>');
}

$conf = file_get_contents(getcwd ( ) ."/contact.json");
$conf = json_decode($conf, true);

$from = $conf["from"];
$to = $conf["to"];
$host = $conf["host"];
$username = $conf["username"];
$password = $password["from"];
$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject);
$smtp = Mail::factory('smtp',
        array (
                'host' => $host,
                'port' => 587,
                'auth' => true,
                'debug' => true,
                'username' => $username,
                'password' => $password
        )
);

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
    print_err("<h3 class='text-danger'>There was a problem sending the message.</h3><p class='text-danger'>Oops, try again or if the problem persist contact me via <a href='mailto:antar@antarmf.com'> email </a></p>");
} else {
    $output = [
        'result' => 'success',
        'html' => "<div id='success_msg'><h3>Email Sent Successfully.</h3><p>Thank <strong>" . $name . "</strong> for contacting me, I'll come back to you soon.</p></div>"
    ];
    echo json_encode($output);
}
