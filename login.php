<?php
require_once('database/db.php');
require_once('model/user.php');

//Récupération des paramètres GET + chiffrement et salage du mdp pour comparaison
$parameters[":password"] = md5($_GET["password"]."A13if26")."A13if26";
$parameters[":numero"] = md5(intval($_GET["numero"]));

//Connexion à la BDD
$config = require_once('config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

//Vérification des informations de login (recherche dans la BDD)
$user = $db->find('user', 'users_information', 'numero = :numero AND password = :password', $parameters);

if($user !== false)
{
	if ($user->in_love == 1)
		$json = array('error' => false,'token' => "1");
	else
		$json = array('error' => false,'token' => $user->numero);
}
else
{
	$json = array('error' => true);
	usleep(500000);
}
	
echo json_encode($json);