<?php session_start(); 
include('header.php');
?>
<link rel="stylesheet" type="text/css" href="styleConnexionMembre.css">
<main>
	<?php 
		//print_r($_POST);
		$tabRetour = $_POST;
		$testredirection = "false";
		$tabInterdit=array('<','>','*','\\','/');
		$tabAdmin= array();
		$tabAdmin=['admin1'=>array('identifiant' => 'superRoot', 'mdp'=>'superRootmdp'),
					'admin2'=>array('identifiant' => 'superRoot1', 'mdp'=>'superRootmdp1'),
					'admin3'=>array('identifiant' => 'superRoot2', 'mdp'=>'superRootmdp2')];

		$tabUtilisateur=['utilisateur1'=>array('identifiant' => 'ident1', 'mdp'=>'mdpmdp'),
						'utilisateur2'=>array('identifiant' => 'ident2', 'mdp'=>'mdpmdp1'),
						'utilisateur3'=>array('identifiant' => 'ident3', 'mdp'=>'mdpmdp2')];

		//print_r($tabAdmin);
		echo "<br>";

		function taille($var){
			if ( !isset($var)){
				return 0;
			}
			else 
			{ 
				return strlen($var);
			}

		}

		function verifConnexion($id,$mdp,&$tabAdmin,&$tabUtilisateur){
			foreach ($tabAdmin as $key => $value) 
			{
				if ( in_array($id,$value) ) {
					if ($value['mdp'] == $mdp){
						return "Admin";
					}
				}
			}
			foreach ($tabUtilisateur as $key => $value) 
			{
				if ( in_array($id,$value) ) {
					if ($value['mdp'] == $mdp){
						return "utilisateur";
					}
				}

			}
		}

		function decoupeEtoile($text)
		{
			if ((preg_match("/#$/",$text)))
				{
					return substr($text,0,"-2");
				}else
				{
					return $text;
				}
		}

		function veriText($key,$text,&$tabRetour,&$testredirection,&$tabInterdit)
		{
			
			if((taille($text) <2 ) or (is_numeric($text)) or (preg_match("/#$/",$text))  )
				{
					$tabRetour[$key]=$text." #";
					$testredirection = "La taille ".$text."  : ". taille($text);
				}
				else
				{
					for ($i=0; $i < count($tabInterdit); $i++) 
					{	
						//$testredirection = "$text"."  ".$tabInterdit[$i];						
						$pos = strpos($text, $tabInterdit[$i]);
						if(is_numeric($pos)) 
						{	
							$tabRetour[$key]=$text." #";
							$testredirection = "$text"."  ".$tabInterdit[$i];
						}
									
					}
							
				}
							
		}

		function veriMdp($key,$text,&$tabRetour,&$testredirection,&$tabInterdit)
		{
			//$text=htmlentities($text);
			decoupeEtoile($text);
			//la taille du mot de passe est comprise entre 6 et 15 
			if((taille($text) <=5 ) || (taille($text) >15 ))
			{
				$tabRetour[$key]=$text." #";
				$testredirection = "La taille ".$text."  : ". taille($text);
							
			}
			else
				{
					for ($i=0; $i < count($tabInterdit); $i++) 
					{	
						//$testredirection = "$text"."  ".$tabInterdit[$i];						
						$pos = strpos($text, $tabInterdit[$i]);
						if(is_numeric($pos)) 
						{	
							$tabRetour[$key]=$text." #";
							$testredirection = "$text"."  ".$tabInterdit[$i];
						}
									
					}
							
				}
		}
		
			?>
		<form action="" name="formTest" method="POST">
			

			<input  type="text" name="identifiant" placeholder="Identifiant" 
				
				<?php 
					if (isset($tabRetour['identifiant'])){ 
						echo "value='".$tabRetour['identifiant']."'";
						veriText('identifiant',$tabRetour['identifiant'],$tabRetour,$testredirection,$tabInterdit);
					}
					if (isset($tabRetour['identifiant'])) 
						{
							$valeurNom=htmlentities($_POST['identifiant']);
							if ((preg_match("/#$/",$tabRetour['identifiant']))) 
							{
								echo " class='ColorRed'";
							}
							else
								{
									echo " class='ColorOlive'";
								} 
						}
					?> 

			required>
			
			
			<input <?php if (isset($_POST['Mdp'])) {
					echo "value='".$_POST['Mdp']."'";
					
					}
					if (isset($tabRetour['Mdp'])) 
						{
							veriMdp('Mdp',$tabRetour['Mdp'],$tabRetour,$testredirection,$tabInterdit); 
							if ((preg_match("/#$/",$tabRetour['Mdp']))) 
							{
								echo  " class='ColorRed'";
							}
							else
								{
									echo " class='ColorOlive'";

								} 
						}

					?> 
					
					type="password" name="Mdp" placeholder="Mot de Passe" required>


			
			<br>
			<div class="container">
				<button type="submit"  name="valideSubmit" id="valide">Valider</button>
			</div>	
			<br>
			<br>
			<?php 

					
					if(isset($_GET)){
						
						//print_r($_GET);
						if(isset($_GET['destroy']) && $_GET['destroy']=='true' ){
							session_destroy();
							header('location:espaceMembre.php');
							exit();
						}
					}




					if (($testredirection == "false") && (isset($_POST['identifiant'])))
					{	
						
						$_SESSION = $_POST;
						$_SESSION['jgqdfskg']=1;
						//print_r($_SESSION);
						//echo $_COOKIE['PHPSESSID'];
						$id=$tabRetour['identifiant'];

						$var = verifConnexion($tabRetour['identifiant'],$tabRetour['Mdp'],$tabAdmin,$tabUtilisateur);
						if ( $var === "Admin")
						{
							echo "<br>	<div id='validation' class='ColorPurple' >
										<h3> Bienvenue $id en tant qu'Administrateur </h3>
										</div><br>";
							$_SESSION['utilisateur']='root';
							header('Location: formulaireGestionnairefichier.php');
						}
						elseif( $var === "utilisateur")
						{


						echo "<br><div id='validation' class='ColorOlive' ><h3> Bienvenue $id </h3>
							</div><br>";
							$_SESSION['utilisateur']='uti';
							header('Location: formulaireGestionnairefichier.php');
						}
						
						effaceTout();
					}
					else{
						if (isset($_POST['identifiant']))
						{


							echo "<br><div id='validation' class='ColorPurple' ><h3> Invalide à cause de :<br>'".$testredirection."' </h3>
							</div><br>";
							$_SESSION['utilisateur']='uti';
							}	
						effaceTout();
					}

					function effaceTout()
					{
						unset($_SESSION);
						unset($_POST);
						unset($_GET);
					}
					
			?>
					
					
			
		</form>
	</main>
	
</body>
</html>