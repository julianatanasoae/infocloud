    <?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
     ?>

<div class="hero-unit">
        <!--<h1>InfoCloud</h1>-->
		<img src="Images/IClogo2.png" style="width: 300px;" />
		
		<table border="1" style="float: right; font-size: 14px;">
		<tr>
		<td colspan="8"><center><h2>Cei mai buni din clasament</h2></center></td>
		</tr>
			<tr>
				<th>Nume</th>
				<th>Prenume</th>
				<th>Username</th>
				<th>Clasa</th>
				<th>Punctaj</th>
			</tr>
			<?php
				$statement = $databaseConnection->prepare("SELECT username, puncte, clasa, nume, prenume FROM users WHERE username != 'admin' ORDER BY puncte DESC LIMIT 3");
		    $statement->execute();
		    $statement->store_result();

		    if($statement->error)
		    {
		        die("Database query failed: " . $statement->error);
		    }

		    $statement->bind_result($username, $puncte, $clasa, $nume, $prenume);
		    while($statement->fetch())
		    {
		        echo "<tr>" . "<td>" . $nume . "</td>" . "<td>" . $prenume . "</td>" . "<td>" . $username . "</td>" . "<td>" . $clasa . "</td>"  . "<td>" . $puncte . "</td>" . "</tr>";
		    }
			?>
		</table>
        <p class="lead">Fii cu capul în nori într-un mod productiv!</p>
        <p><a href="documentatie.pdf" class="btn btn-primary btn-large">Cum folosesc platforma? &raquo;</a></p>
    </div>

	<div class="row">
		<div class="span4">
			<h2>Ultimele concursuri</h2>
			<table border="1">
		<tr>
			<th>Numele concursului</th>
			<th>Data începerii</th>
			<th>Data terminării</th>
			<th>Stare</th>
			<th>Autor</th>
	    	</tr>
		<?php
	    $statement = $databaseConnection->prepare("SELECT id, nume, datainceperii, dataterminarii, autor FROM concursuri ORDER BY id DESC LIMIT 3");
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
		<td><a href=\"view_concurs.php?id=$id\">$nume</a></td>
			<td>" . $datainceperii . "</td>
			<td>" . $dataterminarii . "</td>
			<td>"; if($datainceperii_de_comparat > $todays_date_de_comparat) echo "nedesfășurat"; else if($dataterminarii_de_comparat < $todays_date_de_comparat) echo "finalizat"; else if($datainceperii_de_comparat <= $todays_date_de_comparat && $todays_date_de_comparat <= $dataterminarii_de_comparat) echo "în desfășurare"; echo "</td>
			<td>" . $autor . "</td>";
            }
        ?>

		</table>
		</div>
		<div class="span4" id="div_probleme">
			<h2>Ultimele probleme</h2>
			<table border="1">
	    <tr>
		<th>Numele problemei</th>
		<th>Clasa</th>
		<th>Autor</th>
		<th>Timp maxim de execuție</th>
	    </tr>
        <?php
	    if (logged_on() && !is_admin() && !is_owner())
	    {
            $statement = $databaseConnection->prepare("SELECT id, nume, clasa, autor, timp FROM probleme WHERE clasa = ? AND id_concurs = 0 ORDER BY id DESC LIMIT 3");
	    $statement->bind_param('d', $_SESSION['clasa']);
            $statement->execute();

            if($statement->error)
            {
                die("Database query failed: " . $statement->error);
            }

            $statement->bind_result($id, $nume, $clasa, $autor, $timp);
            while($statement->fetch())
            {
                echo "<tr>
			<td><a href=\"view_problem.php?id=$id\">$nume</a></td>
			<td>" . $clasa . "</td>
			<td>" . $autor . "</td>
			<td>"; if($timp == 1) echo $timp . " secundă"; else echo $timp . " secunde"; echo "</td>";
            }
	    }
	    else
	    {
	    $statement = $databaseConnection->prepare("SELECT id, nume, clasa, autor, timp FROM probleme WHERE id_concurs = 0 ORDER BY id DESC LIMIT 3");
            $statement->execute();

            if($statement->error)
            {
                die("Database query failed: " . $statement->error);
            }

            $statement->bind_result($id, $nume, $clasa, $autor, $timp);
            while($statement->fetch())
            {
                echo "<tr>
			<td><a href=\"view_problem.php?id=$id\">$nume</a></td>
			<td>" . $clasa . "</td>
			<td>" . $autor . "</td>
			<td>"; if($timp == 1) echo $timp . " secundă"; else echo $timp . " secunde"; echo "</td>";
            }
	    }
        ?>
	</table>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>