<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		$este_valid = 1;
		
		function verify($variabila)
		{
			if ($variabila == null || $variabila == '')
				return false;
			else
			{
				if(strstr($variabila, '<'))
					return false;
				if(strstr($variabila, '>'))
					return false;
				if(strstr($variabila, '"'))
					return false;
				if(strstr($variabila, '\''))
					return false;
				if(strstr($variabila, '='))
					return false;
				if(strstr($variabila, ' '))
					return false;
				if(strstr($variabila, ';'))
					return false;
				if(strstr($variabila, '-'))
					return false;
				if(strstr($variabila, '/'))
					return false;
				if(strstr($variabila, '\\'))
					return false;
				if(strstr($variabila, '`'))
					return false;
			}
			return true;
		}
		
		if (isset($_POST['submit']))
    {
        
		
		if (verify($_POST['username']) == false)
				{
					$este_valid = 0;
					//echo "<br/><br/>Username invalid";
				}
			if (verify($_POST['password']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					//echo "<br/><br/>Parolă invalidă";
				}
		
		if ($este_valid ==1)
				{
		
					$username = $_POST['username'];
					$password = $_POST['password'];	
							
							
					$query = "SELECT id, username, clasa FROM users WHERE username = ? AND password = SHA(?) LIMIT 1";
					$statement = $databaseConnection->prepare($query);
					$statement->bind_param('ss', $username, $password);

					$statement->execute();
					$statement->store_result();

					if ($statement->num_rows == 1)
					{
						$statement->bind_result($_SESSION['userid'], $_SESSION['username'], $_SESSION['clasa']);
						$statement->fetch();
						header ("Location: index.php");
					}
					else
					{
						echo "<br/><br/>Username/parolă incorecte.";
					}					
		}
        else
        {
            echo "<br/><br/>Username/parolă incorecte.";
        }
    }
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<form action="logon.php" method="post">
            <fieldset>
            <legend>Login</legend>
            <ul type="none">
                <li>
                    <label for="username">Nume utilizator:</label> 
                    <input type="text" name="username" value="" id="username" />
                </li>
                <li>
                    <label for="password">Parolă:</label>
                    <input type="password" name="password" value="" id="password" />
                </li>
            </ul>
            <input type="submit" name="submit" value="Login" />
            <p>
                <a href="index.php">Anulare</a>
            </p>
        </fieldset>
    </form>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>