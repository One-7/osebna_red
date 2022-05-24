<?php
include_once("config.php");
session_start();
include("elements/navbar.php");


if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["submit_info"])){

        $servername = DB_SERVER;
        $username = DB_USERNAME;
        $password = DB_PASSWORD;
        $database = DB_NAME;
        
        $conn = mysqli_connect($servername, $username, $password, $database);

        $predmet = $_POST["predmet"];

        $username_for_select = $_SESSION["uid"];

        $uid_arr = $conn->query("SELECT ID from information where uporabnisko_ime = '$username_for_select'");
        $uid_new_arr = mysqli_fetch_array($uid_arr);
        $uid = $uid_new_arr[0];

        //dolocimo kratico s for stavkom
        $kratica= "";
        $counter= 0;
        for($p = 0; $p < strlen($predmet); $p++){
            if($p == 0){
                $kratica .= $predmet[$p];
            }
            if($predmet[$p] == " "){
                $kratica .= $predmet[$p+1];
                $counter += 1;
            }
        }
        if($counter == 0){
            for($x = 1; $x < 3; $x++){
                $kratica .= $predmet[$x];
            }
        }
        //preverimo s funkcijo str_ireplace{"ščž", "scz", $string] -> obstaja tudi str_replace, samo da je ta case senstive ireplace pa ne

        $predmet = str_ireplace("š", "s", $predmet);
        $predmet = str_ireplace("č", "c", $predmet);
        $predmet = str_ireplace("ž", "z", $predmet);
        $predmet = str_ireplace("đ", "dz", $predmet);
        $predmet = str_ireplace("ć", "c", $predmet);

        $conn->query("INSERT INTO `predmeti` VALUES('$predmet','$kratica','$uid' ,0)");

    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Osebna Redovalnica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="elements/maincss.css" />
    <script src="elements/main.js"></script>
</head>
<body>
    <div class= "div_table">
    <form method= "post" class= "urediFORM">
        <div class= "form_inputs">
            <input type= "text" placeholder= "Prosim vnesi celotno ime predmeta." name= "predmet" class= "actual_form_inputs" >
        </div>
        <div class= "form_inputs">
            <input type= "submit" name= "submit_info" class= "actual_form_submit" value= "DODAJ PREDMET">
        </div>
    </form>
</div>
</body>
</html>