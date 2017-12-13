<?php

if (!empty($_POST)) {
    if (!empty($_POST["login"])) {
        // credentials are wrong?>
        <div class="alert alert-danger" role="alert">
            Wrong credentials
        </div>
        <?php
    } else if(!empty($_POST["register"])){
        // user registered ?>
        <div class="alert alert-success" role="alert">
            Account created. Please log in.
        </div>
    <?php }
}

?>
<div class="container" id="login">
    <form class="form-signin" method="post">
        <label for="inputEmail" class="sr-only">Nickname</label>
        <input id="inputEmail" class="form-control" placeholder="Nickname" name="nickname" required="" autofocus=""
               type="text">
        <label for="inputPassword" class="sr-only">Password</label>
        <input id="inputPassword" class="form-control" placeholder="Password" required="" name="password"
               type="password">
        <input type="submit" class="btn btn-lg btn-primary btn-block" name="login" value="Sign in"/>
    </form>
    <form class="form-signin">
        <input type="submit" name="register" class="btn btn-secondary btn-block" value="Sign up">
    </form>
</div>