<!DOCTYPE html>

<?php 

require "libs/PhpMailer/class.phpmailer.php";
require "libs/scripts.php";

?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head> 
		<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
		<title>Test di Kolb</title>
		<link href="css/index.css" rel="stylesheet" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.js"></script>
		<script type="text/javascript" src="js/index.js"></script>
		<script type="text/javascript" src="js/jcanvas.min.js"></script>
	</head>

	<body>
		<h1>Il test di Kolb - Risultati</h1>
		
<?php

	if(file_exists("risultati.json")) {
		$elenco = json_decode(file_get_contents("risultati.json"), true);


?>
		
		<table class="results">
			<thead>
				<tr><td><span> </span></td><td>Nome e cognome</td><td>Classe</td><td>Email</td><td>Data</td><td>Stile</td><td><span> </span></td></tr>
			</thead>
			<tbody>
				
				<?php for($i = count($elenco) - 1; $i >= 0; $i--) { ?>
				
				<tr><td><?=(count($elenco) - $i) . ")"?></td><td><?=$elenco[$i]["nomecognome"]?></td><td><?=$elenco[$i]["classe"]?></td><td><?=$elenco[$i]["email"]?></td><td><?=date("d-m-Y H:i", $elenco[$i]["time"])?></td><td><?=$elenco[$i]["stile"]?></td><td><a href="index.php?id=<?=$elenco[$i]["id"]?>" target="_blank">Risultato</a></td></tr>
				
				<?php } ?>
				
			</tbody>
		</table>
		
<?php

	}

?>
		
		
	</body>
</html>