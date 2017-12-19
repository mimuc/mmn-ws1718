<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, user-scalable=yes">
    <title></title>
</head>
<body>

<table>
    <?php


    $connectionInfo = array(
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "database" => "mmn1617"
    );

    require_once('connectionInfo.private.php'); // TODO uncomment.

    $c = mysqli_connect($connectionInfo['host'],
        $connectionInfo['username'], $connectionInfo['password'],
        $connectionInfo['database']
    );


    // to make this work, you'll have to import the films database.
    $selectFilmsQuery = "SELECT * FROM film";

    $filmResult = mysqli_query($c, $selectFilmsQuery);


    while ($film = mysqli_fetch_assoc($filmResult)) {
        echo '<tr>';
        echo    '<td>' . $film['title'] . '</td>';
        echo    '<td>' . $film['description'] . '</td>';
        echo    '<td>' . $film['release_year'] . '</td>';
        echo '</tr>';
    }

    ?>

</table>
</body>
</html>