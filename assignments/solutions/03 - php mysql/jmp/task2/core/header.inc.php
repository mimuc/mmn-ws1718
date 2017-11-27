<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Assignment 3, task 1</title>

    <!-- Bootstrap core CSS -->
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/font-awesome/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
    <script src="./assets/js/jquery-3.2.1.min.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <a class="navbar-brand" href="#">Notes</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) { ?>
            <div class="mr-auto"></div>
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link"><?php echo $_SESSION["nickname"]; ?></a>
                </li>
            </ul>
            <form class="form-inline mt-2 mt-md-0" method="post" action="index.php">
                <input class="btn btn-outline-success my-2 my-sm-0" type="submit" name="logout" value="Logout"/>
            </form>
        <?php } ?>
    </div>
</nav>
<div class="container">