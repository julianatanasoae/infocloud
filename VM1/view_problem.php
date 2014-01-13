<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<?php
				
				if (isset($_SESSION['clasa']) && (!is_admin() && !is_owner()))
				{
					$pageid = $_GET['id'];
					$query = 'SELECT nume, content, clasa, autor, timp, id_concurs FROM probleme WHERE id = ? AND clasa = ? LIMIT 1';
					$statement = $databaseConnection->prepare($query);
					$statement->bind_param('dd', $pageid, $_SESSION['clasa']);
				}
		else {		
        $pageid = $_GET['id'];
        $query = 'SELECT nume, content, clasa, autor, timp, id_concurs FROM probleme WHERE id = ? LIMIT 1';
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('d', $pageid);
		}
        $statement->execute();
        $statement->store_result();
        if ($statement->error)
        {
            die('Database query failed: ' . $statement->error);
        }

        if ($statement->num_rows == 1)
        {
            $statement->bind_result($nume, $content, $clasa, $autor, $timp, $id_concurs);
            $statement->fetch();
            echo "<h2>$nume</h2>";
	    echo "<p>";
	    echo "<b>Clasa:</b> " . $clasa . "<br />" . "<b>Autor:</b> " . $autor . "<br />";
	    echo "<b>Timp maxim de execuție:</b> ";
	    if ($timp == 1)
		echo $timp . " secundă" . "<br />";
	    else
		echo $timp . " secunde" . "<br />";
	    echo "</p>";
            echo $content;
            if (logged_on()&&!is_admin())
            {
		$eval_query = 'SELECT * FROM rezolvari WHERE id_user = ? AND id_problema = ?';
		$eval_statement = $databaseConnection->prepare($eval_query);
	        $eval_statement->bind_param('dd', $_SESSION['userid'], $pageid);
	        $eval_statement->execute();
	        $eval_statement->store_result();
		if ($eval_statement->error)
	        {
	            die('Database query failed: ' . $eval_statement->error);
	        }
		if($eval_statement->num_rows > 0)
		{
		    echo "<b>Ai încărcat deja o soluție la această problemă.</b>";
		}
		else echo "<br /><a href='upload_solution.php?id=" . $pageid . "'><b>Încarcă soluție</b></a>";
            }
	    else if(!logged_on())
	    {
		echo "<br /><b>Trebuie să vă logați pentru a vă putea evalua sursa proprie.</b>";
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