<?php

session_start();
$_SESSION['login'] = "yes";
$_SESSION['loggeduser'] = "Admin";

/* check logged session */
if (!isset($_SESSION['login'])) {
    if (basename($_SERVER['PHP_SELF']) != "login.php") {
        header("Location: ../login.php");
        exit;
    }
}

//echo 'Time zone is :'.$_GET['timezone'];
//Settig the TimeZone Coocki
$coockie = '';
if (!isset($_COOKIE['timezone'])) {
    if (isset($_GET['timezone'])) {
        $timez = $_GET['timezone'];
        setcookie('timezone', $timez);
        // echo ' Cocki  set , setting second';
        $coockie = $_COOKIE['timezone'];
    } else {
        setcookie('timezone', '0.0');
        $coockie = $_COOKIE['timezone'];
    }
    //  echo ' Cocki not set , setting initially';
} else {
    if (isset($_GET['timezone'])) {
        $timez = $_GET['timezone'];
        setcookie('timezone', $timez);
        $coockie = $_COOKIE['timezone'];
        // echo ' Cocki  set , setting second';
    } else {
        setcookie('timezone', '0.0');
        $coockie = $_COOKIE['timezone'];
    }
}
//echo 'sdfkjdhfvg';
//echo 'Time zone is :'.$_GET['timezone'];
//include("../includes/dbconfig.php");	 //
$dbcon = new PDO('mysql:host=localhost;port=3306;dbname=visitortracking', 'root', 'root', array(PDO::ATTR_PERSISTENT => false));

function getFriendlyIP() {
    $temp = '';
    global $dbcon;
    // $dbcon = new PDO('mysql:host=localhost;port=3306;dbname=visitortracking', 'root', '', array(PDO::ATTR_PERSISTENT => false));
    $query = "SELECT `ipaddress` FROM `friendly_ip`";
    try {
        $stmtP = $dbcon->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmtP->execute();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    if ($stmtP->rowCount() > 0) {
        $freindly_ips = $stmtP->fetchALL(PDO::FETCH_ASSOC);

        foreach ($freindly_ips as $ip) {
            //print_r($ip);
            $temp .= "'" . $ip['ipaddress'] . "',";
            //  echo "<option " . $selectedP . "  value='" . $aData["id"] . "'>" . $aData["page_name"] . "</option>";
        }
        $temp = substr($temp, 0, strlen($temp) - 1);
    }
    return $temp;
}

getFriendlyIP();
?>