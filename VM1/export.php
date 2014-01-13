<?php
$DB_TBLName = "users";
$filename = "clasament";
$dbc = mysql_connect('localhost', 'sampleuser', 'samplepassword')
    or die('Error connecting to MySQL server.');
    $db_name = mysql_select_db('infocloud', $dbc); 
   mysql_set_charset('UTF-8',$dbc);
   if ($_GET['clasa'] != NULL)
				$clasa = $_GET['clasa'];
	    if ($clasa != NULL)
	    {
			if (!is_numeric($_GET['clasa']) || (is_numeric($_GET['clasa']) && ($_GET['clasa'] < 5 || $_GET['clasa'] > 12)))
				header ("Location: index.php");
			else $query = "SELECT username, puncte, clasa, nume, prenume, scoala, localitate, judet FROM $DB_TBLName WHERE clasa = " . $clasa . " ORDER BY puncte DESC";
		}
		else
		{
			$query = "SELECT username, puncte, clasa, nume, prenume, scoala, localitate, judet FROM $DB_TBLName WHERE clasa != 0 ORDER BY clasa ASC, puncte DESC";
		}
    $result = mysql_query($query)
        or die('Error querying database.');
        $file_ending = "xls";
header("Content-Type: application/octet-stream; charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");
      /******* Formatting Excel *******/
  $sep = "\t";
  //--1. start of printing column names as names of MySQL fields
  for ($i = 0; $i < mysql_num_fields($result); $i++) {
$str = mysql_field_name($result,$i) . "\t";
$out = mb_convert_encoding($str, "UTF-16LE", "UTF-8");
//$out=mb_detect_encoding($str);
 echo $out;
}
  print("\n");
 //--2. start while loop to get data
 while($row = mysql_fetch_row($result))
              {
      $schema_insert = "";
 for($j = 0; $j < mysql_num_fields($result);$j++)
      {
                      if(!isset($row[$j]))
                                                                              $schema_insert .= "NULL".$sep;
                  elseif ($row[$j] != "")
                  { $word = mb_convert_encoding($row[$j], "UTF-16LE", "ASCII"); 
//$word = mb_detect_encoding($row[$j]);
$schema_insert .= $word.$sep;
// $schema_insert .= "$row[$j]".$sep; 
 }          else
 $schema_insert .= "".$sep;  }
  $schema_insert = str_replace($sep."$", "", $schema_insert);
 $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
 $schema_insert .= "\t";
// print(trim($schema_insert));
 $schema_insert = trim($schema_insert);
 $out1 = mb_convert_encoding($schema_insert, "UTF-16LE", "UTF-8");
 // print(trim($out1));
 echo $out1;
 print ("\n"); 
 }
    ?>
