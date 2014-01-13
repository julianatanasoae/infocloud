<?php
        require_once ("Includes/simplecms-config.php");
        require_once  ("Includes/connectDB.php");
        include("Includes/header.php");
		
		confirm_is_logged_in();
		
		// replace connection string with data provided by the Windows Azure portal
		$connectionString = "DefaultEndpointsProtocol=http;AccountName=;AccountKey=";
        require_once("vendor/autoload.php");

		use WindowsAzure\Common\ServicesBuilder;
		use WindowsAzure\Common\ServiceException;
		use WindowsAzure\Queue\Models\CreateMessageOptions;
		
		$id = $_GET['id'];
    $query = "SELECT clasa, fisier, timp, id_concurs FROM probleme WHERE id = ? AND clasa = ? LIMIT 1";
    $statement = $databaseConnection->prepare($query);
    $statement->bind_param('dd', $id, $_SESSION['clasa']);
    $statement->execute();
    $statement->store_result();
    $statement->bind_result($clasa, $fisier, $timp, $id_concurs);
    $statement->fetch();
	
	if ($statement->error)
        {
            die('Database query failed: ' . $statement->error);
        }
		
	if ($statement->num_rows != 1)
	{
		header ("Location: index.php");
	}
	
	else {
	
	if ($id_concurs != 0)
		{
			$query2 = "SELECT datainceperii, dataterminarii FROM concursuri WHERE id = ?";
			$statement2 = $databaseConnection->prepare($query2);
			$statement2->bind_param('d', $id_concurs);
			$statement2->execute();
			$statement2->store_result();
			$statement2->bind_result($datainceperii, $dataterminarii);
			$statement2->fetch();
			
			if ($statement2->error)
			{
				die('Database query failed: ' . $statement2->error);
			}
			
			$todays_date_temp = $todays_date;
			$todays_date_temp = str_replace('/', '-', $todays_date_temp);
			$todays_date_de_comparat = date('Y-m-d', strtotime($todays_date_temp));
			
			$datainceperii_temp = $datainceperii;
			$dataterminarii_temp = $dataterminarii;
			$datainceperii_temp = str_replace('/', '-', $datainceperii_temp);
			$datainceperii_de_comparat = date('Y-m-d', strtotime($datainceperii_temp));
			$dataterminarii_temp = str_replace('/', '-', $dataterminarii);
			$dataterminarii_de_comparat = date('Y-m-d', strtotime($dataterminarii_temp));
			
			if($datainceperii_de_comparat > $todays_date_de_comparat)
			{
				header("Location: index.php");
			}
			else if($dataterminarii_de_comparat < $todays_date_de_comparat)
			{
				header("Location: index.php");
			}
		}

    if (isset($_POST['submit']))
    {
        $id_problema = $_POST['id_problema'];
		
		
		
        if ($_FILES["file"]["size"] <= 10240)
        {
            $extension = end(explode(".", $_FILES["file"]["name"]));
            if ($extension == "cpp")
            {
                if ($_FILES["file"]["error"] > 0)
                {
                    echo "Cod eroare: " . $_FILES["file"]["error"] . "<br />";
                }
                else
		{
		    $oldmask = umask(0);
                    $uniqid = uniqid();
		    mkdir('surse/' . (string)$clasa . '/' . $id_problema . '/work/' . $uniqid, 0777);
                    $nume_fisier = $uniqid . ".cpp";
                    move_uploaded_file($_FILES["file"]["tmp_name"], "surse/" . (string)$clasa . '/' . $id_problema . '/work/' . $uniqid . '/' . $nume_fisier);
		    mkdir('surse/' . (string)$clasa . '/' . $id_problema . '/work/' . $uniqid . '/' . 'out', 0777);
		    umask($oldmask);
                }
            }
            else
            {
                $nume_fisier = NULL;
                echo "Fisier invalid <br />";
            }
        }
        else
        {
            $nume_fisier = NULL;
            echo "Eroare! Fișierul este mai mare de 1 MB<br />";
        }

	if ($nume_fisier != NULL)
	{
		$queueRestProxy = ServicesBuilder::getInstance()->createQueueService($connectionString);
		try {
			// Create message.
			$builder = new ServicesBuilder();
			if ($id_concurs != 0)
				$message = "{ \"id_problema\": \"" . $id_problema . "\", \"id_eval\": \"" . $uniqid . "\", \"clasa\": \"" . $clasa . "\", \"id_user\": \"" . $_SESSION['userid'] . "\", \"timp\": \"" . $timp . "\", \"fisier_io\": \"" . $fisier . "\", \"id_concurs\": \"" . $id_concurs . "\", \"status\": \"0\", \"vm\": \"0\" }";
			else $message = "{ \"id_problema\": \"" . $id_problema . "\", \"id_eval\": \"" . $uniqid . "\", \"clasa\": \"" . $clasa . "\", \"id_user\": \"" . $_SESSION['userid'] . "\", \"timp\": \"" . $timp . "\", \"fisier_io\": \"" . $fisier . "\", \"id_concurs\": \"0\", \"status\": \"0\", \"vm\": \"0\" }";
			$queueRestProxy->createMessage("evalqueue", $message);
			header ("Location: monitor.php");
		}
		
		catch(ServiceException $e){
			// Handle exception based on error codes and messages.
			// Error codes and messages are here:
			// http://msdn.microsoft.com/en-us/library/windowsazure/dd179446.aspx
			$code = $e->getCode();
			$error_message = $e->getMessage();
			echo "<br/><br/>" . $code.": ".$error_message."<br />";
		}
	}   
        else
        {
            echo 'Eroare la adăugarea soluției <br />';
        }
    }
	}
?>
	<div id="continutul_nostru">
		
		<div class="row-fluid">
			<div class="span12">
				<form action="<?php echo "upload_solution.php?id=" . $id ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Adăugare soluție</legend>
                <ul type="none">
                    <li>
                        <input type="hidden" id="id_problema" name="id_problema" value="<?php echo $id; ?>" />
                        <label for="file">Fișier sursă (.cpp) maxim 1 MB:</label> 
                        <input type="file" name="file" value="" id="file" />
                    </li>
                </ul>
                <input type="submit" name="submit" value="Adăugare" />
                <p>
                    <a href="index.php">Anulare</a>
                </p>
            </fieldset>
        </form>
		<script>
			document.forms[0].addEventListener('submit', function( evt ) {
				var file = document.getElementById('file').files[0];

				if(file && file.size < 1048576) { // 10 MB (this size is in bytes)
					//Submit form        
				} else {
					//Prevent default and display error
					evt.preventDefault();
					alert('Eroare! Mărimea fișierului este mai mare de 1 MB!');
				}
			}, false);
		</script>
			</div>
		</div>
	</div>

<?php
    include ("Includes/footer.php");
 ?>
