<?php
	require_once ("Includes/simplecms-config.php");
	require_once ("Includes/connectDB.php");
	require_once ("Includes/session.php");
	confirm_is_owner();
	
	$id = $_GET['id'];
	if (is_numeric($id))
	{
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
				
				header ("Location: manage_users.php");
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