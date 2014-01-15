<?php
require_once('database/db.php');
require_once('model/user.php');

//Chiffement et salage du mdp
$passwd = md5($_GET["password"]."A13if26")."A13if26";

//Connexion à la BDD
$config = require_once('config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

//Instanciation d'un nouvel objet "User" + insertion
$user = new User();
$user->numero = md5(intval($_GET["numero"]));
$user->password = $passwd;
$user->in_love = 0;
$token = md5(time() . $user->numero . $user->password);

$id = $db->insert($user, 'users_information');

if($id !== false)
	$json = array('error' => false,'token' => $token);
else
	$json = array('error' => true);
	
echo json_encode($json);