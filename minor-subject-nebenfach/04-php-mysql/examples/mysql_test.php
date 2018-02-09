<?php
require_once ("connectionInfo.private.php");
$c = mysqli_connect("localhost","mmn",$connectionInfo['password']);

if($c){
    echo "Connection has been successfully established";
}
else{
    echo "Could NOT connect to database";
}

?>