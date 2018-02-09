<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Session Fun</title>
</head>
<body>

<?php
// let's play around with the session name.
echo session_name() . '<br />';
session_name('MyNewFancySessionName');
echo session_name() . '<br />';

// this gives you the session id
echo session_id() . '<br />';

// now create a session variable.
// the answer will be maintained as long as
// we don't destroy / unset the session
$_SESSION['answer'] = 'yes';


session_unset(); // maintains the session Id, but removes all session variables.

echo session_id() . '<br />'; // stays the same as before.
echo $_SESSION['answer'] . '<br />'; // ? not there anymore!

$_SESSION['answer'] = 'no';
echo $_SESSION['answer'] . '<br />'; // ? "no"

// try this: session_destroy(); $_SESSION = array();
// what happens if you do this now: echo session_id()?
?>
</body>
</html>