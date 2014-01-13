<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_owner();
?>
	<div id="continutul_nostru">
		<header>
			<h1>Administrare profesori</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
				<table border="1">
					<tr>
						<th>Nume</th>
						<th>Prenume</th>
						<th>Username</th>
						<th>Școala</th>
						<th>Localitate</th>
						<th>Județ</th>
						<th></th>
					</tr>
					<?php
						$statement = $databaseConnection->prepare("SELECT id, username, nume, prenume, scoala, localitate, judet FROM cereri_prof");
						$statement->execute();
						$statement->store_result();

						if($statement->error)
						{
							die("Database query failed: " . $statement->error);
						}

						$statement->bind_result($id, $username, $nume, $prenume, $scoala, $localitate, $judet);
						while($statement->fetch())
						{
							echo "<tr>" . "<td>" . $nume . "</td>" . "<td>" . $prenume . "</td>" . "<td>" . $username . "</td>" . "<td>" . $scoala . "</td>" . "<td>" . $localitate . "</td>" . "<td>" . $judet . "</td>";
							$databaseConnection2 = new mysqli("localhost", "sampleuser", "samplepassword", "infocloud");
							if ($databaseConnection2->connect_error)
								{
									die("Database selection failed: " . $databaseConnection2->connect_error);
								}
							$statement2 = $databaseConnection2->prepare("SELECT * FROM users WHERE username = ?");
							$statement2->bind_param('s', $username);
							$statement2->execute();
							$statement2->store_result();
							
							if ($statement2->num_rows > 0)
								echo "<td>" . "<a href=\"delete_prof.php?user=" . $username . "\">Șterge</a>";
							else echo "<td>" . "<a href=\"aproba_prof.php?id=" . $id . "\">Aprobă</a>";
							echo "</tr>";
						}
					?>
				</table>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>