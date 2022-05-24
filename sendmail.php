<?php
session_start();
    $to = $_SESSION["emailto"];
    $subject = "Potrditev registracije!";
    $from = "From: noreply.redovalnica@gmail.com";

    $st = rand(10000000, 99999999);

    $message = "Prosim potrdite vašo registracijo z klikom na link: \n http://localhost/osebna_red/osebna_red/confirm.php/?code=$st ! \n Hvala.";

    $con = new mysqli("localhost", "random", "", "users");
    $uid = $con->query("SELECT ID from information WHERE email = '$to'");
    $uid = mysqli_fetch_row($uid)[0];

    $con->query("INSERT INTO potrditev VALUES(0, $st, $uid)");

    $_SESSION["code"] = $st;

    mail($to, $subject, $message, $from);
    // header("refresh: 2; location: login.php");
    die("please confirm your email!");
?>