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
	$content = null;
	$clasa = null;
	$fisier = null;
	$timp = null;
	if(isset($_GET['id']))
	{
		$pageId = $_GET['id'];
		$query = "SELECT nume, content, clasa, fisier, timp FROM probleme WHERE id = ? AND autor = ?";
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
			$statement->bind_result($nume_vechi, $content, $clasa, $fisier, $timp);
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
					
			if (!is_numeric($_POST['clasa']) || (is_numeric($_POST['clasa']) && ($_POST['clasa'] < 5 || $_POST['clasa'] > 12)) && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Clasa invalidă";
				}
			
			if (verify($_POST['fisier']) == false && $este_valid != 0)
				{
					$este_valid = 0;
					echo "<br/><br/>Nume fisier invalid";
				}
				
			if (!is_numeric($_POST['timp']))
				{
					$este_valid = 0;
					echo "<br/><br/>Timp de execuție invalid";
				}	
				
			if ($este_valid == 1)
			{
				$pageId = $_POST['pageId'];
				$nume = $_POST['nume'];
				$duplicat = false;
				if ($nume_vechi != $nume)
				{
					$duplicate_query = "SELECT * FROM probleme WHERE nume = ?";
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
					$content = $_POST['content'];
					$content = str_replace("<script>", "", $content);
					$content = str_replace("</script>", "", $content);
					$clasa = $_POST['clasa'];
					$fisier = $_POST['fisier'];
					$timp = $_POST['timp'];
					$query = "UPDATE probleme SET nume = ?, content = ?, clasa = ?, fisier = ?, timp = ? WHERE Id = ?";

					$statement = $databaseConnection->prepare($query);
					$statement->bind_param('ssdsdd', $nume, $content, $clasa, $fisier, $timp, $pageId);
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
						echo '<br/><br/>Eroare la editarea problemei';
					}
				}
			}
			else {
				echo '<br/><br/>Eroare la editarea problemei';
			}
		}
	}
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<script src="ckeditor/ckeditor.js"></script>
				<?php echo "<form action=\"edit_problem.php?id=" . $pageId . "\" method=\"post\">"; ?>
            <fieldset>
                <legend>Editare problemă</legend>
                <ul type="none">
                    <li>
			<input type="hidden" id="pageId" name="pageId" value="<?php echo $pageId; ?>" />
                        <label for="nume">Nume:</label> 
                        <input type="text" id="nume" name="nume" value="<?php echo $nume_vechi; ?>" />
                    </li>
		    <li>
		    <label for="clasa">Clasa:</label>
		    <select id="clasa" name="clasa">
			    <?php if ($clasa == 0) echo '<option value="0" selected="selected">--Selectează clasa--</option>';
				else echo '<option value="0">--Selectează clasa--</option>';
			    if ($clasa == 5) echo '<option value="5" selected="selected">5</option>';
				else echo '<option value="5">5</option>';
			    if ($clasa == 6) echo '<option value="6" selected="selected">6</option>';
				else echo '<option value="6">6</option>';
			    if ($clasa == 7) echo '<option value="7" selected="selected">7</option>';
				else echo '<option value="7">7</option>';
			    if ($clasa == 8) echo '<option value="8" selected="selected">8</option>';
				else echo '<option value="8">8</option>';
			    if ($clasa == 9) echo '<option value="9" selected="selected">9</option>';
				else echo '<option value="9">9</option>';
			    if ($clasa == 10) echo '<option value="10" selected="selected">10</option>';
				else echo '<option value="10">10</option>';
			    if ($clasa == 11) echo '<option value="11" selected="selected">11</option>';
				else echo '<option value="11">11</option>';
			    if ($clasa == 12) echo '<option value="12" selected="selected">12</option>';
				else echo '<option value="12">12</option>';
			    ?>
		    </select>
		</li>
		<li>
		    <label for="fisier">Numele fișierelor de date (in/out)</label>
		    <input type="text" name="fisier" value="<?php echo $fisier; ?>" id="fisier" />
		</li>
		<li>
		    <label for="timp">Timp maxim de execuție:</label>
		    <input type="text" name="timp" value="<?php echo $timp; ?>" id="timp" />
		</li>
                    <li>
                        <label for="content">Conținut:</label>
                        <textarea name="content" id="content"><?php echo $content; ?></textarea>
                        <script>
                            CKEDITOR.replace('content');
                        </script>
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