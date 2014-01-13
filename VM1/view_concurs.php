<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<?php
        $pageid = $_GET['id'];
        $query = 'SELECT nume, descriere, datainceperii, dataterminarii, autor FROM concursuri WHERE id = ? LIMIT 1';
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('d', $pageid);
        $statement->execute();
        $statement->store_result();
        if ($statement->error)
        {
            die('Database query failed: ' . $statement->error);
        }

        if ($statement->num_rows == 1)
        {
	    $todays_date_temp = $todays_date;
	    $todays_date_temp = str_replace('/', '-', $todays_date_temp);
	    $todays_date_de_comparat = date('Y-m-d', strtotime($todays_date_temp));
            $statement->bind_result($nume, $descriere, $datainceperii, $dataterminarii, $autor);
            $statement->fetch();
            echo "<h2>Vizualizare concurs: " . $nume . "</h2>";
	    echo "<p>";
	    echo "<b>Data începerii:</b> " . $datainceperii . "<br />" . "<b>Data terminării:</b> " . $dataterminarii . "<br />";
	    echo "<b>Autor:</b> " . $autor . "<br />";
	    echo "</p>";
            echo $descriere;

		$datainceperii_temp = $datainceperii;
	       	$dataterminarii_temp = $dataterminarii;
		$datainceperii_temp = str_replace('/', '-', $datainceperii_temp);
		$datainceperii_de_comparat = date('Y-m-d', strtotime($datainceperii_temp));
		$dataterminarii_temp = str_replace('/', '-', $dataterminarii);
		$dataterminarii_de_comparat = date('Y-m-d', strtotime($dataterminarii_temp));
	
	    if($datainceperii_de_comparat > $todays_date_de_comparat)
 		echo "<b>Concursul urmează să se desfășoare. La data începerii problemele se vor afișa aici.</b>";
	    else if($dataterminarii_de_comparat < $todays_date_de_comparat)
		{
			echo "<b>Concursul s-a încheiat. Iată rezultatele:</b> <br/><br/>";
			echo "<table border=\"1\">";
			echo "<tr><th>Nume</th><th>Prenume</th><th>Username</th><th>Clasa</th><th>Școala</th><th>Localitate</th><th>Județ</th><th>Punctaj</th>";
			$statement7 = $databaseConnection->prepare("SELECT id_user, id_concurs, clasa, punctaj FROM rezolvari_concurs WHERE id_concurs = ? ORDER BY clasa ASC, punctaj DESC");
			$statement7->bind_param('d', $pageid);
			$statement7->execute();
			$statement7->store_result();
			
			if($statement7->error)
			{
				die("Database query failed: " . $statement7->error);
			}
			
			$statement7->bind_result($id_user, $id_concurs, $clasa, $punctaj);
			while($statement7->fetch())
			{
				$statement8 = $databaseConnection->prepare("SELECT username, nume, prenume, scoala, localitate, judet FROM users WHERE id = ?");
				$statement8->bind_param('d', $id_user);
				$statement8->execute();
				$statement8->store_result();
				
				if($statement8->error)
				{
					die("Database query failed: " . $statement8->error);
				}
				
				$statement8->bind_result($username, $nume, $prenume, $scoala, $localitate, $judet);
				$statement8->fetch();
				echo "<tr>" . "<td>" . $nume . "</td>" . "<td>" . $prenume . "</td>" . "<td>" . $username . "</td>" . "<td>" . $clasa . "</td>" . "<td>" . $scoala . "</td>" . "<td>" . $localitate . "</td>" . "<td>" . $judet . "</td>" . "<td>" . $punctaj . "</td>" . "</tr>";
			}
		echo "</table>";	
		}
	    else if($datainceperii_de_comparat <= $todays_date_de_comparat && $todays_date_de_comparat <= $dataterminarii_de_comparat)
		{
		    echo "<b>Concursul este în plină desfășurare. Iată problemele:</b> <br/><br/>";
		    echo "<table border='1'>
	    <tr>
		<th>Numele problemei</th>
		<th>Clasa</th>
		<th>Autor</th>
		<th>Timp maxim de execuție</th>
		<th></th>
	    </tr>";
	    if (logged_on() && !is_admin())
	    {
            $statement = $databaseConnection->prepare("SELECT id, nume, clasa, autor, timp FROM probleme WHERE clasa = ? AND id_concurs = ?");
	    $statement->bind_param('dd', $_SESSION['clasa'], $pageid);
            $statement->execute();

            if($statement->error)
            {
                die("Database query failed: " . $statement->error);
            }

            $statement->bind_result($id, $nume, $clasa, $autor, $timp);
            while($statement->fetch())
            {
                echo "<tr>
			<td>" . $nume . "</td>
			<td>" . $clasa . "</td>
			<td>" . $autor . "</td>
			<td>"; if($timp == 1) echo $timp . " secundă"; else echo $timp . " secunde"; echo "</td>
			<td><a href=\"view_problem.php?id=" .$id . "\">Vizualizare</a>";
            }
	    }
	    else
	    {
	    $statement = $databaseConnection->prepare("SELECT id, nume, clasa, autor, timp FROM probleme WHERE id_concurs = ? ORDER BY clasa ASC");
	    $statement->bind_param('d', $pageid);
            $statement->execute();

            if($statement->error)
            {
                die("Database query failed: " . $statement->error);
            }

            $statement->bind_result($id, $nume, $clasa, $autor, $timp);
            while($statement->fetch())
            {
                echo "<tr>
			<td>" . $nume . "</td>
			<td>" . $clasa . "</td>
			<td>" . $autor . "</td>
			<td>"; if($timp == 1) echo $timp . " secundă"; else echo $timp . " secunde"; echo "</td>
			<td><a href=\"view_problem.php?id=" .$id . "\">Vizualizare</a>";
            }
	    }
	echo "</table>";
		}

        }
        else
        {
            echo 'Problema nu există în baza de date';
        }
    ?>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>