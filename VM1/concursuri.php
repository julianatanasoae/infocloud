<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
?>
	<div id="continutul_nostru">
		<header>
			<h1>Concursuri</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
				<table border="1">
	    <tr>
		<th>Numele concursului</th>
		<th>Data începerii</th>
		<th>Data terminării</th>
		<th>Stare</th>
		<th>Autor</th>
		<th></th>
	    </tr>
        <?php
	    $statement = $databaseConnection->prepare("SELECT id, nume, datainceperii, dataterminarii, autor FROM concursuri");
            $statement->execute();

            if($statement->error)
            {
                die("Database query failed: " . $statement->error);
            }

            $statement->bind_result($id, $nume, $datainceperii, $dataterminarii, $autor);
	    $todays_date_temp = $todays_date;
	    $todays_date_temp = str_replace('/', '-', $todays_date_temp);
	    $todays_date_de_comparat = date('Y-m-d', strtotime($todays_date_temp));
            while($statement->fetch())
            {
		$datainceperii_temp = $datainceperii;
		$dataterminarii_temp = $dataterminarii;
		$datainceperii_temp = str_replace('/', '-', $datainceperii_temp);
		$datainceperii_de_comparat = date('Y-m-d', strtotime($datainceperii_temp));
		$dataterminarii_temp = str_replace('/', '-', $dataterminarii);
		$dataterminarii_de_comparat = date('Y-m-d', strtotime($dataterminarii_temp));
                echo "<tr>
			<td>" . "<a href=\"view_concurs.php?id=$id\">$nume</a>" . "</td>
			<td>" . $datainceperii . "</td>
			<td>" . $dataterminarii . "</td>
			<td>"; if($datainceperii_de_comparat > $todays_date_de_comparat) echo "nedesfășurat"; else if($dataterminarii_de_comparat < $todays_date_de_comparat) echo "finalizat"; else if($datainceperii_de_comparat <= $todays_date_de_comparat && $todays_date_de_comparat <= $dataterminarii_de_comparat) echo "în desfășurare"; echo "</td>
			<td>" . $autor . "</td>
			<td><a href=\"view_concurs.php?id=$id\">Vizualizare</a>";
            }
        ?>
	</table>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>