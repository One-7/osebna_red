<?php
include_once("config.php");
session_start();
if(isset($_SESSION["imetabele"])){
$imetabele = $_SESSION["imetabele"];
}
$username_for_select = $_SESSION["uid"];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["submit_info"])){

        //create connection
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        //check connection
        if (mysqli_connect_errno()){
            die("Connection failed: " . mysqli_connect_error());
        }
        else if (mysqli_error($conn)){
            die(mysqli_error($conn));
        }


        // $_POST variable values
        $ocena = $_POST["ocena"];
        $predmet = $_POST["select_predmet"];

        //select uid
        $uid_arr = $conn->query("SELECT ID from information where uporabnisko_ime = '$username_for_select'");
        $uid_new_arr = mysqli_fetch_array($uid_arr);
        $uid = $uid_new_arr[0];
        //select pid
        $pid_arr = $conn->query("SELECT PID from predmeti where UID = '$uid' and kratica = '$predmet'");
        $pid_new_arr = mysqli_fetch_array($pid_arr);
        $pid = $pid_new_arr[0];
        // die($pid);

        
        
        if ((empty($ocena)) || ($predmet = "0" )){
            echo "
                <script type='text/javascript'>
                    alert('Vpis ocene neuspešen! Nič nesme biti prazno.');
                    window.location = 'uredi.php';
                </script>";
        }
        else if(($ocena > 5) || ($ocena < 1)){
            header("Refresh: 2; uredi.php");
            die("NAPAKA PRI VPISU OCENE!");}
        else{

            $date_for_sql = date("Y-m-d");
            $time_for_sql = date("H:i:s");
            $conn->query("INSERT INTO `ocena` VALUES('$uid', '$pid', 0, '$ocena', '$date_for_sql', '$time_for_sql')");
            $randomdiepreventvar = mysqli_error($conn);
            if(strlen($randomdiepreventvar) > 0){
                die($randomdiepreventvar);
            }
            header("location: ocenavpisana.php");
            mysqli_close($conn);
        }
    }
}
include("elements/navbar.php");
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

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->

</head>
<body>
    <div class= "div_table">
    <form method= "post" class= "urediFORM">
        <div class= "form_inputs">
            <input type= "text" placeholder= "OCENA" name= "ocena" class= "actual_form_inputs">
        </div>

        <div class= "form_inputs">
            <select name="select_predmet" class= "form_select">
                <option value= "">Prosim izberite predmet.</option>
                <?php
                    include_once("config.php");
                    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                
                    $uid_arr = $conn->query("SELECT ID from information where uporabnisko_ime = '$username_for_select'");
                    $uid_new_arr = mysqli_fetch_array($uid_arr);
                    $uid = $uid_new_arr[0];

                    $predmeti_query = $conn->query('SELECT kratica from predmeti where UID = '.$uid);
                    $kratice_arr = mysqli_fetch_all($predmeti_query);
                    $list=[];

                    foreach($kratice_arr as $kratica_arr2){
                        foreach($kratica_arr2 as $kratica){
                        $list[] = $kratica;
                        }
                        // echo '<option value="'.$kratica.'">'.$kratica.'</option>';
                    }
                    for($o = 0; $o < count($list); $o++){
                        echo '<option value= "' . $list[$o] . '">' . $list[$o] . '</option>';
                    }
                    // echo '<option value= "">' . $kratica . '</option>';
                ?>
            </select>
        </div>

        <div class= "form_inputs">
            <input type= "submit" name= "submit_info" class= "actual_form_submit" value= "VPIŠI OCENO">
        </div>
    </form>
    </div>

</body>
</html>     