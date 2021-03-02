<?php

     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    } 
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }
     SESSION_START();


     $requette1=$bdd->prepare('SELECT * FROM utilisateurs WHERE username=?');
     $requette1->execute(array($_SESSION['username']));
     $affiche=$requette1->fetch();

     $requete_select_users= $bdd->prepare('SELECT id_user,avatar,username,nom,prenom,email,intitule_role FROM utilisateurs,roles WHERE utilisateurs.id_role=roles.id_role');
     $requete_select_users->execute(array());

     if(isset($_GET['id_user']))
     {
         $requete_delete= $bdd->prepare("DELETE from utilisateurs where id_user= ?");
         $requete_delete->execute(array($_GET['id_user']));
         header('Location:affiche_users.php');
     }

     // calculer nombres articles par utilisateurs : 
        $requete_calcul= $bdd->prepare("SELECT utilisateurs.id_user,utilisateurs.username,count(articles.id_article) as calcul FROM articles,utilisateurs WHERE articles.id_user=utilisateurs.id_user GROUP BY articles.id_user ");
        $requete_calcul->execute(array());

    // sélectionner tous les articles d'un utilisateur
        // $requette2=$bdd->prepare('SELECT * FROM articles,utilisateurs WHERE articles.id_user=utilisateurs.id_user and utilisateurs.id_user=?');
        // $requete2->execute(array($_GET['id_user']));
        // $affi_arti=$requete2->fetch();

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
				<img src="<?php echo $affiche['avatar'] ?>" alt="" class="rounded-circle "  style="width=45px;height:40px;">
			</div>

		</div>

	</nav>
	
</header>

<main>

   
    <div class="container-fluid p-0 mt-5 text-uppercase">
            <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link " href="profile-admin.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="articles_admin.php">Articles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categorie_admin.php">Catégories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active bg-info" href="affiche_users.php">Utilisateurs</a>
                        </li>
                    </ul>                
            </div>
            <div class="col-md-1"></div>        
        </div>    
    </div> 

    <div class="container text-uppercase">
      <h3 class="m-5">Tous les utilisateurs</h3>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Role</th>
                    <th>Suppression</th>
                </tr>
            </thead>

            <tbody>
                <?php  while($affiche_users=$requete_select_users->fetch() ) {   ?>
                    <tr>
                            <td><img src="<?php echo $affiche_users['avatar'] ?>" alt="" class="rounded "  width=20%></td>
                            <td><?php  echo $affiche_users['username'] ?></td>
                            <td><?php  echo $affiche_users['email'] ?></td>
                            <td><?php  echo $affiche_users['nom'] ?></td>
                            <td><?php  echo $affiche_users['prenom'] ?></td>
                            <td><?php  echo $affiche_users['intitule_role'] ?></td>
                            <td ><button class="border bg-danger "><a href="affiche_users.php?id_user=<?php echo $affiche_users['id_user'] ?>" class=' text-white'>Supprimer</a></button></td> 
                    </tr>
                <?php  } ?>    
            </tbody>       
        </table>

        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>Username</th>
                    <th>Nombre d'articles</th>
                    <th>Articles</th>
                </tr>
            </thead>

            <tbody>
                <?php  while($calcul=$requete_calcul->fetch()) {   ?>
                    <tr>
                        <td><?php  echo $calcul['username'] ?></td>
                        <td><?php echo $calcul['calcul'] ?></td>
                        <td ><button class="border bg-success "><a href="affiche_article_user.php?id_user=<?php echo $calcul['id_user'] ?>" class=' text-white'>Voir ses articles</a></button></td> 
                    </tr>
                <?php  } ?>    
            </tbody>       
        </table>


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