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
		
		if (isset($_POST['submit']))
    {
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
        $nume = $_POST['nume'];
		
		$duplicate_query = "SELECT * FROM probleme WHERE nume = ?";
		$duplicate_statement = $databaseConnection->prepare($duplicate_query);
		$duplicate_statement->bind_param('s', $nume);
		$duplicate_statement->execute();
		$duplicate_statement->store_result();
		
		if ($duplicate_statement->num_rows > 0)
		{
			echo "<br/><br/>Mai există deja o problemă cu acest nume! Încearcă altceva.";
		}
		else {
        $content = $_POST['content'];
		$content = str_replace("<script>", "", $content);
		$content = str_replace("</script>", "", $content);
		$clasa = $_POST['clasa'];
		$autor = $_SESSION['username'];
		$fisier = $_POST['fisier'];
		$timp = $_POST['timp'];
		$id_concurs = $_POST['id_concurs'];
        $query = "INSERT INTO probleme (nume, content, clasa, autor, fisier, timp, id_concurs) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('ssdssdd', $nume, $content, $clasa, $autor, $fisier, $timp, $id_concurs);
        $statement->execute();
        $statement->store_result();
	$problemId = $databaseConnection->insert_id;
		
		
		
		
		
		
				$oldmask = umask(0);
				mkdir('surse/' . (string)$clasa . '/' .  $problemId, 0777);
				mkdir('surse/' . (string)$clasa . '/' . $problemId . '/' . 'in', 0777);
				mkdir('surse/' . (string)$clasa . '/' . $problemId . '/' . 'ok', 0777);
				mkdir('surse/' . (string)$clasa . '/' . $problemId . '/' . 'work', 0777);
				umask($oldmask);
			
		

			if ($statement->error)
			{
				die('Database query failed: ' . $statement->error);
			}

			$creationWasSuccessful = $statement->affected_rows == 1 ? true : false;
			if ($creationWasSuccessful)
			{
				header ("Location: manage_files.php?id=" . $problemId);
			}
			else
			{
				echo '<br/><br/>Eroare la adăugarea problemei';
			}
		}
    }
	}
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<script src="ckeditor/ckeditor.js"></script>
				<form action="add_problem.php" method="post">
            <fieldset>
            <legend>Adăugare problemă</legend>
            <ul type="none">
                <li>
                    <label for="nume">Nume:</label> 
                    <input type="text" name="nume" value="" id="nume" />
                </li>
		<li>
		    <label for="clasa">Clasa:</label>
		    <select id="clasa" name="clasa">
			    <option value="0">--Selectează clasa--</option>
			    <option value="5">5</option>
			    <option value="6">6</option>
			    <option value="7">7</option>
			    <option value="8">8</option>
			    <option value="9">9</option>
			    <option value="10">10</option>
			    <option value="11">11</option>
			    <option value="12">12</option>
		    </select>
		</li>
		<li>
		    <label for="fisier">Numele fișierelor de date (in/out)</label>
		    <input type="text" name="fisier" value="" id="fisier" />
		</li>
		<li>
		    <label for="timp">Timp maxim de execuție:</label>
		    <input type="text" name="timp" value="" id="timp" />
		</li>
		<li>
		    <label for="id_concurs">OPȚIONAL - Atribuie problema la un concurs:</label>
		    <select id="id_concurs" name="id_concurs">
			    <option value="0">--Selectează concursul--</option>
			    <?php
                        $statement = $databaseConnection->prepare("SELECT id, nume FROM concursuri");
                        $statement->execute();

                        if($statement->error)
                        {
                            die("Database query failed: " . $statement->error);
                        }

                        $statement->bind_result($id, $nume);
                        while($statement->fetch())
                        {
                            echo "<option value=\"$id\">$nume</option>\n";
                        }
                        ?>
		    </select>
		</li>
                <li>
                    <label for="content">Conținut:</label>
                    <textarea name="content" id="content"></textarea>
		    <script>
			CKEDITOR.replace('content');
		    </script>
                </li>
            </ul>
            <input type="submit" name="submit" value="Adăugare" />
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