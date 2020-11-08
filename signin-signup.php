<?php

     try {
		$bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    } 
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }

// La connexion d'un abonné 

			if(isset($_POST['connecter']))
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
			



// l'inscription d'un nouvel abonné

			$errors = array(
				'username' => '', 
				'email' => '',
				'pswd' => '',
				'confirm_pswd' => '',
			);

            if(isset($_POST['valider']))
            {
                $user=htmlspecialchars($_POST['username']);
                $user=strip_tags($_POST['username']);

                $email=htmlspecialchars($_POST['email']);
                $email=strip_tags($_POST['email']);

                $pswd=htmlspecialchars($_POST['pswd']);
                $pswd=strip_tags($_POST['pswd']);

                $confirm_pswd=htmlspecialchars($_POST['confirm_pswd']);
				$confirm_pswd=strip_tags($_POST['confirm_pswd']);

				$id_role='2';
				
				if(isset($user) && isset($email) && isset($pswd) && isset($confirm_pswd))
				{

					if( !empty($user) and !empty($email) and !empty($pswd) and !empty($confirm_pswd) )
					{
						if( $pswd == $confirm_pswd)
						{
							$requete_insert= $bdd->prepare("INSERT INTO utilisateurs(username,email,pswd,id_role) values (?,?,?,?) ");
							$requete_insert->execute(array($user,$email,$pswd,$id_role));

							$requete_select= $bdd->prepare("SELECT * FROM utilisateurs WHERE username=? AND pswd=? ");
							$requete_select->execute(array($user,$pswd));
							$affiche=$requete_select->fetch();
			
							SESSION_START();
								$_SESSION['username']=$affiche['username'];
								$_SESSION['pswd']=$affiche['pswd'];
								$_SESSION['email']=$affiche['email'];
								$_SESSION['id_user']=$affiche['id_user'];
								$_SESSION['nom']=$affiche['nom'];
								$_SESSION['prenom']=$affiche['prenom'];

							header('Location:acceuil.php');
							
						}elseif($confirm_pswd!=$pswd){
							$errors['confirm_pswd']="Les mots de passe ne correspondent pas";
						}

					}
					else{

						if($user==''){
							$errors['username']="Le nom d'utilisateur n'est pas valide";
						}

						if($email==''){
							$errors['email']="L'adresse mail n'est pas valide";
						}

						if($pswd==''){
							$errors['pswd']="Le mot de passe doit au minimum 3 caractères";
						}

						if($confirm_pswd==''){
							$errors['confirm_pswd']="Le mot de passe doit au minimum 3 caractères";
						}
						
					}	
			
				}
                
            }    

?>


<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/signin-signupp.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
</head>
<body>
<div class="container ">
	<form action="" method="POST">
		<div class="form sign-in">
		<h2>CONNECTEZ-VOUS</h2>
		<label>
			<span>Username</span>
			<input type="text" name="username">
			
			<span><small class="text-white ml-3"><?php echo $errors['username']; ?></small></span>
		</label>
		<label>
			<span>Mot de passe</span>
			<input type="password" name="pswd">
			<span><small class="text-white ml-3"><?php echo $errors['confirm_pswd']; ?></small></span>
		</label>
		<button class="submit" type="submit" name="connecter">se connecter</button>	
		 <p class="forgot-pass">Mot de passe oublier?</p> 
	</form>

    <div class="social-media">
        <ul>
          <li><img src="images/img-signin-signup/facebook.png"></li>
          <li><img src="images/img-signin-signup/twitter.png"></li>
          <li><img src="images/img-signin-signup/linkedin.png"></li>
          <li><img src="images/img-signin-signup/instagram.png"></li>
        </ul>
      </div>
    </div>

    <div class="sub-container">
      <div class="img">
        <div class="img-text m-up">
          <h2>êtes-vous un nouveau visiteur?</h2>
          <p>Inscrivez-vous et partager avec nous vos avis et vos commentaires!</p>
        </div>
        <div class="img-text m-in">
          <h2>Avez-vous déjà un compte?</h2>
          <p>connectez-vous pour lire nos récents articles !</p>
        </div>

        <div class="img-btn">
          <span class="m-up">Inscription</span>
          <span class="m-in">Connexion</span>
        </div>
      </div>

      <div class="form sign-up">
		<form action="" method="POST">
			<h2>CRÉER UN COMPTE</h2>
			<label>
				<span>Username</span>
				<input type="text" name="username">
			</label>
			<label>
				<span>Email</span>
				<input type="email" name="email">
			</label>
			<label>
				<span>Mot de passe</span>
				<input type="password" name="pswd">
			</label>
			<label>
				<span>Confirmation du mot de passe</span>
				<input type="password" name="confirm_pswd">
			</label>
			<button class="submit" type="submit" name="valider">S'inscrire</button>
		</form>
      </div>

    </div>
  </div>


<script type="text/javascript" src="js/signin-signup.js"></script>
</body>
</html>