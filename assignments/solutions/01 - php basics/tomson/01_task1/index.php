<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>What's wrong here?</title>
</head>
<body>
<?php
function loginUser($email, $password){
   var_dump($_POST);
    if($password === 'test.pass') {
        echo '<p>You are now logged in.</p>';
    } else {
        echo '<p>Wrong password</p>';
    }
}
if (!empty($_POST)) {
    loginUser($_POST['email'], $_POST['password']);
} else { ?>
    <form method="post" action="index.php">
        <label>
            Email: <input type="email" name="email" />
        </label>
        <label>
            Password: <input type="password" name="password" />
        </label>
        <input type="submit" name="submit" />
    </form>
<?php } ?>
</body>
</html>

<!---
- Input Formular hat keinen richtigen Submit Button.
- Es fehlt das action-Attribut für die Formulare.
- Passwort wird per URL mit GET übertragen. Etwas besser ist die Verwendung von POST.
---!>