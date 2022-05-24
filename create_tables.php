<?php
    // $con = new mysqli("localhost", "groselj", "Drekec123", "osebna_redovalnica_groselj");
    include_once("config.php");
    $precon = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    $precon ->query("CREATE DATABASE IF NOT EXISTS DB_NAME");
    mysqli_close($precon);

    $con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    for ($i = 0; $i < 3; $i++){
        
        if ($i == 0){
            $con->query("CREATE TABLE IF NOT EXISTS `information` ( 
                `ime` TEXT NOT NULL , 
            `priimek` TEXT NOT NULL , 
            `email` VARCHAR(255) NOT NULL , 
            `uporabnisko_ime` VARCHAR(16) NOT NULL , 
            `geslo` VARCHAR(255) NOT NULL , 
            `ID` INT NOT NULL AUTO_INCREMENT ,
            `potrditev` BOOLEAN NOT NULL DEFAULT FALSE ,
            PRIMARY KEY (`ID`),
            UNIQUE (`uporabnisko_ime`), 
            UNIQUE (`email`)) 
            ENGINE = InnoDB;");  
        }
        if ($i == 2){
            $con->query("CREATE TABLE IF NOT EXISTS `ocena` ( 
                `UID` INT NOT NULL,
            `PID` INT NOT NULL,
            `OID` INT NOT NULL AUTO_INCREMENT , 
            `ocena` INT NOT NULL , `datum` DATE NOT NULL , 
            `time` TIME NOT NULL , 
            FOREIGN KEY (`UID`) REFERENCES `information`(`ID`) ON DELETE CASCADE, 
            FOREIGN KEY (`PID`) REFERENCES `predmeti`(`PID`) ON DELETE CASCADE, 
            PRIMARY KEY (`OID`), 
            INDEX (`PID`), 
            INDEX (`UID`)) 
            ENGINE = InnoDB;");
        }
        if ($i == 1){
            $con->query("CREATE TABLE IF NOT EXISTS `predmeti` ( 
                `naziv` TEXT NOT NULL , 
            `kratica` TEXT NOT NULL , 
            `UID` INT NOT NULL, 
            `PID` INT NOT NULL AUTO_INCREMENT, 
            FOREIGN KEY (`UID`) REFERENCES `information`(`ID`) ON DELETE CASCADE,
            PRIMARY KEY (`PID`), 
            INDEX (`UID`)) 
            ENGINE = InnoDB;");
        }
        $con->query("CREATE TABLE IF NOT EXISTS `potrditev`(
            `ID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `code` INT(8) NOT NULL,
            `UID` INT NOT NULL,
            UNIQUE(`code`))
            ENGINE = InnoDB;");
        echo(mysqli_error($con));
    }
?>