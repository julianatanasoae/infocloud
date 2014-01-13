<?php
	require_once ("Includes/simplecms-config.php");
	require_once ("Includes/connectDB.php");
	require_once ("Includes/session.php");
	confirm_is_owner();
	
	$id = $_GET['id'];
	if (is_numeric($id))
	{
		$get_statement = $databaseConnection->prepare("SELECT username, password, nume, prenume, scoala, localitate, judet FROM cereri_prof WHERE id = ? LIMIT 1");
		$get_statement->bind_param('d', $id);
		$get_statement->execute();
		$get_statement->store_result();

		if($get_statement->error)
		{
			die("Database query failed: " . $get_statement->error);
		}

		$get_statement->bind_result($username, $password, $nume, $prenume, $scoala, $localitate, $judet);
		$get_statement->fetch();
		
		$query = "INSERT INTO users (username, password, puncte, nume, prenume, scoala, localitate, judet) VALUES (?, ?, 0, ?, ?, ?, ?, ?)";

		$statement = $databaseConnection->prepare($query);
		$statement->bind_param('sssssss', $username, $password, $nume, $prenume, $scoala, $localitate, $judet);
		$statement->execute();
		$statement->store_result();

		$creationWasSuccessful = $statement->affected_rows == 1 ? true : false;
		if ($creationWasSuccessful)
		{
			$userId = $statement->insert_id;

			$addToUserRoleQuery = "INSERT INTO users_in_roles (user_id, role_id) VALUES (?, ?)";
			$addUserToUserRoleStatement = $databaseConnection->prepare($addToUserRoleQuery);

			// TODO: Extract magic number for the 'user' role ID.
			$userRoleId = 1;
			$addUserToUserRoleStatement->bind_param('dd', $userId, $userRoleId);
			$addUserToUserRoleStatement->execute();
			$addUserToUserRoleStatement->close();
			header ("Location: manage_profs.php");
		}
		else
		{
			echo "<br/><br/>Eroare la înregistrare";
		}
	}
	else
	{
		header("Location: index.php");
	}
?>