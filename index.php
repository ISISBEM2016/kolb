<!DOCTYPE html>

<?php 

require "libs/PhpMailer/class.phpmailer.php";
require "libs/scripts.php";

$introduzione = "<p>Secondo lo studioso <a href=\"https://it.wikipedia.org/wiki/David_Kolb\" title=\"David Kolb\" target=\"_blank\">David Kolb</a>, l&rsquo;apprendimento umano pu&ograve; essere rappresentato da <strong>un ciclo in quattro fasi</strong>:</p>"
	. "<ul>"
	. "<li><strong>Esperienza Concreta (CE):</strong> &egrave; la fase percettiva in cui si partecipa attivamente a un&rsquo;esperienza, ottenendo delle informazioni tramite le proprie sensazioni;</li>"
	. "<li><strong>Osservazione Riflessiva (RO):</strong> &egrave; la fase in cui si riflette criticamente sull&rsquo;esperienza vissuta;</li>"
	. "<li><strong>Concettualizzazione Astratta (AC):</strong> &egrave; la fase in cui si traggono conclusioni e modelli di carattere generale sulla base delle riflessioni svolte;</li>"
	. "<li><strong>Sperimentazione Attiva (AS):</strong> &egrave; la fase in cui si sperimentano, si mettono in pratica, le conclusioni alle quali si &egrave; giunti.</li>"
	. "</ul>"
	. "<p>Kolb non fissa una delle quattro fasi come punto di partenza obbligato (<strong>non si smette mai di apprendere, &egrave; un ciclo continuo!</strong>), ma ciascuno di noi predilige una di queste fasi come suo personale punto di partenza per l&rsquo;apprendimento.</p>";

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
	
<?php

if($_SERVER["REQUEST_METHOD"] == "POST" || (int)$_GET["id"] > 0) {
	
	$r = array();
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$nomecognome = trim($_POST["nomecognome"]);
		$classe = trim($_POST["classe"]);
		$email = trim($_POST["email"]);
		
		for($i = 10; $i <= 90; $i+=10) {
			if( $_POST[$i + 1]+$_POST[$i + 2]+$_POST[$i + 3]+$_POST[$i + 4] <> 10 ) {
				print "<h1>ERRORE</h1><p>Nella riga " . ($i / 10) . " non sono stati assegnati valori diversi.</p>";
				print "<h2><a href=\"javascript:window.history.back();\" title=\"Torna indietro\">Torna indietro</a> e ritenta :-)</h2>";
			exit;
			}		
		}
		
		for($i = 1; $i <= 9; $i++) {
			for($j = 1; $j <= 4; $j++) {
				$r[$i . $j] = $_POST[$i . $j];
			}
		}
		
		$risposte = array();
		for($i = 1; $i <= 9; $i++) {
			$r = array();
			for($j = 1; $j <= 4; $j++) {
				array_push($r, $_POST[$i . $j]);
			}
			array_push($risposte, $r);
		}
		
		if(file_exists("risultati.json")) {
			$elenco = json_decode(file_get_contents("risultati.json"), true);
		}
		else {
			$elenco = array();
		}
		
		if(count($elenco) > 0) {
			$prog = (int)$elenco[count($elenco) - 1]["id"] + 1;
		}
		else {
			$prog = 1;
		}
		
		$nuovo = array(
			"id" => $prog,
			"nomecognome" => $nomecognome,
			"classe" => $classe,
			"email" => $email,
			"tipo" => $tipo,
			"stile" => $stile,
			"risposte" => $risposte,
			"time" => time()
		);
		
		array_push($elenco, $nuovo);
		file_put_contents("risultati.json", json_encode($elenco));
		
		sendMail(
			"Il test di Kolb", 
			"vincenzo.caico@goiss.it", 
			"", 
			$email, 
			utf8_decode("Il risultato del tuo test di Kolb"),
			"<html><body>" 
				. $introduzione
				. "<p>In base ai risultati del tuo test, privilegi la fase di:</p>"
				. $tipo_testo 
				. "<p>Il tuo stile di apprendimento &egrave;</p>"
				. $stile_testo 
				. "</body></html>",
			true
		);
	}
	else if((int)$_GET["id"] > 0) {

		if(file_exists("risultati.json")) {
			$elenco = json_decode(file_get_contents("risultati.json"), true);
			
			$e = searchByFieldArray($elenco, "id", (int)$_GET["id"]);
			$nomecognome = $e["nomecognome"];
			
			for($i = 1; $i <= 9; $i++) {
				for($j = 1; $j <= 4; $j++) {
					$r[$i . $j] = $e["risposte"][$i - 1][$j - 1];
				}
			}
		}
		else {
			$r = array();
		}
		
		
	}

	$EC = $r["21"] + $r["31"] + $r["41"] + $r["51"] + $r["71"] + $r["81"];
	$OR = $r["12"] + $r["32"] + $r["62"] + $r["72"] + $r["82"] + $r["92"];
	$CA = $r["23"] + $r["33"] + $r["43"] + $r["53"] + $r["83"] + $r["93"];
	$SA = $r["14"] + $r["34"] + $r["64"] + $r["74"] + $r["84"] + $r["94"];
	
	$SAOR = $SA - $OR;
	$CAEC = $CA - $EC;

	if($SAOR > 2.5) {
		if($CAEC < 3.5) {
			$tipo1 = 1;
			if($SAOR * $SAOR > $CAEC * $CAEC) {
				$tipo2 = "D";
			} 
			else {
				$tipo2 = "A";
			}
		} 
		else {
			$tipo1 = 3;
			if($SAOR * $SAOR > $CAEC * $CAEC) {
				$tipo2 = "D";
			} 
			else {
				$tipo2 = "C";
			}
		}
	} 
	else {
		if($CAEC < 3.5) {
			$tipo1 = 2;
			if($SAOR * $SAOR > $CAEC * $CAEC) {
				$tipo2 = "B";
			} 
			else {
				$tipo2 = "A";
			}
		} 
		else {
			$tipo1 = 4;
			if($SAOR * $SAOR > $CAEC * $CAEC) {
				$tipo2 = "B";
			} 
			else {
				$tipo2 = "C";
			}
		}	
	}
	
?>

		<h1>Il test di Kolb - Risultati<?php if(strlen($nomecognome) > 0) { echo " di " . $nomecognome; } ?></h1>
		<div class="sinistra" id="risultati">
			<p>Privilegi la fase di:</p>
	
<?php 

	if($tipo2 == "A") { 
		$tipo = "Esperienza concreta";
		$tipo_testo = "<h2>ESPERIENZA CONCRETA (A)</h2>"
			. "<ul>"
			. "<li>Racconti le tue esperienze proponendole come soluzione</li>"
			. "<li>Ti piace fare, utilizzare strumenti, documentarti</li>"
			. "<li>Provi sempre a buttar gi&ugrave; delle idee</li>"
			. "<li>Sei attento alle sensazioni che provi</li>"
			. "<li>Esprimi le tue emozioni e i tuoi sentimenti</li>"
			. "<li>Racconti come hai vissuto l&rsquo;esperienza pi&ugrave; che il risultato che hai prodotto</li>"
			. "</ul>";
		print $tipo_testo;
	}
	else if($tipo2 == "B") {
		$tipo = "Osservazione riflessiva";
		$tipo_testo = "<h2>OSSERVAZIONE RIFLESSIVA (B)</h2>"
			. "<ul>"
			. "<li>Parli poco, ma ascolti e chiedi informazioni</li>"
			. "<li>Osservi, prendi nota</li>"
			. "<li>Fai ipotesi, analizzi i pro e i contro</li>"
			. "<li>Rifletti, analizzi tutti gli elementi e poi li selezioni</li>"
			. "<li>Non ti interessi molto agli aspetti realizzativi</li>"
			. "<li>Leggi attentamente, cerchi logiche interne</li>"
			. "<li>Fai schemi e mappe concettuali</li>"
			. "<li>Approfondisci molto</li>"
			. "</ul>";
		print $tipo_testo;
	}
	else if($tipo2 == "C") {
		$tipo = "Concettualizzazione astratta";
		$tipo_testo = "<h2>CONCETTUALIZZAZIONE ASTRATTA (C)</h2>"
			. "<ul>"
			. "<li>Analizzi, organizzi, valuti e selezioni</li>"
			. "<li>Raccogli pi&ugrave; spunti per elaborare un modello</li>"
			. "<li>Non esprimi le tue emozioni, ma esponi le tue deduzioni</li>"
			. "<li>Ti affidi di pi&ugrave; al pensiero e alla riflessione che alle sensazioni</li>"
			. "<li>Cerchi criteri di confronto</li>"
			. "<li>Puoi non arrivare a formulare ipotesi concrete</li>"
			. "</ul>";
		print $tipo_testo;
	}
	else if($tipo2 == "D") {
		$tipo = "Sperimentazione attiva";
		$tipo_testo = "<h2>SPERIMENTAZIONE ATTIVA (D)</h2>"
			. "<ul>"
			. "<li>Rispetti i mandati e i tempi di consegna</li>"
			. "<li>Valuti attentamente costi e benefici</li>"
			. "<li>Collabori con gli altri</li>"
			. "<li>Simuli: fai prove, dai scadenze al tuo lavoro</li>"
			. "<li>Sei attento a gestire i tempi e le risorse a tua disposizione</li>"
			. "<li>Assumi volentieri delle responsabilit&agrave;</li>"
			. "</ul>";
		print $tipo_testo;
	}
	
?>

			<p>Il tuo stile di apprendimento &egrave;:</p>

<?php
	
	if($tipo1 == 1) {
		$stile = "Adattivo";
		$stile_testo = "<h2>ADATTIVO (PRODUTTIVO)</h2>"
			. "<p>Punti di forza:</p>"
			. "<ul>"
			. "<li>Sei abile ad apprendere in modo naturale attraverso l&rsquo;esperienza concreta</li>"
			. "<li>Tendi a proporti traguardi, sfide e obiettivi da raggiungere</li>"
			. "<li>Hai la capacit&agrave; di agire seguendo le tue intuizioni e provi pi&ugrave; soluzioni prima di giungere a quella ottimale</li>"
			. "<li>Hai abilit&agrave; sociale nel lavorare in gruppo e ad affidarti agli altri per le parti del compito che sono di loro competenza</li>"
			. "<li>Ti adatti con naturalezza a situazioni nuove e impreviste</li>"
			. "<li>Sei abile ad imparare osservando dagli altri e chiedendo aiuto, se necessario</li>"
			. "</ul><p>Punti di debolezza:</p><ul>"
			. "<li>Tendi ad improvvisare e a cimentarti su pi&ugrave; compiti risultando, a volte, superficiale</li>"
			. "<li>Propendi a non valutare la qualit&agrave; del lavoro svolto e a non ritornare su di esso per perfezionarlo</li>"
			. "<li>Tendi ad essere troppo impaziente e impulsivo</li>"
			. "</ul>";
		print $stile_testo;
	}
	else if($tipo1 == 2) {
		$stile = "Divergente";
		$stile_testo = "<h2>DIVERGENTE (SENSIBILE)</h2>"
			. "<p>Punti di forza:</p>"
			. "<ul>"
			. "<li>Sei bravo a osservare una situazione concreta da diversi punti di vista e intuirne i collegamenti ottenendo una sua visione completa e coerente</li>"
			. "<li>Sei abile nel selezionare i dati e gli aspetti rilevanti all&rsquo;interno di una massa complessa e confusa di informazioni</li>"
			. "<li>Propendi ad avere un vasto campo di interessi culturali e tendi a interessarti ad aspetti molteplici della conoscenza e raccogliere sempre nuove informazioni</li>"
			. "<li>Hai una forte capacit&agrave; immaginativa e l&rsquo;attitudine a generare idee nuove e originali</li>"
			. "<li>Propendi al lavoro di gruppo e all&rsquo;interazione con gli altri, svolgendo spesso il ruolo di coordinatore</li>"
			. "<li>Sei aperto a idee innovative e originali</li>"
			. "</ul><p>Punti di debolezza:</p><ul>"
			. "<li>A volte tendi a essere dispersivo e a dissipare inutilmente le tue energie fisiche e mentali</li>"
			. "<li>Tendi a deconcentrarti spesso durante lo svolgimento di un compito</li>"
			. "<li>A volte tralasci i dettagli per preferire la visione d&rsquo;insieme delle situazioni</li>"
			. "</ul>";
		print $stile_testo;
	}
	else if($tipo1 == 3) {
		$stile = "Convergente";
		$stile_testo = "<h2>CONVERGENTE (DECISIONALE)</h2>"
			. "<p>Punti di forza:</p>"
			. "<ul>"
			. "<li>Sei abile nel trovare applicazioni pratiche alle idee e alle teorie formali</li>"
			. "<li>Propendi alla realizzazione di problemi pratici e quesiti tecnici che hanno un&rsquo;unica soluzione identificabile e ben definita</li>"
			. "<li>Ha la capacit&agrave; di assumere le decisioni migliori per atturare nella pratica la soluzione di un problema</li>"
			. "<li>Sei capace di ottenere ottimi risultati in test strutturati con una singola risposta corretta</li>"
			. "<li>Hai una buona capacit&agrave; di ragionamento ipotetico-deduttivo</li>"
			. "<li>Tendi a specializzarti altamente in un ramo specifico della conoscenza</li>"
			. "</ul><p>Punti di debolezza:</p><ul>"
			. "<li>Tendi ad avere scarsi interessi perch&eacute; convergi verso conoscenze specifiche</li>"
			. "<li>Nello svolgimento di un compito, tendi a tralasciare le relazioni personali e a non sviluppare le competenze sociali</li>"
			. "<li>Hai una scarsa propensione alle idee innovative ed originali</li>"
			. "</ul>";
		print $stile_testo;
	}
	else if($tipo1 == 4) {
		$stile = "Assimilativo";
		$stile_testo = "<h2>ASSIMILATIVO (TEORICO)</h2>"
			. "<p>Punti di forza:</p>"
			. "<ul>"
			. "<li>Sei abile nel recepire un ampio insieme di informazioni e sintetizzarle in modo formale in un modello teorico</li>"
			. "<li>Sai manipolare con efficacia e coerenza i modelli analitici</li>"
			. "<li>Sei abile a concentrarti con efficacia sui concetti astratti e teorici</li>"
			. "<li>Apprendi con naturalezza da testi e illustrazioni, secondo uno stile formale</li>"
			. "<li>Sei capace di soffermarti per lungo tempo su concetti e idee, analizzando la loro coerenza logica e formale</li>"
			. "<li>Sei abile nel porti obiettivi precisi e pianificarne i dettagli</li>"
			. "</ul><p>Punti di debolezza:</p><ul>"
			. "<li>Quando svolgi un compito tendi a mettere da parte il contatto con le persone, fino ad isolarti</li>"
			. "<li>Propendi a tralasciare gli aspetti pratici delle elaborazioni teoriche</li>"
			. "<li>Hai una certa riluttanza iniziale nell&rsquo;affrontare i compiti con decisione</li>"
			. "</ul>";
		print $stile_testo;
	}

?>

		</div>
		<input id="kEC" type="hidden" value="<?=$EC?>" />
		<input id="kOR" type="hidden" value="<?=$OR?>" />
		<input id="kCA" type="hidden" value="<?=$CA?>" />
		<input id="kSA" type="hidden" value="<?=$SA?>" />
		<input id="tipo1" type="hidden" value="<?=$tipo1?>" />
		<input id="tipo2" type="hidden" value="<?=$tipo2?>" />
		<div class="destra" id="mappa">
			<canvas id="canvas"></canvas>
		</div>
	
<?php
	
}
else {

?>


		<h1>Il test di Kolb</h1>
		<div id="introduzione" class="sinistra">
		
<?php 

print $introduzione;

?>

			<p>Questo test ti permetter&agrave; di scoprire quale di queste fasi tu privilegi e qual &egrave; il tuo personale <strong>stile di apprendimento</strong>.</p>
		</div>
		<div class="destra">
			<div class="image"><img src="images/ciclokolb.png" width="" height="420" alt="418" /></div>
		</div>
		<div id="istruzioni">
			<h2>Istruzioni</h2>
			<p>Qui di seguito trovi <strong>9 gruppi di affermazioni</strong>. Ciascuno gruppo comprende <strong>4 diverse affermazioni</strong> poste sulla stessa riga.</p>
			<p>Per ciascun gruppo, assegna <strong>un punteggio da 1 a 4</strong> alle 4 affermazioni:</p>
			<ul>
				<li><strong>4</strong> a quella che meglio caratterizza il tuo modo abituale di affrontare e risolvere i problemi</li>
				<li><strong>3</strong> a quella immediatamente successiva</li>
				<li><strong>2</strong> alla modalit&agrave; che utilizzi raramente</li>
				<li><strong>1</strong> a quella che meno corrisponde al tuo stile</li>
			</ul>
			<p>Assicurati di dare <strong>un valore numerico diverso a ciascuna affermazione presente su una stessa riga</strong>.</p>
		</div>
		<form method="POST" action="<?=$_SERVER["PHP_SELF"]?>" onsubmit="return checkForm();">
			<table class="test">
				<tbody>
					<tr>
						<td><p>1</p></td>
						<td>Cerco di cogliere le differenze</td>
						<td>
							<select class="answer" name="11" value="<?=$_POST["11"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Procedo per ipotesi e tentativi</td>
						<td>
							<select class="answer" name="12" value="<?=$_POST["12"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi lascio coinvolgere</td>
						<td>
							<select class="answer" name="13" value="<?=$_POST["13"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Pratico</td>
						<td>
							<select class="answer" name="14" value="<?=$_POST["14"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>2</p></td>
						<td>Prendo in considerazione le idee altrui</td>
						<td>
							<select class="answer" name="21" value="<?=$_POST["21"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi dedico solo al problema</td>
						<td>
							<select class="answer" name="22" value="<?=$_POST["22"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Effettuo analisi</td>
						<td>
							<select class="answer" name="23" value="<?=$_POST["23"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Rimango imparziale</td>
						<td>
							<select class="answer" name="24" value="<?=$_POST["24"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>3</p></td>
						<td>Mi baso su sensazioni</td>
						<td>
							<select class="answer" name="31" value="<?=$_POST["31"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Faccio attente osservazioni</td>
						<td>
							<select class="answer" name="32" value="<?=$_POST["32"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi baso sulla ragione</td>
						<td>
							<select class="answer" name="33" value="<?=$_POST["33"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Cerco di capire facendo</td>
						<td>
							<select class="answer" name="34" value="<?=$_POST["34"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>4</p></td>
						<td>Solitamente accetto</td>
						<td>
							<select class="answer" name="41" value="<?=$_POST["41"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Provo anche rischiando</td>
						<td>
							<select class="answer" name="42" value="<?=$_POST["42"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Valuto pro e contro</td>
						<td>
							<select class="answer" name="43" value="<?=$_POST["43"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Cerco di divenire pienamente cosciente</td>
						<td>
							<select class="answer" name="44" value="<?=$_POST["44"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>5</p></td>
						<td>Privilegio l&rsquo;intuizione</td>
						<td>
							<select class="answer" name="51" value="<?=$_POST["51"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi baso sui risultati</td>
						<td>
							<select class="answer" name="52" value="<?=$_POST["52"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Seguo un processo logico</td>
						<td>
							<select class="answer" name="53" value="<?=$_POST["53"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi pongo molte domande</td>
						<td>
							<select class="answer" name="54" value="<?=$_POST["54"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>6</p></td>
						<td>Procedo per astrazione</td>
						<td>
							<select class="answer" name="61" value="<?=$_POST["61"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Esamino i fatti</td>
						<td>
							<select class="answer" name="62" value="<?=$_POST["62"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Sono concreto</td>
						<td>
							<select class="answer" name="63" value="<?=$_POST["63"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Preferisco l&rsquo;agire</td>
						<td>
							<select class="answer" name="64" value="<?=$_POST["64"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>7</p></td>
						<td>Guardo all&rsquo;oggi</td>
						<td>
							<select class="answer" name="71" value="<?=$_POST["71"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Rifletto sui fatti</td>
						<td>
							<select class="answer" name="72" value="<?=$_POST["72"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Guardo al domani</td>
						<td>
							<select class="answer" name="73" value="<?=$_POST["73"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi attengo ai fatti</td>
						<td>
							<select class="answer" name="74" value="<?=$_POST["74"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>8</p></td>
						<td>Mi baso sulla mia esperienza</td>
						<td>
							<select class="answer" name="81" value="<?=$_POST["81"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Mi baso sull&rsquo;osservazione</td>
						<td>
							<select class="answer" name="82" value="<?=$_POST["82"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Procedo per concetti</td>
						<td>
							<select class="answer" name="83" value="<?=$_POST["83"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Privilegio la sperimentazione</td>
						<td>
							<select class="answer" name="84" value="<?=$_POST["84"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><p>9</p></td>
						<td>Mi applico con energia</td>
						<td>
							<select class="answer" name="91" value="<?=$_POST["91"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Procedo cautamente</td>
						<td>
							<select class="answer" name="92" value="<?=$_POST["92"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Procedo seguendo la ragione</td>
						<td>
							<select class="answer" name="93" value="<?=$_POST["93"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
						<td>Sono responsabile</td>
						<td>
							<select class="answer" name="94" value="<?=$_POST["94"]?>">
								<option value="0">?</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="9" class="email">
							<label for="nomecognome">Nome e cognome:</label>
							<input type="text" name="nomecognome" />
							<label for="classe">Classe:</label>
							<input type="text" name="classe" class="mini" />
							<label for="email">Il tuo indirizzo email:</label>
							<input type="text" name="email" />
						</td>
					</tr>
				</tbody>
			</table>
			<div class="invia"><input type="submit" name="submit" value=" Invia "/></div>
		</form>
		
<?php 

}

?>

		<br /><br />
	</body>
</html>