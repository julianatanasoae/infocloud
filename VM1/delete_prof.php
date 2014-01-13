<?php
	require_once ("Includes/simplecms-config.php");
	require_once ("Includes/connectDB.php");
	require_once ("Includes/session.php");
	confirm_is_owner();
	
	$user = $_GET['user'];
	if (ctype_alnum($user))
	{
			$get_statement2 = $databaseConnection->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
			$get_statement2->bind_param('s', $user);
			$get_statement2->execute();
			$get_statement2->store_result();
			
			if ($get_statement2->error)
			{
				die("Database query failed: " . $get_statement2->error);
			}
			
			if ($get_statement2->num_rows == 1)
			{
				$get_statement2->bind_result($id);
				$get_statement2->fetch();
			}
			
			$query = "DELETE FROM users_in_roles WHERE user_id = ?";

			$statement = $databaseConnection->prepare($query);
			$statement->bind_param('d', $id);
			$statement->execute();
			$statement->store_result();

			$deleteWasSuccessful = $statement->affected_rows > 0 ? true : false;
			if ($deleteWasSuccessful)
			{
				$delete_query = "DELETE FROM users WHERE id = ?";
				$delete_statement = $databaseConnection->prepare($delete_query);
				$delete_statement->bind_param('d', $id);
				$delete_statement->execute();
				$delete_statement->store_result();
				
				$delete2_query = "DELETE FROM cereri_prof WHERE username = ?";
				$delete2_statement = $databaseConnection->prepare($delete2_query);
				$delete2_statement->bind_param('s', $user);
				$delete2_statement->execute();
				$delete2_statement->store_result();
				
				header ("Location: manage_profs.php");
			}
			else
			{
				header ("Location: index.php");
			}
	}
	else
	{
		header("Location: index.php");
	}
?>