<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_admin();
		
		if (isset($_POST['submit']))
{
    $pageId = $_POST['pageId'];
    $query = "SELECT Id FROM probleme WHERE id = ? AND autor = ?";
    $statement = $databaseConnection->prepare($query);
    $statement->bind_param('ds', $pageId, $_SESSION['username']);
    $statement->execute();
    $statement->store_result();

    if ($statement->error)
    {
        die('Database query failed: ' . $statement->error);
    }

    // TODO: Check for == 1 instead of > 0 when page names become unique.
    $pageExists = $statement->num_rows == 1;
    if ($pageExists)
    {
        header ("Location: edit_problem.php?id=$pageId");
    }
    else
    {
        echo "Nu s-a găsit problema selectată pentru editare";
    }
}
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<form action="select_problem_to_edit.php" method="post">
        <fieldset>
            <legend>Editare problemă</legend>
            <ul type="none">
                <li>
                    <label for="pageId">Nume:</label>
                    <select id="pageId" name="pageId">
                        <option value="0">--Selectează problema--</option>
                        <?php
                        $statement = $databaseConnection->prepare("SELECT id, nume FROM probleme WHERE autor = ?");
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
            <input type="submit" name="submit" value="Editare" />
        </fieldset>
    </form>
	<a href="index.php">Anulare</a>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>