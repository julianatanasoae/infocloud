<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_admin();
?>
	<div id="continutul_nostru">
		<header>
			<h1>Administrare teste pentru evaluator</h1>
		</header>
		
		<div class="row-fluid">
			<div class="span12">
			<?php 
	$id = $_GET['id'];
	$query = 'SELECT nume, clasa, fisier, timp FROM probleme WHERE id = ? LIMIT 1';
        $statement = $databaseConnection->prepare($query);
        $statement->bind_param('d', $id);
        $statement->execute();
        $statement->store_result();
        if ($statement->error)
        {
            die('Database query failed: ' . $statement->error);
        }

        if ($statement->num_rows == 1)
        {
	    $statement->bind_result($nume, $clasa, $fisier, $timp);
            $statement->fetch();
	}
	?>
				<p>ID-ul problemei: <?php echo $id; ?><br/>
       Numele problemei: <?php echo $nume; ?><br/>
       Clasa: <?php echo $clasa; ?><br/>
       Timp maxim de execuție: <?php if ($timp == 1) echo $timp . " secundă"; else echo $timp . " secunde"; ?><br/>
    </p>
    <p>
	Acum trebuie să ne ocupăm de partea de evaluare a problemei.<br/>
	Pentru a construi un evaluator funcțional, trebuie să urmezi instrucțiunile de mai jos.<br/>
	Mai jos este un client FTP care îți arată în partea dreaptă câteva foldere care stau pe serverul nostru.<br/>
	Structura este următoarea:</br>
	<ul>
	    <li>Clasa
		<ul>
		    <li>ID-ul problemei
			<ul>
			    <li>in - aici sunt fișierele de intrare</li>
			    <li>ok - aici sunt fișierele de ieșire care ar trebui să fie generate la o problemă rezolvată corect</li>
			    <li>work - aici vor sta sesiunile de evaluare ale elevilor</li>
			</ul>
		    </li>
		</ul>
	    </li>
	</ul>
    </p>
    <p>
	Pe tine te interesează folderele <b>in</b> și <b>ok</b>. În aceste foldere vei uploada testele.<br/>
	<b>Creează-ți în calculator fișierele de intrare după următoarea structură: <?php echo "1-" . $fisier . ".in, " . "2-" . $fisier . ".in, ... " . "10-" . $fisier . ".in"; ?></br>
	Apoi creează OK-urile după următoarea structură: <?php echo "1-" . $fisier . ".ok, " . "2-" . $fisier . ".ok, ... " . "10-" . $fisier . ".ok"; ?></br>
	Intră în partea dreaptă a clientului în folderul <?php echo $clasa . "/" . $id . "/in "; ?> și uploadează acolo IN-urile.</br>
	După ce ai efectuat acest pas, intră în folderul <?php echo $clasa . "/" . $id . "/ok "; ?> și uploadează acolo OK-urile.
    </b></p>
    <p>
	Evaluarea se va face după regula "10 puncte pentru fiecare test" astfel încât punctajul maxim pentru o problemă să fie de 100 de puncte.<br/>
	Dacă ai urmat instrucțiunile și totul a mers 'ca pe roate', elevul își va putea evalua problema.<br/>
	Dacă nu... mai citește o dată! :)
    </p>
	<iframe style="position:absolute; top: 600px; color: #0f8790; height: 84px; width: 705px; z-index:20" frameborder="0"></iframe>
    <script type="text/javascript" src="deployJava.js"></script>
    <script language="javascript" src="uftpscript.js"></script>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>