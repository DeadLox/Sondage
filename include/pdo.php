<?php
$hote  = "localhost";
$base  = "form";
$login = "root";
$mdp   = "";
try {
	$bdd = new PDO('mysql:localhost='. $hote .';dbname='. $base, $login, $mdp);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->exec("SET CHARACTER SET utf8");
} catch (Exception $e) {
	die('Erreur : '. $e->getMessage());
}
?>