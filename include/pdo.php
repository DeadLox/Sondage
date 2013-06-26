<?php
$hote  = "localhost";
$base  = "form";
$login = "root";
$mdp   = "root";
$port  = 8888;
try {
	$bdd = new PDO('mysql:localhost='. $hote .';port='.$port.';dbname='. $base, $login, $mdp);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->exec("SET CHARACTER SET utf8");
} catch (Exception $e) {
	die('Erreur : '. $e->getMessage());
}
?>