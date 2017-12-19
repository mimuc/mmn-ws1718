<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>MySQLi</title>
</head>
<body>

<table>
<?php
$c = mysqli_connect('localhost', 'mmn', 'mmn.demo.pass', 'mmn1617');


$query = "SELECT id, artist, title FROM albums";

if ($c) {
    $result = mysqli_query($c, $query);
    while ($album = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo '<td>'.$album['id'].'</td>';
        echo '<td>'.$album['artist'].'</td>';
        echo '<td>'.$album['title'].'</td>';
        echo "</tr>";
    }
}

mysqli_close($c);
?>

</table>
</body>
</html>