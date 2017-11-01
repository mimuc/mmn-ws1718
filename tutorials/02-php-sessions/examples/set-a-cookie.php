<?php
if(isset($_POST['name'])){
    setcookie('Name',$_POST['name']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Set-a-Cookie!</title>
    <meta name="author" content="Tobias Seitz">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php
if(isset($_COOKIE['Name'])){
    echo '<h1>Hello '.$_COOKIE['Name'].'</h1>';
}
?>

<form method="post">
    <label>Name: <input type="text" name="name"/></label>
    <input type="submit" />
</form>
</body></html>
