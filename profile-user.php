<?php

     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    } 
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }
     SESSION_START();

     if(isset($_POST['valider']))
     {
         $nom=htmlspecialchars($_POST['nom']);
         $nom=strip_tags($_POST['nom']);

         $prenom=htmlspecialchars($_POST['prenom']);
         $prenom=strip_tags($_POST['prenom']);

         $user=htmlspecialchars($_POST['username']);
         $user=strip_tags($_POST['username']);

         $email=htmlspecialchars($_POST['email']);
         $email=strip_tags($_POST['email']);

         $pswd=htmlspecialchars($_POST['pswd']);
         $pswd=strip_tags($_POST['pswd']);

         $avatar=htmlspecialchars($_POST['avatar']);
         $avatar=strip_tags($_POST['avatar']);

          $requete_update= $bdd->prepare("UPDATE utilisateurs SET nom=?,prenom=?,username=?,pswd=?,email=? where username=?");
          $requete_update->execute(array($nom,$prenom,$user,$pswd,$email,$_SESSION['username']));
   
          
        //pour sauvegarder l'image dans un dossier 'images' et ajouter le lien sur la table utilisateurs à la base de donnée
        if(isset($_FILES['avatar']) and $_FILES['avatar']['error']==0)
        {
            if($_FILES['avatar']['size']< 1000000)
            {
                $list_extensions=array('png','jpg','jpeg','gif');
                $details=pathinfo($_FILES['avatar']['name']);
                $extension=$details['extension'];

                $resultat=in_array($extension,$list_extensions);

                if($resultat)
                {   
                    $chemin='images/img-avatar'.$details['basename'];
                    move_uploaded_file($_FILES['avatar']['tmp_name'],$chemin);

                    $requette=$bdd->prepare("UPDATE utilisateurs SET avatar=? WHERE username=? ");
                    $requette->execute(array($chemin,$_SESSION['username']));
                }
            }
        }

        $_SESSION['username']=$_POST['username'];
        $_SESSION['nom']=$_POST['nom'];
        $_SESSION['prenom']=$_POST['prenom'];
        $_SESSION['email']=$_POST['email'];
        $_SESSION['pswd']=$_POST['pswd'];
        $_SESSION['avatar']=$_POST['avatar'];

        header('Location:profile-user.php');

     }  

    // la selection pour l'affichage de mon avatar
    $requette1=$bdd->prepare('SELECT * FROM utilisateurs WHERE username=?');
     $requette1->execute(array($_SESSION['username']));
     $affiche_avatar=$requette1->fetch();


     // faire une recherche sur un mot dans la table article :
	 if(isset($_POST['search']))
	 {
		
		$_SESSION['text-search']=$_POST['text-search'];
		header('Location:search.php');
	 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Mon profile</title>
    <link rel="stylesheet" type="text/css" href="css/accueil.css">
    <link rel="stylesheet" type="text/css" href="css/users.css">
    <link rel="stylesheet" type="text/css" href="css/article.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.0/css/all.css" integrity="sha384-OLYO0LymqQ+uHXELyx93kblK5YIS3B2ZfLGBmsJaUyor7CpMTBsahDHByqSuWW+q" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Raleway">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 


</head>

<body>


<header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand text-danger" href="acceuil.php">Maro<b class="text-success">CϽ</b>ulture</a> 	
	<!-- Collection of nav links, forms, and other content for toggling -->
	 <div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">
		<div class="navbar-nav nnav">
            <a href="histoire.php" class="nav-item nav-link">Histoire</a>
			<a href="voyage.php" class="nav-item nav-link">Voyage</a>			
			<a href="gastronomie.php" class="nav-item nav-link">Gastronomie</a>
			<a href="artisanat.php" class="nav-item nav-link">Artisanat</a>
			<a href="folklores.php" class="nav-item nav-link">Folklores</a>
			<a href="personnalites.php" class="nav-item nav-link">Personnalités</a>
        </div>
		<div class="navbar-nav m-auto pl-5">

			<div class="navbar-form-wrapper">
                   <form class="navbar-form form-inline" method="POST">
						<div class="input-group search-box">
							<input type="text" class="form-control" placeholder="Chercher un article" name="text-search">
							<div class="input-group-append">
								<span class="input-group-text">
									<!-- <input type="button" style="width:30px;" name="search"> -->
									<button style="width:30px;" name="search"><i class="material-icons pt-1 ">&#xE8B6;</i></button>
								</span>
							</div>
						</div>
					</form>
		    </div>

			<div class="collapse navbar-collapse dropdown justify-content-end" id="navbarTogglerDemo02" >		
				<div>
					<a href="#" class="btn btn-transparent dropdown-toggle text-uppercase" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php  echo $_SESSION['username'];?></a>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="profile.php" >MON PROFILE</a>
						<a class="dropdown-item" href="deconnexion.php">SE DÉCONNECTER</a>
					</div>
				</div>
				<img src="<?php echo $affiche_avatar['avatar'] ?>" alt="" class="rounded-circle "   style="width=45px;height:40px;">
			</div>		

		</div>

	</nav>
	
</header>

<main>

    <div class="container-fluid p-0 mt-5 text-uppercase">
        <div class="row justify-content-center">
           <div class="col-md-1"></div>
           <div class="col-md-8">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a class="nav-link active bg-info" href="profile-user.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="articles_user.php">Articles</a>
                    </li>
                </ul>                
           </div>
           <div class="col-md-1"></div>        
        </div>    

    </div> 


    <div class="container text-uppercase">
        <div class="form-row col-md-12 mt-3" > 
            <div class="col-md-5 mr-3" >
                <h1 class="m-5" >Mon Profile</h1>
            </div>
            <div  class=" col-md-3 ">

            </div>
            <div class="col-md-1 ml-5" >
                <img src="<?php echo $affiche_avatar['avatar'] ?>" alt="" class="img-thumbnail rounded-circle ml-5" style="position:absolute" width=100%>
            </div>
        </div>    
        <div class="col-md-10 ml-5 pl-5" >    
            <form  action="" method="POST" enctype="multipart/form-data">

                <div class="form-row col-md-12 mt-3" > 
                    <div class="form-group col-md-5 mr-5">
                        <label for=""><strong>Nom</strong></label>
                        <input type="text" class="form-control" id="inputNom"  name="nom" value=<?php  echo $_SESSION['nom'];?> >
                    </div>
                    <div class="form-group col-md-5 ml-5" >
                        <label for=""><strong> Prénom</strong></label>
                        <input type="text" class="form-control" id="inputPrenom"  name="prenom" value=<?php  echo $_SESSION['prenom'];?> >
                    </div>
                </div>
                        
                <div class="form-row col-md-12 mt-3">
                    <div class="form-group col-md-5 mr-5">
                        <label for=""><strong>Nom Utilisateur</strong></label>
                        <input type="text" class="form-control" id="inputUsername" placeholder="Votre Nom Utilisateur" name="username" value=<?php  echo $_SESSION['username'];?> >
                    </div>
                    <div class="form-group col-md-5 ml-5">
                        <label for="inputPassword4"><strong>Password</strong></label>
                        <input type="password" class="form-control" id="inputPassword4" placeholder="Password" name="pswd" value=<?php  echo $_SESSION['pswd'];?> >
                    </div>
                </div>
                        
                <div class="form-row col-md-12 mt-3" >
                    <div class="form-group col-md-8">
                        <label for=""><strong>Email</strong></label>
                        <input type="email" class="form-control" id="inputEmail" value=<?php  echo $_SESSION['email'];?> name="email">       
                    </div>
                </div>

                <div class="form-row col-md-12 mt-3" >
                    <div class="form-group col-md-10">
                        <label for=""><strong>Avatar</strong></label>
                        <input type="file" class="form-control" id="inputFile" name="avatar">       
                    </div>
                </div>

                <div class="form-row col-md-12 mt-3">
                   <div class="col-md-9" ></div>
                    <div class="col-md-3" >
                        <button type="submit" class="btn btn-lg btn-primary" name="valider">Valider</button>
                    </div>
                </div>

            </form>
                
        </div>
    </div>

	<div class=" col-md-12 mb-5 bg-light"></div>		
</main>


<footer class="bg-light " style="height:450px;">


	<div class="container bg-white col-md-11 d-flex pt-5">


		<div class="container col-md-4  " >
			<h5 class="text-center mb-4 ">RESEAUX SOCIAUX</h5>
			<div class=" d-flex flex-column  mb-5 pt-3 " style="margin-left:122px;">
				<div class="mb-4">
					<div id="carre1" class="mr-3"><a href="https://www.facebook.com/" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://www.facebook.com/" class=""><i class="fa fa-facebook nav-item nav-link reseaux-sociaux "><span class="text-dark ml-5">FACEBOOK</span></i></a></div>
				</div>
				<div class="mb-4">
					<div id="carre2" class="mr-3"><a href="https://www.instagram.com/?hl=fr" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://www.instagram.com/?hl=fr" class=""><i class="fa fa-instagram nav-item nav-link reseaux-sociaux "><span class="text-dark ml-5">INSTAGRAM</span></i></a></div>
				</div>
				<div class="mb-4">
					<div id="carre1" class="mr-3"><a href="https://twitter.com/login?lang=fr" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://twitter.com/login?lang=fr" class=""><i class="fa fa-twitter nav-item nav-link  reseaux-sociaux "><span class="text-dark ml-5">TWITTER</span></i></a></div>
				</div>
				<div class="mb-4">
					<div id="carre2" class="mr-3"><a href="https://www.pinterest.fr/" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://www.pinterest.fr/" class=""><i class="fa fa-pinterest-p nav-item nav-link reseaux-sociaux "><span class="text-dark ml-5">PINTEREST</span></i></a></div>
				</div>
			</div>
		</div>

		<div class="container col-md-4 text-center mt-3">
			<h5 class="mb-5">EN SAVOIR PLUS</h5>
			<div ><a href=""><label for="" class="text-dark mb-4">QUI SOMMES-NOUS ?</label></a></div>
			<div ><a href=""><label for="" class="text-dark mb-4">CONTACT</label></a></div>
			<div ><a href=""><label for="" class="text-dark">A PROPOS</label></a></div>
		</div>

		<div class="container col-md-4 text-center mt-3">
			<h5 class="mb-5">NEWS LETTER</h5>
			<p class="mb-4">Abonnez-vous pour recevoir nos derniers articles</p>
			<form action="" method="POST">
				<div class="mb-4  ">
					<input type="text" placeholder="Entrez un username" style="width:300px;" class="pl-3">
				</div>
				<div class="mt-4 ">
					<input type="email" placeholder="Entrez un email" style="width:300px;" class="pl-3">
				</div>
				<div class="container col-md-8 mt-4 ">
					<button class="btn btn-warning ">ENVOYER</button>
				</div>
			</form>
		</div>

	</div>

</footer>








</body>
</html>