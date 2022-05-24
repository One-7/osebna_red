<?php
$code = $_GET["code"];
$con = new mysqli("localhost", "random", "", "users");

$a = mysqli_fetch_row($con->query("SELECT code from potrditev WHERE code = $code"))[0];
$b = mysqli_fetch_row($con->query("SELECT UID from potrditev WHERE code = $code"))[0];
// die($b);

if($a == NULL){
    echo "ups nekaj smrdi";
}
else{
    $con->query("UPDATE `information` SET `potrditev` = 1 WHERE `ID` = $b)");
    echo "uspesno";
    header("refresh: 1; location: login.php");
}

?>