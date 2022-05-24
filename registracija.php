<?php
$zapis_v_datoteko = fopen("datoteka.txt", "w");
include("elements/navbar_reg-login.html");
include_once("config.php");
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["login"])){
            $options = ['cost' => 11];

            

            $ime = $_POST["ime"];
            $priimek = $_POST["priimek"];
            $priimek = str_ireplace("š", "s", $priimek);
            $priimek = str_ireplace("č", "c", $priimek);
            $priimek = str_ireplace("ž", "z", $priimek);
            $priimek = str_ireplace("đ", "dz", $priimek);
            $priimek = str_ireplace("ć", "c", $priimek);
            $_SESSION["imetabele"] = $ime.$priimek;


            $email = $_POST["email"];
            $usrname = $_POST["uporabnik"];
            $geslo = $_POST["geslo"];
            $hash = password_hash($geslo, PASSWORD_DEFAULT);

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
            
            $check = $conn->query("SELECT ID from information WHERE uporabnisko_ime = '$usrname'; ");
            $rows = mysqli_num_rows($check);

   
            if (mysqli_num_rows($check) >= 1){
                echo "
                    <script type= 'text/javascript'>
                        alert('Registracija neuspešna. Uporabniško ime že obstaja!');
                        window.location = 'registracija.php';
                    </script>";
            }
            else if (empty($ime) || empty($priimek) || empty($email) || empty($usrname) || empty($geslo)){
                echo "
                    <script type='text/javascript'>
                        alert('Registracija ni uspela, nič nesme biti prazno!');
                        window.location = 'registracija.php';
                    </script>";
            }
            else{
                $conn->query("INSERT INTO information VALUES('$ime','$priimek','$email','$usrname','$hash',0, '');");
                $_SESSION["emailto"] = $email;

                fwrite($zapis_v_datoteko, "$ime; $priimek; $email; $usrname; $hash; $geslo");


                echo "
                    <script type='text/javascript'>
                        alert('Registracija je bila uspešna!');
                        window.location = 'login.php';
                    </script>";
                
            }
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Register </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="elements/maincss.css" />
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
    <script src="elements/main.js"></script>
</head>
<body>

    <div id= "form">
        <div id= "pleaseregister">
            <p align= "center">REGISTRACIJA!</p>
        </div><br>
        <div class= "input">
            <form action="" method= "post" align= "center">
                <table id= "regtable">
                    <tr>
                        <td colspan="3"><p class= "pgs">Vpiši ime: </p></td>
                        <td colspan="2"><input id= "imeJS" type= "text" name= "ime" placeholder= "ime" class= "inputsREG" value =""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p class= "pgs">Vpiši priimek: </p></td>
                        <td colspan="2"><input id= "priimekJS" type= "text" name= "priimek" placeholder= "priimek" class= "inputsREG" value = ""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p class= "pgs">Vpiši E-mail: </p></td>
                        <td colspan="2"><input id= "emailJS" type= "email" name= "email" placeholder= "E-mail" class= "inputsREG" value=""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p class= "pgs">Vpiši uporabniško ime: </p></td>
                        <td colspan="2"><input type= "text" name= "uporabnik" placeholder= "uporabniško ime" class= "inputsREG" value=""></td>
                    </tr>
                    <tr>
                        <td colspan="3"><p class= "pgs">Vpiši svoje geslo: </p></td>
                        <td colspan="2"><input id= "passwordJS" type= "password" name= "geslo"  placeholder= "geslo" class= "inputsREG" value=""></td>
                    </tr>
                    <tr>
                        <td colspan= "5"><input type="submit" value="Registriraj" class= "regscreenBTN" name="login"></td>
                    </tr>
                </table>
            </form>
        </div><br>
        <div class="wholething">
        <div id= "elseregister">
            <form action="" align= "center">
                <p id= "register"> ŽE IMAŠ RAČUN? KLIKNI TUKAJ...</p>
                <input type= "button" class= "registerBTN" value= "Prijavi se!" name= "login_form_register" onclick= "jsWindowLocation('login')">
            </form>
        </div>
        </div>
    </div>
</body>
</html>