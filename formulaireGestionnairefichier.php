<?php 	
session_start();

// affichage pour le developpement
echo "<div class=' erreurAffi colorRed'>";
//echo "<br>";
echo("session : "); print_r($_SESSION);
echo("<br>post : "); print_r($_POST);
echo("<br>get : "); print_r($_GET);

echo "<br></div>";


// donner un titre a ma session et au document 
if ( isset($_POST['titre']) and  
 $_POST['titre'] !="")
{
	$_SESSION['titre'] = 
	$_POST['titre'];
}


//ecrire dans le fichier si les champs du formulaire sont tous renseingnés 
if ( isset($_POST['nom']) and  $_POST['nom'] !="" and 
	isset($_POST['prenom']) and  $_POST['prenom'] !="" and
	isset($_POST['age']) and  $_POST['age'] !="" and
	isset($_POST['ville']) and  $_POST['ville'] !="" 
)
{
$titre = $_SESSION['titre'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$age = $_POST['age'];
$ville = $_POST['ville'];
ecrireFichier($titre,$nom,$prenom,$age,$ville);
header("Location: formulaireGestionnairefichier.php");
}


//Supprimer un ligne dans le fichier si suppLigne existe et est rempli 
// modification grace a l'ancre "x" 

if ( isset($_GET['suppLigne']) and  
 $_GET['suppLigne'] !="")
{
	$cheminFichier = $_SESSION['titre'].".txt";
	$ptr = fopen( $cheminFichier, 'r' );
	echo "<br>";
	$ligneS = $_GET['suppLigne'];

	$ressourceFichier = fread($ptr, filesize("$cheminFichier"));
    
   /* On a plus besoin du pointeur */
   fclose($ptr);
 
   $ressourceFichier = explode(PHP_EOL, $ressourceFichier); 
   /* PHP_EOL contient le saut à la ligne utilisé sur le serveur (\n linux, \r\n windows ou \r Macintosh */
    
   unset($ressourceFichier[$ligneS]); /* On supprime la ligne $ligneS */
   $ressourceFichier = array_values($ressourceFichier); /* Ré-indexe l'array */
    
   /* Puis on reconstruit le tout et on l'écrit */
   $ressourceFichier = implode(PHP_EOL, $ressourceFichier);
   $ptr = fopen("$cheminFichier", "w");
   fwrite($ptr, $ressourceFichier);

   header("Location: formulaireGestionnairefichier.php"); 
}


//modifier une ligne dans le fichier si modifLigne de GET existe et est rempli 
if ( isset($_GET['modifLigne']) and  
 $_GET['modifLigne'] !="")
{
	$cheminFichier = $_SESSION['titre'].".txt";
	$ptr = fopen( $cheminFichier, 'r' );
	echo "<br>";
	$ligneM = $_GET['modifLigne'];
	$_SESSION['modifLigne'] = $ligneM;

	if ( isset($_POST['0Mod']) and  $_POST['0Mod'] !="" and 
	isset($_POST['1Mod']) and  $_POST['1Mod'] !="" and
	isset($_POST['2Mod']) and  $_POST['2Mod'] !="" and
	isset($_POST['3Mod']) and  $_POST['3Mod'] !="" 
)
{
$titre = $_SESSION['titre'];
$nom = $_POST['0Mod'];
$prenom = $_POST['1Mod'];
$age = $_POST['2Mod'];
$ville = $_POST['3Mod'];
ecrireFichierLigne($titre,$nom,$prenom,$age,$ville,$ligneM);
unset($_SESSION['modifLigne']);
header("Location: formulaireGestionnairefichier.php");
}

	

	//echo "<div class='erreurAffi colorRed'> vous allez modifier la ligne : $ligneM </div>";
	//
	//

   //header("Location: formulaireGestionnairefichier.php"); 
}



//supprimer par post avec le petit formulaire form2 
if ( isset($_POST['ligneSupp']) and  $_POST['ligneSupp'] !="" && isset($_SESSION['titre']) and  $_SESSION['titre'] !="")
{
				
	$cheminFichier = $_SESSION['titre'].".txt";
	$ptr = fopen( $cheminFichier, 'r' );
	echo "<br>";
	$ligneS = $_POST['ligneSupp'];

	$ressourceFichier = fread($ptr, filesize("$cheminFichier"));
    
   /* On a plus besoin du pointeur */
   fclose($ptr);
 
   $ressourceFichier = explode(PHP_EOL, $ressourceFichier); 
   /* PHP_EOL contient le saut à la ligne utilisé sur le serveur (\n linux, \r\n windows ou \r Macintosh */
    
   unset($ressourceFichier[$ligneS]); /* On supprime la ligne $ligneS */
   $ressourceFichier = array_values($ressourceFichier); /* Ré-indexe l'array */
    
   /* Puis on reconstruit le tout et on l'écrit */
   $ressourceFichier = implode(PHP_EOL, $ressourceFichier);
   $ptr = fopen("$cheminFichier", "w");
   fwrite($ptr, $ressourceFichier);

   header("Location: formulaireGestionnairefichier.php");

// AUTRE METHODE AVEC FILE : 
// $filename="test.txt"; // nom du fichier
// $file = file($filename); // la fonction file, lit le fichier et met chaque ligne de celui-ci dans un tableau
// $nbligne = count($file); // compter nb lignes
// if($nbligne > 10)
// {
// unset($file[0]); // supprime la première ligne si le nb de lignes est supérieur à 10
// }

// file_put_contents($filename, $file); // réinsère les lignes dans le fichier, ça écrase l'ancien fichier.

}


//echo("<br>session : "); print_r($_SESSION);
//echo("<br>post : "); print_r($_POST);


// ecrire dans un fichier 
function ecrireFichier($t,$n,$p,$a,$v){
ecrireTitre($t);
$cheminFichier = $t.".txt";;
$ressourceFichier = fopen( $cheminFichier, 'a+' ); 	
fwrite($ressourceFichier, " $n  :  $p  :  $a  :  $v ".PHP_EOL);
fclose( $ressourceFichier);
}

function ecrireTitre($titre)
{	
	$cheminFichier = $titre.".txt";
	if(!file_exists($cheminFichier)){
		$ressourceFichier = fopen( $cheminFichier, 'a+' );
	}
	else{
		$ressourceFichier = fopen( $cheminFichier, 'r+' );
	}
fwrite($ressourceFichier, " exemple : ".$titre.PHP_EOL);
fclose( $ressourceFichier);
}

function ecrireFichierLigne($t,$n,$p,$a,$v,$l){

$cheminFichier = $t.".txt"; 
$file = file($cheminFichier); 
 //print_r($file);
 // la fonction file, lit le fichier et met chaque ligne de celui-ci dans 
 $file[$l] = " $n  :  $p  :  $a  :  $v ".PHP_EOL;
 //file_put_contents — Écrit un contenu dans un fichier
 //Revient à appeler les fonctions fopen(), fwrite() et fclose() successivement.
 file_put_contents($cheminFichier, $file);
}


?>

<?php
	include('header.php');
?>
	
<!--


<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Mot De Passe</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="styleGfichier.css">
	
	</head>
	<header>
		<ul>
			<li> connexion </li>

		</ul>

	</header> -->
	<link rel="stylesheet" type="text/css" href="styleGfichier.css">
	<main>
		<div class="menu">
			<a href="Destroy.php" name="destroyMenu" ><i class="fa fa-refresh" aria-hidden="true"></i></a>

			<a href="espaceMembre.php" name="deconectMenu" ><i class="fa fa-sign-out" aria-hidden="true"></i></a>
		</div>


		
		<form action="" name="formTest" method="POST" id="form1">
	<?php
	// si j'ai un titre en session j'affiche plus l'input et j'affiche le titre de la session 
		if(isset($_SESSION['titre'])){
			echo "<p>".$_SESSION['titre']."</p>";
		}
		else{	
	?>
				<input    type="text" name="titre" placeholder="Titre" required />
	<?php
			}
	?>		
			<br>
			<input    type="text" name="nom" placeholder="Nom" required/>
			<br>
			<input    type="text" name="prenom" placeholder="Prenom" required/>
			<br>
			<input    type="text" name="age" placeholder="Age" required/>
			<br>
			<input    type="text" name="ville" placeholder="Ville" required/>
			<br>
			<hr>
			<div class="container">
				<button type="submit"  name="valideSubmit" id="valide">Valider</button>
			</div>	
			
		</form>

	<?php
		// si j'ai un titre j'ouvre le fichier ou je le crèe 
		if ( isset($_SESSION['titre']) and  $_SESSION['titre'] !="")
			{

				//on va affecter a $ligneM la valeur de $_SESSION['modifLigne'] 
				if ( isset($_SESSION['modifLigne']) and  $_SESSION['modifLigne'] !=""){
					$ligneM = $_SESSION['modifLigne'];
				}else{
					$ligneM="-15";
				}
				 
				$cpt=0;
				$cheminFichier = $_SESSION['titre'].".txt";
				//on pourrait faire une boucle sur le nombre d'eléments et ensuite faire un tableau de n elements
				//$TableauModif=["nomMod","prenomMod","ageMod"];
				if(file_exists($cheminFichier))
				{
					//$ressourceFichier = fopen( $cheminFichier, 'r' ); 

					$fichier = file($cheminFichier);
					//echo "<br><br> <br>  <br> ";
					//print_r($fichier);
					//echo "<br>";
					echo "<table class='tableauResultat'>";
					foreach ($fichier as $key => $value) {
						$res = explode(":", $value);
						//print_r($res);
						//on a trouvé la ligne a modifier 
						if( $ligneM == $cpt){
							echo "<form name='form3' method='POST'>" ;
							echo "<tr class=''><td class=''> $cpt </td>";
						foreach ($res as $key => $value) 
						{   

	// exemple de retour : post : Array ( [0Mod] => Jeanette [1Mod] => marsius [2Mod] => 28 [3Mod] => atlanta [valideFormMod] => )
							echo "<td class='colorRed' ><input class='inputModif' type='text' name='".$key."Mod' placeholder=$value required/></td>";
						}
							echo "<td><button type='submit'  name='valideFormMod' id='valideMod'><i class='fa fa-check' aria-hidden='true'></i></button></td>";
							echo "<td>";
								echo   	"<a href='formulaireGestionnairefichier.php?suppLigne=".$cpt."' name='faSupp' ><i class='fa fa-times' aria-hidden='true'></i></a>";
							echo "</td><td>";
							echo   	"<a href='formulaireGestionnairefichier.php?modifLigne=".$cpt."' name='faModif' ><i class='fa fa-plus' aria-hidden='true'></i></a>";
							//<button type="submit"  name="valideSubmit" id="valide">Valider</button>
							
							echo "</tr>
								</form>
							 ";$cpt++;
						}else //sinon on affiche normalement 
						{


							echo "<tr><td> $cpt </td>";
						foreach ($res as $key => $value) {
							echo "<td class='tdText'> $value </td>";
						}

						echo "<td>";
							echo   	"<a href='formulaireGestionnairefichier.php?suppLigne=".$cpt."' name='faSupp' ><i class='fa fa-times' aria-hidden='true'></i></a>";
						echo "</td><td>";
						echo   	"<a href='formulaireGestionnairefichier.php?modifLigne=".$cpt."' name='faModif' ><i class='fa fa-plus' aria-hidden='true'></i></a>";
						echo "</tr> ";$cpt++;

				}
			}

			}
			//header("Location: formulaireGestionnairefichier.php"); 

		}
		
	?>
	</table>

	<?php
		// Si il y a un titre on affiche le second formulaire qui permet de choisir la ligne qu'on veut supprimer
		// On pourrait aussi ensuite l'utiliser pour modifier une ligne ou afficher une ligne spécifique. 
		if ( isset($_SESSION['titre']) and  $_SESSION['titre'] !="")
			{
	?>
		<!-- <form  action="" name="formSupprimeLigne" method="POST" id="form2">
			<input    type="text" name="ligneSupp" placeholder="Ligne à Supprimer" required/>
			<br>
			<hr>
			<button type="submit"  name="valideSubmitForm2" id="valideSf2">Valider</button>
		</form> -->
		<?php
		}
		?>
		

	
	</main>
	</body>
</html>