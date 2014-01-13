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
		
		if ($este_valid == 1)
			{
        $nume = $_POST['nume'];
		
		$duplicate_query = "SELECT * FROM concursuri WHERE nume = ?";
		$duplicate_statement = $databaseConnection->prepare($duplicate_query);
		$duplicate_statement->bind_param('s', $nume);
		$duplicate_statement->execute();
		$duplicate_statement->store_result();
		
		if ($duplicate_statement->num_rows > 0)
		{
			echo "<br/><br/>Mai există deja o problemă cu acest nume! Încearcă altceva.";
		}
		
		else {
		
        $descriere = $_POST['descriere'];
		$descriere = str_replace("<script>", "", $descriere);
		$descriere = str_replace("</script>", "", $descriere);
	$datainceperii = $_POST['datainceperii'];
	$dataterminarii = $_POST['dataterminarii'];
	$autor = $_SESSION['username'];
	
	$datainceperii_temp = $datainceperii;
	$dataterminarii_temp = $dataterminarii;
	$datainceperii_temp = str_replace('/', '-', $datainceperii_temp);
	$datainceperii_de_comparat = date('Y-m-d', strtotime($datainceperii_temp));
	$dataterminarii_temp = str_replace('/', '-', $dataterminarii);
	$dataterminarii_de_comparat = date('Y-m-d', strtotime($dataterminarii_temp));

	if ($datainceperii_de_comparat <= $dataterminarii_de_comparat)
	{
		$query = "INSERT INTO concursuri (nume, descriere, datainceperii, dataterminarii, autor) VALUES (?, ?, ?, ?, ?)";

		$statement = $databaseConnection->prepare($query);
		$statement->bind_param('sssss', $nume, $descriere, $datainceperii, $dataterminarii, $autor);
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
		    echo 'Eroare la adăugarea concursului';
		}
	}
	else
	{
		echo 'Perioadă de desfășurare imposibilă!';
	}
	}
	}
    }
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<script src="ckeditor/ckeditor.js"></script>
				<form action="add_concurs.php" method="post">
					<fieldset>
					<legend>Adăugare concurs</legend>
					<ul type="none">
                <li>
                    <label for="nume">Nume:</label> 
                    <input type="text" name="nume" value="" id="nume" />
                </li>
                <li>
                    <label for="descriere">Descriere:</label>
                    <textarea name="descriere" id="descriere"></textarea>
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
		    <input type="text" name="datainceperii" value="" id="datainceperii" readonly="true" />
		</li>
		<li>
		    <script>
			$(function() {
			    $("#dataterminarii").datepicker({ dateFormat: 'dd/mm/yy' });
			});
		    </script>
		    <label for="dataterminarii">Data terminării:</label>
		    <input type="text" name="dataterminarii" value="" id="dataterminarii" readonly="true" />
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