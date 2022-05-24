<?php
session_start();
// $imetabele = $_SESSION["imetabele"];
include("elements/navbar.php");

if (isset($_POST["predmet"])) {
    $predmet = $_POST["predmet"];
    $_SESSION["predmet"] = $predmet;
    header("location: delete.php");
}
?>
<!DOCTYPE html5>
<html>

<head>
    <meta charset="utf-8" />
    <title>Osebna Redovalnica</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="elements\maincss.css">
    <link rel="stylesheet" href="redovalnica\bootstrap-3.3.7-dist\css\bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <script src="elements/main.js"></script>
</head>

<body>
    <div id="redovalnicabox">
        <table align="center">
            <tr>
                <td>
                    <div class="red_TEXT">PREDMET</div>
                </td>
                <td>
                    <div class="red_TEXT">OCENE</div>
                </td>
                <td>
                    <div class="red_TEXT">POVPREČJE</div>
                </td>
            </tr>
            <?php
            
            include_once("config.php");
            $skupnopovprecje = 0;
            $listocene = [];
            $datumi = [];
            $counter = 0;
            $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $username_for_select = $_SESSION["uid"];

            $username_query = $conn->query("SELECT id from information where uporabnisko_ime = '$username_for_select'");
            $username_arr = mysqli_fetch_row($username_query);
            $uid = $username_arr[0];


            $list_of_selected_predmeti_query = $conn->query("SELECT naziv from predmeti where UID = '$uid'");
            $list_of_selected_predmeti_arr = mysqli_fetch_all($list_of_selected_predmeti_query);

            $list_of_predmet_ocene_query = $conn->query("SELECT ocena from ocena inner join predmeti on ocena.uid = predmeti.uid");
            foreach ($list_of_selected_predmeti_arr as $predmet_arr_array) {
                foreach ($predmet_arr_array as $predmet_array_element) {
                    if ($predmet_array_element != "") {

                        //acquire pid
                        $pid_query = $conn->query("SELECT PID from predmeti where naziv = '$predmet_array_element' AND UID = '$uid'");
                        $pid_arr = mysqli_fetch_row($pid_query);
                        $pid = $pid_arr[0];

                        //acquire all ocene for pid
                        $ocene_query = $conn->query("SELECT ocena from ocena where PID = $pid");
                        $ocene_arr = mysqli_fetch_all($ocene_query);


                        $datumQ = $conn->query("SELECT datum from ocena where PID = $pid");
                        $datumArr = mysqli_fetch_all($datumQ);

                        $alloceneQ = $conn->query("SELECT ocena from ocena where UID = $uid");
                        $alloceneArr = mysqli_fetch_all($alloceneQ);

                        $oceneforpid = mysqli_fetch_all($conn->query("SELECT ocena from ocena where PID = $pid"));

                        if (count($alloceneArr) == null) {
                            echo "<tr><td colspan='3'><div class= 'red_TEXT'>NI ŠE OCEN!</div></td></tr>";
                            die();
                        }

                        $rgbColor = mt_rand(150, 240) . ", " . mt_rand(150, 240) . ", " . mt_rand(150, 240);
                        $rgbColor = "rgb(" . $rgbColor . ")";


                        //echo all ocene and predmeti into td
                        $kraticapredmetARR = explode(" ", $predmet_array_element);
                        $kratica = "";
                        if (count($kraticapredmetARR) > 1) {
                            foreach ($kraticapredmetARR as $predmetarrelement) {
                                $kratica .= $predmetarrelement[0];
                            }

                            echo
                                '<tr>
                            <td>
                            <div class= "red_TEXT"><span data-toggle= "tooltip" data-trigger= "hover focus" data-placement="bottom" title="' . $predmet_array_element . '" class= "red_button" style="color:' . $rgbColor . '">' . $kratica . '</span></form></div>
                            </td>
                            ';
                        } else {

                            echo
                                '<tr>
                            <td>
                            <div class= "red_TEXT"><span class= "red_button" style="color:' . $rgbColor . '">' . $predmet_array_element . '</span></form></div>
                            </td>
                            ';
                        }
                        $povprecje = 0;
                        $stevec = 0;
                        foreach ($ocene_arr as $o) {

                            foreach ($o as $oc) {
                                $listocene[] = $oc;
                                $povprecje += $oc;
                                $stevec += 1;
                            }
                        }

                        $oidQ = $conn->query("SELECT OID from ocena WHERE PID = $pid");
                        $oidArr = mysqli_fetch_all($oidQ);

                        foreach ($datumArr as $dat) {
                            foreach ($dat as $datum) {
                                $string = "";
                                $datum = str_replace("-", ".", $datum);
                                $datar = explode(".", $datum);
                                foreach ($datar as $newdat) {
                                    $string = $newdat . "." . $string;
                                }
                                $datumi[] = rtrim($string, ".");
                            }
                        }
                        echo '<td><div class= "red_TEXT">';


                        for ($for = 0; $for < count($listocene); $for++) {
                            if ($listocene[$for] == "1") {
                                echo '<a class="ocena ocena_red" data-toggle= "tooltip" data-placement="bottom" title="' . $datumi[$for] . '">';
                            } else if ($listocene[$for] == "5") {
                                echo '<a class="ocena ocena_green" data-toggle= "tooltip" data-placement="bottom" title="' . $datumi[$for] . '">';
                            } else if ($listocene[$for] == "4") {
                                echo '<a class="ocena ocena_yelgre" data-toggle= "tooltip" data-trigger= "hover focus" data-placement="bottom" title="' . $datumi[$for] . '">';
                            } else if ($listocene[$for] == "3") {
                                echo '<a class="ocena ocena_yellow" data-toggle= "tooltip" data-trigger= "hover focus" data-placement="bottom" title="' . $datumi[$for] . '">';
                            } else if ($listocene[$for] == "2") {
                                echo '<a class="ocena ocena_orared" data-toggle= "tooltip" data-trigger= "hover focus" data-placement="bottom" title="' . $datumi[$for] . '">';
                            }
                            if ($for == count($listocene) - 1) {
                                echo $listocene[$for];
                            } else {
                                echo $listocene[$for] . "</a>, ";
                            }
                        }
                        echo '</a></div></td>';
                        if ($povprecje == 0) {
                            $povprecje = "";
                        } else {
                            $povprecje = $povprecje / $stevec;
                            $povprecje = round($povprecje, 2);
                            $counter += 1;

                            $skupnopovprecje += $povprecje;
                        }
                        echo '<td><div class= "red_TEXT"><span class="ocena ocena_povp" style="color:' . $rgbColor . '">' . $povprecje . '</span></div></td>';
                        echo '<td><form method= "post"><button type= "submit" name= "predmet" value= "' . strtoupper($predmet_array_element) . '" class= "headerdiv_logout_thin">-></button></td></tr>';


                        $listocene = [];
                        $datumi = [];
                    }
                }
            }
            echo "<tr><td><br></td></tr>";
            $rgbColor = mt_rand(150, 240) . ", " . mt_rand(150, 240) . ", " . mt_rand(150, 240);
            $rgbColor = "rgb(" . $rgbColor . ")";
            if ($counter > 0) {
                echo '<tr>
                    <td colspan= "2"><div class= "red_TEXT"><span class= "red_button">SKUPNO</span></div></td>';
                echo '
                    <td><div class= "red_TEXT"><span class= "ocena ocena_povp" style= "color: ' . $rgbColor . '">' . round($skupnopovprecje / $counter, 1) . '</div></td>
                    </tr>';
            }
            ?>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>