<?php

     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    } 
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }


            if(isset($_POST['seconnecter']))
            {
                $user=htmlspecialchars($_POST['username']);
                $user=strip_tags($_POST['username']);

                $pswd=htmlspecialchars($_POST['pswd']);
                $pswd=strip_tags($_POST['pswd']);
                
                $requete_select= $bdd->prepare("SELECT * FROM utilisateurs WHERE username=? AND pswd=? ");
				$requete_select->execute(array($user,$pswd));
				$affiche=$requete_select->fetch();
				
				if($affiche)
				{
					SESSION_START();
					$_SESSION['username']=$affiche['username'];
					$_SESSION['pswd']=$affiche['pswd'];
					$_SESSION['email']=$affiche['email'];
					$_SESSION['id_user']=$affiche['id_user'];
					$_SESSION['nom']=$affiche['nom'];
					$_SESSION['prenom']=$affiche['prenom'];

					header('Location:acceuil.php');
				}
            }   
			
			if(isset($_POST['annuler']))
            {
				header('Location:acceuil.php');
			}

?>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<head>
	<title>Connexion à un compte</title>
   <!--Made with love by Mutiullah Samim -->
   
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="css/sign-in.css">

</head>


<body>


<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header bg-secondary">
				<h3>Authentification</h3>
			</div>
			<div class="card-body bg-light">
				<form action="" method="POST">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name="username" class="form-control" placeholder="Nom Utilisateur " >
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name="pswd" class="form-control" placeholder="Mot de Passe">
					</div>
					<div class="form-group">
						<input type="submit" name='annuler' value="Annuler" class="btn float-left login_btn" id="annuler">
						<input type="submit" name="seconnecter" value="Se connecter" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer bg-secondary">
				<div class="d-flex justify-content-center links">
					Vous n'avez pas de compte ? <a href="ssign-up.php"> Inscription </a>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>