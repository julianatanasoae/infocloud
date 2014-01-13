<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_owner();
?>
	<div id="continutul_nostru">
		<header>
			<h1>Administrare elevi</h1>
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
						$statement = $databaseConnection->prepare("SELECT id, username, nume, prenume, scoala, localitate, judet FROM users WHERE clasa != 0");
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
							
								echo "<td>" . "<a href=\"delete_user.php?id=" . $id . "\">Șterge</a>";
							
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