<?php
include_once("config.php");
session_start();
$predmet = $_SESSION["predmet"];
$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $uidquery = $con->query("SELECT ID from information where uporabnisko_ime = '". $_SESSION["uid"]."'");
    $uid = mysqli_fetch_row($uidquery)[0];

    $pid_query = $con->query("SELECT PID from predmeti where naziv = '$predmet' AND UID = '$uid'");
    $pid_arr = mysqli_fetch_row($pid_query);
    $pid = $pid_arr[0];
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["potrdi"])){
            if(isset($_POST["radio"])){
            $theOID = $_POST["radio"];
            if(isset($theOID)){
                $con->query("DELETE from ocena WHERE OID = '$theOID'");
                header("location: delete.php");
                }
            }
        elseif (isset($_POST["radiouredi"])) {
            $theOIDuredi = $_POST["radiouredi"];
            $novaocena = $_POST["novaocena"];
            if(isset($theOIDuredi)){
                $con->query("UPDATE ocena SET ocena = '$novaocena' WHERE OID = '$theOIDuredi'");
                header("location: index.php");
                }
            }
        else{
            header("location: index.php");
        }
        }
        if (isset($_POST["izbris*"])){
            $con->query("DELETE from ocena WHERE PID = $pid");
            }
        if (isset($_POST["izbris**"])){
            $con->query("DELETE from ocena WHERE UID = $uid");
            }
        if(isset($_POST["nazaj"])){
            header("location:index.php");
            }
        if(isset($_POST["del_predmet"])){
            $con->query("DELETE from predmeti where PID = '$pid' AND UID = '$uid'");
            header("location: index.php");
        }

    }
    
include("elements/navbar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uredi ocene</title>
    <link rel="stylesheet" type="text/css" media="screen" href="elements\maincss.css">

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <script src="elements/main.js"></script>
</head>
<body>
    <script>
        function show() {
            if(document.getElementById("unhidethis").style.display == "block") {
                document.getElementById("unhidethis").style.display = "none";
            }
            else {
                document.getElementById("unhidethis").style.display = "block";
            }
        }
    </script>

    <table align= "center"><tr><td><div class= "red_TEXT_thin"><span><?php echo $predmet; ?></span></div></td></tr></table><br>
    <div id= "redovalnicabox">
    <table align= "center">
        <tr>
            <td>
                <div class="red_TEXT_thin">OID</div>
            </td>
            <td>
                <div class= "red_TEXT_thin">OCENA</div>
            </td>
            <td>
                <div class= "red_TEXT_thin">DATUM</div>
            </td>
            <td>
                <div class= "red_TEXT_thin">POPRAVI</div>
            </td>
            <td>
                <div class= "red_TEXT_thin">ZBRIŠI</div>
            </td>
        </tr>
        <form method ='post'>
        <?php
            $oidarrarr = $con->query("SELECT OID from ocena WHERE PID = '$pid' ");
            $oidarr = mysqli_fetch_all($oidarrarr);
            
            foreach($oidarr as $index){
                foreach($index as $oid){
                echo "<tr><td><div class = 'red_TEXT_thin' value= '". $oid . "'>". $oid ."</div></td>";

                $ocenaarr = $con->query("SELECT ocena from ocena WHERE OID = '$oid'");
                $ocena = mysqli_fetch_array($ocenaarr)[0];
                echo "<td><div class = 'red_TEXT_thin'>". $ocena ."</div></td>";
                

                $datumarr = $con->query("SELECT datum from ocena WHERE OID = '$oid'");
                $datum = mysqli_fetch_array($datumarr)[0];
                echo "<td><div class = 'red_TEXT_thin'> ". $datum ."</div></td>";

                echo "
                <td><div class= 'red_TEXT_thin'>
                <input type='checkbox' class= 'headerdiv_thin' id='checkuredi'  onclick= 'show()' name= 'radiouredi' value= '" . $oid . "'>
                </div></td>
                
                <td><div class= 'red_TEXT_thin'>
                <input type='checkbox' class= 'headerdiv_thin' id='checkremove' name= 'radio' value= '". $oid ."'>
                </div></td>
                </tr>";
            }
        }
        ?>

        <tr><td colspan= '5'><div class='bottom_line'></div><br></td></tr>

        <tr>
            <td>
                <div class = 'red_TEXT_thin_hid' id='unhidethis'>
                    <input name='novaocena' onfocus="nameofinput('novaocena', 'potrdi')" type='text' placeholder='POPRAVI OCENO' class ='red_TEXT_thin_ocena'>
                </div>
            </td>
        <td></td>
        <td></td>
    </td>
</td>
<td></td>
<td><input type='submit' class= 'red_TEXT_thin_button' name= 'potrdi' id = 'potrdi' value='POTRDI'></td>
</tr>
<tr>
    <td><input type="submit" style="margin-top:3px;" class= "red_TEXT_thin_button" name= "nazaj" value= "NAZAJ"></td>
    <td></td>
    <td></td>
    
    <td><input type="submit" style="width: 248px" class= "red_TEXT_thin_button" name= "del_predmet" value= "ODSTRANI PREDMET"></td>
</tr>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>
        <div>
            <button type="submit" style="width: 248px; margin-top: 3px;" class= "red_TEXT_thin_button" name= "izbris*">Izbriši ocene tega predmeta.</button>
        </div>

        </table>
    </div>
</form>
</body>
</html>