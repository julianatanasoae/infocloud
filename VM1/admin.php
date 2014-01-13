<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_admin();
?>
	<div id="continutul_nostru">
		<header>
			<h1>Administrare</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
	<h3>Administrare probleme</h3>
	<ul style="list-style-type: none;">
	    <li><a href="add_problem.php">Adăugare problemă</a></li>
	    <li><a href="select_problem_to_edit.php">Editare problemă</a></li>
	    <li><a href="delete_problem.php">Ştergere problemă</a></li>
	    <li><a href="select_problem_to_manage.php">Administrare teste pentru evaluator</a></li>
	</ul>
	<h3>Administrare concursuri</h3>
	<ul style="list-style-type: none;">
	    <li><a href="add_concurs.php">Adăugare concurs</a></li>
	    <li><a href="select_concurs_to_edit.php">Editare concurs</a></li>
	    <li><a href="delete_concurs.php">Ștergere concurs</a></li>
	</ul>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>