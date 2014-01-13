<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		if (isset($_POST['submit']))
		{
			$id_eval_submit = $_POST['id_eval_submit'];
			if (ctype_alnum($id_eval_submit))
			{
				$submit_verify_statement = $databaseConnection->prepare("SELECT id_user, id_problema, clasa, id_concurs, punctaj FROM evaluari WHERE id_eval = ? AND id_user = ? LIMIT 1");
				$submit_verify_statement->bind_param('sd', $id_eval_submit, $_SESSION['userid']);
				$submit_verify_statement->execute();
				$submit_verify_statement->store_result();
				
				if ($submit_verify_statement->num_rows == 1)
				{
					$submit_verify_statement->bind_result($id_user_submit, $id_problema_submit, $clasa_submit, $id_concurs_submit, $punctaj_submit);
					$submit_verify_statement->fetch();
					
					$submit_ai_mai_facut_statement = $databaseConnection->prepare("SELECT * FROM rezolvari WHERE id_user = ? AND id_problema = ?");
					$submit_ai_mai_facut_statement->bind_param('dd', $id_user_submit, $id_problema_submit);
					$submit_ai_mai_facut_statement->execute();
					$submit_ai_mai_facut_statement->store_result();
					if ($submit_ai_mai_facut_statement->error)
					{
						die('Database query failed: ' . $submit_ai_mai_facut_statement->error);
					}
					
					if ($submit_ai_mai_facut_statement->num_rows > 0)
					{
						echo "<br/><br/>Ai încărcat deja o soluție la această problemă!";
					}
					else
					{
						if ($id_concurs_submit == 0)
						{
							$submit_insert_query = "INSERT INTO rezolvari (id_user, id_problema) VALUES (?, ?)";
							$submit_insert_statement = $databaseConnection->prepare($submit_insert_query);
							$submit_insert_statement->bind_param('dd', $id_user_submit, $id_problema_submit);
							$submit_insert_statement->execute();
							$submit_insert_statement->store_result();
							if ($submit_insert_statement->error)
							{
								die('Database query failed: ' . $submit_insert_statement->error);
							}
							$creationWasSuccessful = $submit_insert_statement->affected_rows == 1 ? true : false;
							if ($creationWasSuccessful)
							{
								$query2 = "SELECT puncte FROM users WHERE id = ?";
								$statement2 = $databaseConnection->prepare($query2);
								$statement2->bind_param('d', $_SESSION['userid']);
								$statement2->execute();
								$statement2->store_result();
								if ($statement2->error)
								{
									die('Database query failed: ' . $statement2->error);
								}

								if ($statement2->num_rows == 1)
								{
									$statement2->bind_result($puncte_user);
									$statement2->fetch();
								}
							
								$punctaj_de_actualizat = $puncte_user + $punctaj_submit;

								$query3 = "UPDATE users SET puncte = ? WHERE id = ?";
								$statement3 = $databaseConnection->prepare($query3);
								$statement3->bind_param('dd', $punctaj_de_actualizat, $_SESSION['userid']);
								$statement3->execute();
								$statement3->store_result();

								if ($statement3->error)
								{
								die('Database query failed: ' . $statement3->error);
								}

								$updateWasSuccessful = $statement3->affected_rows == 1 ? true : false;
								
								if ($updateWasSuccessful)
								{
									header ("Location: index.php");
								}
								else
								{
								echo "<br/><br/>Eroare la actualizarea punctajului";
								}
							}
							else echo "<br/><br/>Eroare la trimiterea rezultatului";
						}
						else
						{
							$submit_insert_query = "INSERT INTO rezolvari (id_user, id_problema) VALUES (?, ?)";
							$submit_insert_statement = $databaseConnection->prepare($submit_insert_query);
							$submit_insert_statement->bind_param('dd', $id_user_submit, $id_problema_submit);
							$submit_insert_statement->execute();
							$submit_insert_statement->store_result();
							if ($submit_insert_statement->error)
							{
									die('Database query failed: ' . $submit_insert_statement->error);
							}
							$creationWasSuccessful = $submit_insert_statement->affected_rows == 1 ? true : false;
							if ($creationWasSuccessful)
							{
								$query2 = "SELECT * FROM rezolvari_concurs WHERE id_user = ? AND id_concurs = ?";
								$statement2 = $databaseConnection->prepare($query2);
									$statement2->bind_param('dd', $_SESSION['userid'], $id_concurs_submit);
									$statement2->execute();
									$statement2->store_result();
									if ($statement2->error)
								{
									die('Database query failed: ' . $statement2->error);
								}

								if ($statement2->num_rows == 1)
								{
									$query3 = "SELECT punctaj FROM rezolvari_concurs WHERE id_user = ? AND id_concurs = ?";
									$statement3 = $databaseConnection->prepare($query3);
									$statement3->bind_param('dd', $_SESSION['userid'], $id_concurs_submit);
									$statement3->execute();
									$statement3->store_result();
									if ($statement3->error)
									{
										die('Database query failed: ' . $statement3->error);
									}
									if ($statement3->num_rows == 1)
									{
										$statement3->bind_result($punctaj_existent);
										$statement3->fetch();
									}
									$punctaj_de_actualizat = $punctaj_existent + $punctaj_submit;

									$query4 = "UPDATE rezolvari_concurs SET punctaj = ? WHERE id_user = ? AND id_concurs = ?";
									$statement4 = $databaseConnection->prepare($query4);
									$statement4->bind_param('ddd', $punctaj_de_actualizat, $_SESSION['userid'], $id_concurs_submit);
									$statement4->execute();
									$statement4->store_result();
					
									if ($statement4->error)
									{
									die('Database query failed: ' . $statement4->error);
									}

									$updateWasSuccessful = $statement4->affected_rows == 1 ? true : false;
									
									if ($updateWasSuccessful)
									{
										header ("Location: index.php");
									}
									else
									{
									echo "Eroare la actualizarea punctajului";
									}				    
								}
								else
								{
									$query5 = "INSERT INTO rezolvari_concurs (id_user, id_concurs, clasa, punctaj) VALUES (?, ?, ?, ?)";
									$statement5 = $databaseConnection->prepare($query5);
									$statement5->bind_param('dddd', $_SESSION['userid'], $id_concurs_submit, $clasa, $punctaj_submit);
									$statement5->execute();
									$statement5->store_result();

									if($statement5->error)
									{
										die('Database query failed: ' . $statement5->error);
									}
									
									$insertWasSuccessful = $statement5->affected_rows == 1 ? true : false;
									if ($insertWasSuccessful)
									{
										header("Location: index.php");
									}
									else
									{
										echo "Eroare la actualizarea punctajului";
									}
								}
							}
							else
							{
								echo "Eroare la trimiterea rezultatului";
							}
						}
					}
				}
				else echo "<br/><br/>Eroare la trimiterea rezultatului";
			}
			else echo "<br/><br/>Eroare la trimiterea rezultatului";
		}
		
		$eval_databaseConnection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, "eval");
		if ($eval_databaseConnection->connect_error)
    {
        die("Database selection failed: " . $eval_databaseConnection->connect_error);
    }
	
	$id_eval = $_GET['id_eval'];
?>
	<div id="continutul_nostru">
		<header>
			<h1>Rezultatele evaluarii</h1>
		</header>
		<div class="row-fluid">
			<div class="span12">
				<?php
				if (ctype_alnum($id_eval) && !strstr($id_eval, ' '))
				{
						$initial_statement = $databaseConnection->prepare("SELECT id_user, id_problema, clasa, id_concurs, timp, vm FROM evaluari WHERE id_eval = ? LIMIT 1");
						$initial_statement->bind_param('s', $id_eval);
						$initial_statement->execute();
						$initial_statement->store_result();
						
						if ($initial_statement->error)
						{
							die("Database query failed: " . $initial_statement->error);
						}
						
						$initial_statement->bind_result($id_user, $id_problema, $clasa, $id_concurs, $timp, $vm);
						$initial_statement->fetch();
						
				
					$query12 = "SELECT username FROM users WHERE id = ? LIMIT 1";
					$statement12 = $databaseConnection->prepare($query12);
					$statement12->bind_param('d', $id_user);
					$statement12->execute();
					$statement12->store_result();
					
					$statement12->bind_result($eval_user);
					$statement12->fetch();
					
					$query13 = "SELECT nume FROM probleme WHERE id = ? LIMIT 1";
					$statement13 = $databaseConnection->prepare($query13);
					$statement13->bind_param('d', $id_problema);
					$statement13->execute();
					$statement13->store_result();
					$statement13->bind_result($eval_problema);
					$statement13->fetch();
					
					if ($id_concurs != 0)
					{
						$query_concurs = "SELECT nume FROM concursuri WHERE id = ? LIMIT 1";
						$statement_concurs = $databaseConnection->prepare($query_concurs);
						$statement_concurs->bind_param('d', $id_concurs);
						$statement_concurs->execute();
						$statement_concurs->store_result();
						$statement_concurs->bind_result($eval_concurs);
						$statement_concurs->fetch();
					}
						
						
						
						echo "ID evaluare: " . $id_eval . "<br/>" . "User: " . $eval_user . "<br/>" . "Problema: " . $eval_problema . "<br/>" . "Clasa: " . $clasa . "<br/>";
						if ($id_concurs != 0)
							echo "Concurs: " . $eval_concurs . "<br/>";
						if ($timp == 1)
						echo "Timp de execuție: " . $timp . " secundă" . "<br/>";
						else echo "Timp de execuție: " . number_format($timp, 2, '.', '') . " secunde" . "<br/>";
						echo "Evaluat de: VM" . $vm . " " . "<br/><br/>";
						
						$statement = $eval_databaseConnection->prepare("SELECT test, status, timp FROM `" . $id_eval . "`");
						$statement->execute();
						$statement->store_result();

					if($statement->error)
					{
						die("Database query failed: " . $statement->error);
					}

					$statement->bind_result($test, $status, $actual_time);
					while($statement->fetch())
					{
						if ($status == "depasit timpul de executie")
						{
							echo "<text style=\"background-color:yellow; color: #000000;\">Test " . $test . ": s-a depășit timpul de execuție" . "</text><br />";
						}
						else if ($status == "OK")
						{
							echo "<text style=\"background-color:green; color:white;\">Test " . $test . ": OK!" . "</text>" . " Timp de execuție: " . $actual_time . "</br>";
						}
						else if ($status == "gresit")
						{
							echo "<text style=\"background-color:red; color:white;\">Test " . $test . ": gresit!" . "</text>" . " Timp de execuție: " . $actual_time . "</br>";
						}
						if ($test == "output")
						{
							echo "<br/>Output compilator: " . $status;
						}
						if ($test == "punctaj")
						{
							echo "<br/>Punctaj: " . $status;
						}
					}
					
					$statement7 = $databaseConnection->prepare("SELECT * FROM evaluari WHERE id_eval = ? AND id_user = ?");
					$statement7->bind_param('sd', $id_eval, $_SESSION['userid']);
					$statement7->execute();
					$statement7->store_result();
					
					if ($statement7->num_rows == 1)
					{
						$submit_ai_mai_facut_statement = $databaseConnection->prepare("SELECT * FROM rezolvari WHERE id_user = ? AND id_problema = ?");
						$submit_ai_mai_facut_statement->bind_param('dd', $_SESSION['userid'], $id_problema);
						$submit_ai_mai_facut_statement->execute();
						$submit_ai_mai_facut_statement->store_result();
						if ($submit_ai_mai_facut_statement->error)
						{
							die('Database query failed: ' . $submit_ai_mai_facut_statement->error);
						}
						
						if ($submit_ai_mai_facut_statement->num_rows > 0)
						{
							echo "<br/><br/>Ai încărcat deja o soluție la această problemă!";
						}
						else
						{
							echo "<br/><br/>Ești mulțumit de rezultat? Dacă nu, mai stoarce niște neuroni, modifică-ți soluția, și mai evaluează o dată.";
							echo "<br />" . "Dacă nu mai vrei să modifici nimic, trimite rezultatul. Atenție, după trimiterea rezultatului nu mai poți să reevaluezi soluții la această problemă!";
							echo "<br /><br />";
							echo "<form action=\"view_eval_result.php?id_eval=" . $id_eval . "\" method=\"post\">";
							echo "<fieldset>";
							echo "<input type=\"hidden\" id=\"id_eval_submit\" name=\"id_eval_submit\" value=\"" . $id_eval . "\" />";
							echo "<input type=\"submit\" name=\"submit\" value=\"Trimite rezultatul\" />";
							echo "</fieldset>";
							echo "</form>";
						}
					}
				}
				else
					header("Location: index.php");
				?>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
	mysqli_close($eval_databaseConnection);
 ?>
