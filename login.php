<?php
    include("create_tables.php");
    include_once("config.php");
    include("elements/navbar_reg-login.html");
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["login"])){

            $usrname = $_POST["ime"];
            // $usrname = strtolower($usrname);
            $psword = $_POST["geslo"];

            $servername = DB_SERVER;
            $username = DB_USERNAME;
            $password = DB_PASSWORD;
            $database = DB_NAME;

            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $ime = $conn->query("SELECT ime from information WHERE uporabnisko_ime = '$usrname'");
            $priimek = $conn->query("SELECT priimek from information WHERE uporabnisko_ime = '$usrname'");
            $imeR = mysqli_fetch_array($ime);
            $prrimR = mysqli_fetch_array($priimek);
            $imeA = $imeR[0];
            $priA = $prrimR[0];

            $potrditev = mysqli_fetch_row($conn->query("SELECT potrditev from information WHERE uporabnisko_ime = '$usrname'"))[0];
            $result = $conn->query("SELECT uporabnisko_ime from information WHERE uporabnisko_ime = '$usrname'");
            $result2 = $conn->query("SELECT geslo from information WHERE uporabnisko_ime = '$usrname'");
            $row = mysqli_fetch_array($result);
            $row2 = mysqli_fetch_array($result2);
            $actualResult = $row[0];
            // $actualResult = strtolower($actualResult);
            $actualResult2 = $row2[0];
            
            if(empty($usrname) && empty($psword)){
                    echo "
                    <script type= 'text/javascript'>
                        alert('Login failed. Username or password can not be empty!');
                        window.location = 'login.php';
                    </script>";
            }
            else if (empty($usrname) || empty($psword)){
                echo "
                    <script type= 'text/javascript'>
                        alert('Login failed. Username or password can not be empty!');
                        window.location = 'login.php';
                    </script>";
            }
            
            else if (($usrname == $actualResult)){
                if(password_verify($psword, $actualResult2)){
                    $_SESSION["ime"] = $imeA;
                    $_SESSION["priimek"] = $priA;
                    
                    //username gets stored as a $_SESSION variable...
                    $_SESSION["uid"] = $usrname;

                    $_SESSION["directAccess"] = true;
                    
                    header("Location:index.php");
                }
            else {
                // die($actualResult);
                echo "
                    <script type= 'text/javascript'>
                        alert('Login failed. User does not exist or the password is not correct!');
                        window.location = 'login.php';
                    </script>";
            }
        }
        }
    }


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Login </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="elements/maincss.css">
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
    <script src="elements/main.js"></script>
</head>
<body>
    
    <div class= "wholething">
        <div id= "pleaselogin">
            <p>PROSIM PRIJAVITE SE!</p>
        </div><br>
        <div class= "input">
            <form action="" method= "post" align= "center">
                <input align= "center" type= "text" name= "ime" placeholder="uporabniško ime" class= "inputs"><br>
                <input align= "center" type= "password" name= "geslo"  placeholder="geslo" class="inputs" ><br/>
                <input type="submit" value="VPIŠI ME!" name= "login" class= "registerBTN">
            </form>
        </div><br>
        <div id= "elseregister">
            <form action="" align= "center">
                <p id= "register"> NIMAŠ RAČUNA? KLIKNI TUKAJ...</p>
                <input type= "button" class= "registerBTN" value= "Registriraj me!" name= "login_form_register" onclick= "jsWindowLocation('registracija')">
            </form>
        </div>
    </div>
</body>
</html>