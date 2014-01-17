<?php
require_once('database/db.php');
require_once('model/liaison.php');
require_once('model/user.php');

//Récupération des paramètres GET + chiffrement du numéro ciblé pour comparaison avec BDD
$parameters[":user1"] = $_GET["user1"];
$parameters[":user2"] = md5(intval($_GET["user2"]));

//Connexion à la BDD
$config = require_once('config.php');
$db = new DB($config['dsn'], $config['username'], $config['password'], $config['options']);

//On cherche en premier lieu si l'utilisateur a déja choisi quelqu'un auparavant
$first = $db->find('liaison', 'amour_liaison', 'user1 = :user1 AND user1 = :user1', $parameters);

if ($first == false)
{
	//Instanciation d'un nouvel objet "Liaison" + insertion
	$ln = new Liaison();
	$ln->user1 = $parameters[':user1'];
	$ln->user2 = $parameters[':user2'];
	$new_liaison = $db->insert($ln, 'amour_liaison');
	
	//On cherche dans la BDD si la liaison est réciproque
	$reciproque = $db->find('liaison', 'amour_liaison', 'user2 = :user1 AND user1 = :user2', $parameters);
	
	if($new_liaison !== false)
	{
		if ($reciproque !== false)
		{
			//On met à jour les entrées dans la table "users_information"
			$db->updateAmour($parameters[':user1']);
			$db->updateAmour($parameters[':user2']);
			$msg = "Cette personne t'a aussi désignée!";
		}
		else
			$msg = "Contact choisi! Cette personne ne t'a pas (encore) désigné!";
	}
	else
		$msg = "Problème lors du choix du contact2.";
}
else
	$msg = "Tu as déja choisi quelqu'un!";

$json = array('error' => false,'token' => $msg);
echo json_encode($json);