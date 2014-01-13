<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
?>
	<div id="continutul_nostru">
		<header>
			<h1>Probleme</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
				<table border="1">
	    <tr>
		<th>Numele problemei</th>
		<th>Clasa</th>
		<th>Autor</th>
		<th>Timp maxim de execuție</th>
		<th></th>
	    </tr>
        <?php
	    if (logged_on() && !is_admin() && !is_owner())
	    {
            $statement = $databaseConnection->prepare("SELECT id, nume, clasa, autor, timp FROM probleme WHERE clasa = ? AND id_concurs = 0");
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
			<td>" . $nume . "</td>
			<td>" . $clasa . "</td>
			<td>" . $autor . "</td>
			<td>"; if($timp == 1) echo $timp . " secundă"; else echo $timp . " secunde"; echo "</td>
			<td><a href=\"view_problem.php?id=$id\">Vizualizare</a>";
            }
	    }
	    else
	    {
	    $statement = $databaseConnection->prepare("SELECT id, nume, clasa, autor, timp FROM probleme WHERE id_concurs = 0 ORDER BY clasa ASC");
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
			<td><a href=\"view_problem.php?id=$id\">Vizualizare</a>";
            }
	    }
        ?>
	</table>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>