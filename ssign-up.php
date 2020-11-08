<?php

     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    } 
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }
            
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
			
							header('Location:ssign-in.php');
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



<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<head>
	<title>Création de compte</title>
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
		<div class="card card_inscription">
			<div class="card-header">
				<h3>Inscription</h3>
			</div>
			<div class="card-body ">
				<form action="" method="POST">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name='username' class="form-control" placeholder="Entrez votre nom d'utilisateur" id="username" >
					</div>
					<span><small class="text-white ml-3"><?php echo $errors['username']; ?></small></span>
					
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-envelope"></i></span>
						</div>
						<input type="email" name='email' class="form-control" placeholder="Entrez votre e-mail" id="email">
					</div>
					<span><small class="text-white ml-3"> <?php echo $errors['email']; ?></small></span>
                    
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name='pswd' class="form-control" placeholder="Entrez votre mot de passe" id="pswd">
					</div>
					<span><small class="text-white ml-3 "><?php echo $errors['pswd']; ?></small></span>

                    
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name='confirm_pswd' class="form-control" placeholder="Confirmez votre mot de passe" id="confirm_pswd">
					</div>
					<span><small class="text-white ml-3"><?php echo $errors['confirm_pswd']; ?></small></span>

					
					<div class="form-group mt-3">
						<input type="submit" name='valider' value="Valider" class="btn float-right login_btn" id="valider">
                    </div>
                   
				</form>
            </div>
             
            <div class="card-footer">
				    <div class="d-flex justify-content-center links">
					    Vous avez déjà un compte ? <a href="ssign-in.php"> Se connecter</a>
				    </div>
			</div>
		</div>
	</div>
</div>

<!-- <script src="sign-up.js"></script> -->

</body>
</html>