<?php
    session_start();
    require_once  ("Includes/connectDB.php");

    function logged_on()
    {
        return isset($_SESSION['userid']);
    }

    function confirm_is_admin() {
        if (!logged_on())
        {
            header ("Location: logon.php");
        }

        if (!is_admin())
        {
            header ("Location: index.php");
        }
    }
	
	function confirm_is_owner() {
        if (!logged_on())
        {
            header ("Location: logon.php");
        }

        if (!is_owner())
        {
            header ("Location: index.php");
        }
    }
	
	function confirm_is_logged_in() {
		if(!logged_on())
		{
			header ("Location: logon.php");
		}
	}

    function is_admin()
    {
        global $databaseConnection;
        $query = "SELECT user_id FROM users_in_roles UIR INNER JOIN roles R on UIR.role_id = R.id WHERE R.name = 'admin' AND UIR.user_id = ? LIMIT 1";
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('d', $_SESSION['userid']);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows == 1;
    }
	
	function is_owner()
	{
		global $databaseConnection;
        $query = "SELECT user_id FROM users_in_roles UIR INNER JOIN roles R on UIR.role_id = R.id WHERE R.name = 'owner' AND UIR.user_id = ? LIMIT 1";
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('d', $_SESSION['userid']);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows == 1;
	}
?>