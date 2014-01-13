<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		$este_valid = 1;
		
		function verify_password($variabila)
		{
			if ($variabila == null || $variabila == '')
				return false;
			else
			{
				if(strlen($variabila) > 40)
					return false;
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
		
		function verify($variabila)
		{
			if ($variabila == null || $variabila == '')
				return false;
			else
			{
				if(strlen($variabila) > 20)
					return false;
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
		
		function verify_username($variabila)
		{
			if ($variabila == null || $variabila == '')
				return false;
			else
			{
				if(strlen($variabila) > 15)
					return false;
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
		
		function verify_scoala($variabila)
		{
			if ($variabila == null || $variabila == '')
				return false;
			else
			{
				if(strlen($variabila) > 30)
					return false;
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
		
			if (verify_username($_POST['username']) == false)
				{
					$este_valid = 0;
					echo "<br/><br/>Username invalid";
				}
			if (verify_password($_POST['password']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Parolă invalidă";
				}
			if (verify($_POST['nume']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Nume invalid";
				}
			if (verify($_POST['prenume']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Prenume invalid";
				}
			if (verify_scoala($_POST['scoala']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Școala invalidă";
				}
			if (verify($_POST['localitate']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Localitate invalidă";
				}
			if (verify($_POST['judet']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Județ invalid";
				}
				
				if ($este_valid == 1)
				{
					$username = $_POST['username'];
					
					$query_user = "SELECT * FROM users WHERE username = ?";
					$statement_user = $databaseConnection->prepare($query_user);
					$statement_user->bind_param('s', $username);
					$statement_user->execute();
					$statement_user->store_result();
					
					if ($statement_user->error)
					{
						die('Database query failed: ' . $statement_user->error);
					}
					
					if ($statement_user->num_rows > 0)
					{
						echo "<br/><br/>Username-ul deja există!";
					}
					else
					{
						$password = $_POST['password'];
						$nume = $_POST['nume'];
						$prenume = $_POST['prenume'];
						
						$scoala = $_POST['scoala'];
						$localitate = $_POST['localitate'];
						$judet = $_POST['judet'];

						
						
							$query = "INSERT INTO cereri_prof (username, password, puncte, nume, prenume, scoala, localitate, judet) VALUES (?, SHA(?), 0, ?, ?, ?, ?, ?)";

							$statement = $databaseConnection->prepare($query);
							$statement->bind_param('sssssss', $username, $password, $nume, $prenume, $scoala, $localitate, $judet);
							$statement->execute();
							$statement->store_result();

							$creationWasSuccessful = $statement->affected_rows == 1 ? true : false;
							if ($creationWasSuccessful)
							{
								/*$userId = $statement->insert_id;

								$addToUserRoleQuery = "INSERT INTO users_in_roles (user_id, role_id) VALUES (?, ?)";
								$addUserToUserRoleStatement = $databaseConnection->prepare($addToUserRoleQuery);

								// TODO: Extract magic number for the 'user' role ID.
								$userRoleId = 1;
								$addUserToUserRoleStatement->bind_param('dd', $userId, $userRoleId);
								$addUserToUserRoleStatement->execute();
								$addUserToUserRoleStatement->close();

								$_SESSION['userid'] = $userId;
								$_SESSION['username'] = $username;*/
								header ("Location: register_prof_thanks.php");
							}
							else
							{
								echo "<br/><br/>Eroare la înregistrare";
							}
						
					}
				}
			else
			{
				echo "<br/><br/>Eroare la înregistrare";
			}
		}
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<form action="register_prof.php" method="post">
            <fieldset>
                <legend>Înregistrare cont profesor</legend>
                <ul type="none">
                    <li>
                        <label for="username">Nume utilizator:</label> 
                        <input type="text" name="username" value="" id="username" />
                    </li>
                    <li>
                        <label for="password">Parolă:</label>
                        <input type="password" name="password" value="" id="password" />
                    </li>
		    <li>
			<label for="nume">Nume:</label>
			<input type="text" name="nume" value="" id="nume" />
		    </li>
		    <li>
			<label for="prenume">Prenume:</label>
			<input type="text" name="prenume" value="" id="prenume" />
		    </li>
		    <li>
			<label for="scoala">Școala:</label>
			<input type="text" name="scoala" value="" id="scoala" />
		    </li>
		    <li>
			<label for="localitate">Localitate:</label>
			<input type="text" name="localitate" value="" id="localitate" />
		    </li>
		    <li>
			<label for="judet">Județ:</label>
			<input type="text" name="judet" value="" id="judet" />
		    </li>
                </ul>
                <input type="submit" name="submit" value="Înregistrare" />
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