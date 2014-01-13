<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_admin();
		
		if (isset($_POST['submit']))
    {
        $pageId = $_POST['nume'];
        $query = "DELETE FROM concursuri WHERE id = ? AND autor = ?";
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('ds', $pageId, $_SESSION['username']);
        $statement->execute();
        $statement->store_result();

        if ($statement->error)
        {
            die('Database query failed: ' . $statement->error);
        }

        // TODO: Check for == 1 instead of > 0 when page names become unique.
        $deletionWasSuccessful = $statement->affected_rows > 0 ? true : false;
        if ($deletionWasSuccessful)
        {
            header ("Location: index.php");
        }
        else
        {
            echo "<br/><br/>Eroare la ștergerea concursului";
        }
    }
?>
	<div id="continutul_nostru">
		<div class="row-fluid">
			<div class="span12">
				<form action="delete_concurs.php" method="post">
        <fieldset>
            <legend>Ștergere concurs</legend>
            <ul type="none">
                <li>
                    <label for="nume">Nume:</label>
                    <select id="nume" name="nume">
                        <option value="0">--Selectează concursul--</option>
                            <?php
                                $statement = $databaseConnection->prepare("SELECT id, nume FROM concursuri WHERE autor = ?");
								$statement->bind_param('s', $_SESSION['username']);
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
            </ul>
            <input type="submit" name="submit" value="Ștergere" />
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