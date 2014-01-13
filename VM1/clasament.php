<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		if (isset($_POST['submit']))
		{
			header("Location: clasament.php?clasa=" . $_POST['clasa']);
		}
?>
	<div id="continutul_nostru">
		<header>
			<h1>Clasament</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
			<form action="clasament.php" method="post">
				<fieldset>
					<legend></legend>
					<ul type="none">
						<li>
							<select id="clasa" name="clasa">
								<option value="0">--Toate clasele--</option>
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
						<li><input type="submit" name="submit" value="Filtrează" /></li>
					</ul>
				</fieldset>
			</form>
				<table border="1">
        <tr>
            <th>Nume</th>
            <th>Prenume</th>
	    <th>Username</th>
	    <th>Clasa</th>
	    <th>Școala</th>
	    <th>Localitate</th>
	    <th>Județ</th>
	    <th>Punctaj</th>
        </tr>
        <?php
			$clasa = 0;
			if ($_GET['clasa'] != NULL)
				$clasa = $_GET['clasa'];
			if (intval($_GET['clasa']) == 0)
				$clasa = NULL;
	    if ($clasa != NULL)
	    {
			if (!is_numeric($_GET['clasa']) || (is_numeric($_GET['clasa']) && ($_GET['clasa'] < 5 || $_GET['clasa'] > 12) && $_GET['clasa'] != 0))
				{
					echo "<br/><br/>Clasa invalidă";
				}
			else
			{
				$statement = $databaseConnection->prepare("SELECT username, puncte, clasa, nume, prenume, scoala, localitate, judet FROM users WHERE clasa = ? ORDER BY puncte DESC");
				$statement->bind_param('d', $clasa);
				$statement->execute();
				$statement->store_result();

				if($statement->error)
				{
					die("Database query failed: " . $statement->error);
				}

				$statement->bind_result($username, $puncte, $clasa_user, $nume, $prenume, $scoala, $localitate, $judet);
				while($statement->fetch())
				{
					echo "<tr>" . "<td>" . $nume . "</td>" . "<td>" . $prenume . "</td>" . "<td>" . $username . "</td>" . "<td>" . $clasa_user . "</td>" . "<td>" . $scoala . "</td>" . "<td>" . $localitate . "</td>" . "<td>" . $judet . "</td>" . "<td>" . $puncte . "</td>" . "</tr>";
				}
			}
	    }
	    else
	    {
		$statement = $databaseConnection->prepare("SELECT username, puncte, clasa, nume, prenume, scoala, localitate, judet FROM users WHERE clasa != 0 ORDER BY clasa ASC, puncte DESC");
		    $statement->execute();
		    $statement->store_result();

		    if($statement->error)
		    {
		        die("Database query failed: " . $statement->error);
		    }

		    $statement->bind_result($username, $puncte, $clasa_user, $nume, $prenume, $scoala, $localitate, $judet);
		    while($statement->fetch())
		    {
		        echo "<tr>" . "<td>" . $nume . "</td>" . "<td>" . $prenume . "</td>" . "<td>" . $username . "</td>" . "<td>" . $clasa_user . "</td>" . "<td>" . $scoala . "</td>" . "<td>" . $localitate . "</td>" . "<td>" . $judet . "</td>" . "<td>" . $puncte . "</td>" . "</tr>";
		    }
	    }
        ?>
    </table>
	<p>
		<?php
			if ($clasa != NULL)
				echo "<a href=\"export.php?clasa=" . $clasa . "\">Exportă clasamentul în Excel</a>";
			else echo "<a href=\"export.php\">Exportă clasamentul în Excel</a>";
		?>
	</p>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>