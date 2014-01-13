<?php
	require_once ("Includes/simplecms-config.php");
	require_once  ("Includes/connectDB.php");
	include("Includes/header.php");
	
	confirm_is_admin();
	
	$este_valid = 1;
	
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
	
	$pageId = null;
	$nume = null;
	$descriere = null;
	$datainceperii = null;
	$dataterminarii = null;

	if(isset($_GET['id']))
	{
		$pageId = $_GET['id'];
		$query = "SELECT nume, descriere, datainceperii, dataterminarii FROM concursuri WHERE id = ? AND autor = ?";
		$statement = $databaseConnection->prepare($query);
		$statement->bind_param('ds', $pageId, $_SESSION['username']);
		$statement->execute();
		$statement->store_result();

		if ($statement->error)
		{
			die('Database query failed: ' . $statement->error);
		}

		$pageExists = $statement->num_rows == 1;
		if ($pageExists)
		{
			$statement->bind_result($nume_vechi, $descriere, $datainceperii, $dataterminarii);
			$statement->fetch();
		}
		else
		{
			header("Location: index.php");
		}
		
		if (isset($_POST['submit']))
		{
			$este_valid = 1;
			if (verify($_POST['nume']) == false && $este_valid != 0)
						{
							$este_valid = 0;
							echo "<br/><br/>Nume invalid";
						}
						
			if ($este_valid == 1)
			{
				$pageId = $_POST['pageId'];
				$nume = $_POST['nume'];
				$duplicat = false;
				if ($nume_vechi != $nume)
				{
					$duplicate_query = "SELECT * FROM concursuri WHERE nume = ?";
					$duplicate_statement = $databaseConnection->prepare($duplicate_query);
					$duplicate_statement->bind_param('s', $nume);
					$duplicate_statement->execute();
					$duplicate_statement->store_result();
						
					if ($duplicate_statement->num_rows > 0)
					{
						echo "<br/><br/>Mai există deja o problemă cu acest nume! Încearcă altceva.";
						$duplicat = true;
					}
				}
					if($duplicat == false)
					{
						$descriere = $_POST['descriere'];
						$descriere = str_replace("<script>", "", $descriere);
						$descriere = str_replace("</script>", "", $descriere);
						$datainceperii = $_POST['datainceperii'];
						$dataterminarii = $_POST['dataterminarii'];

						$datainceperii_temp = $datainceperii;
						$dataterminarii_temp = $dataterminarii;
						$datainceperii_temp = str_replace('/', '-', $datainceperii_temp);
						$datainceperii_de_comparat = date('Y-m-d', strtotime($datainceperii_temp));
						$dataterminarii_temp = str_replace('/', '-', $dataterminarii);
						$dataterminarii_de_comparat = date('Y-m-d', strtotime($dataterminarii_temp));

						if ($datainceperii_de_comparat <= $dataterminarii_de_comparat)
						{
							$query = "UPDATE concursuri SET nume = ?, descriere = ?, datainceperii = ?, dataterminarii = ? WHERE Id = ?";

							$statement = $databaseConnection->prepare($query);
							$statement->bind_param('ssssd', $nume, $descriere, $datainceperii, $dataterminarii, $pageId);
							$statement->execute();
							$statement->store_result();

							if ($statement->error)
							{
								die('Database query failed: ' . $statement->error);
							}

							$creationWasSuccessful = $statement->affected_rows == 1 ? true : false;
							if ($creationWasSuccessful)
							{
								header ("Location: index.php");
							}
							else
							{
								echo '<br/><br/>Eroare la editarea concursului';
							}
						}
						else
						{
							echo '<br/><br/>Perioadă de desfășurare imposibilă!';
						}
					}
			}
			else {
				echo '<br/><br/>Eroare la editarea concursului';
			}
		}
	}
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
			<script src="ckeditor/ckeditor.js"></script>
			<?php echo "<form action=\"edit_concurs.php?id=" . $pageId . "\" method=\"post\">"; ?>
            <fieldset>
                <legend>Editare concurs</legend>
                <ul type="none">
                    <li>
			<input type="hidden" id="pageId" name="pageId" value="<?php echo $pageId; ?>" />
                        <label for="nume">Nume:</label> 
                        <input type="text" id="nume" name="nume" value="<?php echo $nume_vechi; ?>" />
                    </li>
                    <li>
                        <label for="descriere">Descriere:</label>
                        <textarea name="descriere" id="descriere"><?php echo $descriere; ?></textarea>
                        <script>
                            CKEDITOR.replace('descriere');
                        </script>
                    </li>
		    <li>
		    <script>
		        $(function() {
			    $("#datainceperii").datepicker({ dateFormat: 'dd/mm/yy' });
		    	});
		    </script>
		    <label for="datainceperii">Data începerii:</label>
		    <input type="text" name="datainceperii" value="<?php echo $datainceperii; ?>" id="datainceperii" readonly="true" />
		</li>
		<li>
		    <script>
			$(function() {
			    $("#dataterminarii").datepicker({ dateFormat: 'dd/mm/yy' });
			});
		    </script>
		    <label for="dataterminarii">Data terminării:</label>
		    <input type="text" name="dataterminarii" value="<?php echo $dataterminarii; ?>" id="dataterminarii" readonly="true" />
		</li>
                </ul>
                <input type="submit" name="submit" value="Salvare modificări" />
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