<?php
if (!empty($_POST)) {
    if (!empty($_POST["register"])) {
        // something went wrong ?>
        <div class="alert alert-danger" role="alert">
            The nickname is invalid or the passwords were not equal.
        </div>
<?php
    }
}
?>

<div class="container" id="register">
    <form class="form-signin" method="post">
        <label for="inputEmail" class="sr-only">Nickname</label>
        <input id="inputEmail" class="form-control" placeholder="Nickname" name="nickname" required="" autofocus=""
               type="text">
        <label for="inputPassword" class="sr-only">Password</label>
        <input id="inputPassword" class="form-control" placeholder="Password" required="" name="password"
               type="password">
        <label for="retryPassword" class="sr-only">Password</label>
        <input id="retryPassword" class="form-control" placeholder="Password" required="" name="retry_password"
               type="password">
        <input type="submit" class="btn btn-lg btn-primary btn-block" name="register" value="Sign up"/>
    </form>
</div>