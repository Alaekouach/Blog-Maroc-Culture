<?php
session_start();
     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    }
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }
   

// la selection pour l'affichage de mon avatar
    $requette1=$bdd->prepare("SELECT * FROM utilisateurs WHERE username=?");
     $requette1->execute(array($_SESSION['username']));
     $affiche=$requette1->fetch();


     // selection de la table catégorie
     $requette_select_cat=$bdd->prepare("SELECT id_categorie,intitule_categorie FROM categorie");
     $requette_select_cat->execute(array());

	 $requette_select_cat2=$bdd->prepare("SELECT id_categorie,intitule_categorie FROM categorie");
     $requette_select_cat2->execute(array());
    //  $affiche=$requette_select_cat->fetch();

     // ajouter une catégorie
     if(isset($_POST['ajouter']))
     {
         $intitule_categorie=$_POST['intitule_categorie'];
        
          $requete_insert= $bdd->prepare("INSERT INTO categorie(intitule_categorie) values(?)");
          $requete_insert->execute(array($intitule_categorie));
          header('Location:categorie_admin.php');
     }

     // suppression d'une catégorie
     if(isset($_GET['id_categorie']))
     {
         $requete_delete= $bdd->prepare("DELETE from categorie where id_categorie= ?");
         $requete_delete->execute(array($_GET['id_categorie']));
         header('Location:categorie_admin.php');
     }


     // calculer nombres articles par catégorie : 

     $requete_calcul= $bdd->prepare("SELECT categorie.intitule_categorie,count(articles.id_categorie) as calcul  FROM articles,categorie WHERE articles.id_categorie=categorie.id_categorie  GROUP BY articles.id_categorie");
     $requete_calcul->execute(array());

	 $requete_calcul2= $bdd->prepare("SELECT categorie.intitule_categorie,count(articles.id_categorie) as calcul  FROM articles,categorie WHERE articles.id_categorie=categorie.id_categorie  GROUP BY articles.id_categorie");
     $requete_calcul2->execute(array());
   
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
    <title>Catégories</title>
    <link rel="stylesheet" type="text/css" href="css/accueil.css">
    <link rel="stylesheet" type="text/css" href="css/user.css">
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
	<!-- mobile-->
	<div class="mob">
	
		<nav class="navbar navbar-dark bg-light d-flex ">
			<button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">  
				<span class="navbar-toggler-icon bg-secondary"></span>
			</button>

			<a href="#" class="search_icon text-dark" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i></a>
			
			<a class="navbar-brand text-danger " href="index.php">Maro<b class="text-success">CϽ</b>ulture</a>
		</nav>

		<!-- modal pour search-->
		<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm modal-search" style="width:75%; margin:auto;margin-top:75px;">
				<div class="modal-content">
					<form class="" method="POST">
						<div class="input-group search-box" >
							<input type="text" class="form-control pl-2" placeholder="Chercher un article" name="text-search" >
							<button name="search"><i class="fas fa-search"></i></button>							
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- modal pour le menu toggle-->
		<div class="collapse bg-white" id="navbarToggleExternalContent">

			<div>
				<?php if(isset($_SESSION['username'])) {?>

					<div class="d-flex mt-3" style="width:80%; margin:auto; ">
						<img src="<?php echo $affiche['avatar'] ?>" alt="" class="rounded-circle " style="width=45px;height:40px;">
						<h6 class="text-center mb-3 " style="width:80%; margin:auto; ">Bonjour, <?php echo $affiche['nom']." ".$affiche['prenom'] ?></h6>

					</div>

					<hr style="width:80%; margin:auto;">

					<div class="text-center mt-3 mb-3">							
						<a class="btn-sm btn-success mt-4 mr-2" href="profile.php" >Mon profile</a>
						<a class="btn-sm btn-primary mt-4 ml-2" href="deconnexion.php">Se déconnecté</a>
					</div>

					<hr style="width:80%; margin:auto;">

				<?php }else{  ?>
					<div class="text-center mt-3 mb-3">
						<a href="ssign-in.php" class="btn-sm btn-danger mt-4 mr-2">Se connecter</a>
						<a href="ssign-up.php" class="btn-sm btn-light border border-dark mt-4 ml-2">S'inscrire</a>
					</div>

					<hr style="width:80%; margin:auto;">

				<?php } ?>
			</div>

			<!-- <div class="bg-light text-center p-4"> -->
			<div class="container d-flex flex-wrap justify-content-between mt-2 mb-2" style="width:300px" >
				
				<div class=" mt-2">
					<a href="histoire.php">
						<i class="fa fa-history couleur1" aria-hidden="true"></i>
						<small class="ml-2 text-dark">Histoire</small>
					</a>
				</div>
                <div class=" mt-2 ">
					<a href="voyage.php">
						<i class="fa fa-plane couleur2" aria-hidden="true"></i>
						<small class="ml-2 text-dark">Voyage</small>
					</a>
				</div>
				<div class=" mt-2 ">
					<a href="gastronomie.php">
						<i class="fa fa-cutlery couleur1" aria-hidden="true"></i>
						<small class="ml-2 text-dark">Gastronomie</small>
					</a>
				</div>
				<div class=" mt-2 ">
					<a href="artisanat.php">
						<i class="fa fa-scissors couleur2" aria-hidden="true"></i>
						<small class="ml-2 text-dark">Artisanat</small>
					</a>
				</div>
				<div class=" mt-2">
					<a href="folklores.php">
						<i class="fa fa-music couleur1" aria-hidden="true"></i>
						<small class="ml-2 text-dark">Folklores</small>
					</a>
				</div>
				<div class=" mt-2">
					<a href="personnalites.php">
						<i class="fa fa-male couleur2" aria-hidden="true"></i>
						<small class="ml-2 text-dark">Personnalités</small>
					</a>
				</div>

			</div>
		</div>

	</div>




	<!-- desktop-->
	<div class="desk"> 
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand text-danger" href="index.php">Maro<b class="text-success">CϽ</b>ulture</a>
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

					<?php if(isset($_SESSION['username'])) {?>
						<div class="collapse navbar-collapse dropdown justify-content-end" id="navbarTogglerDemo02" >
							<div>
								<a href="#" class="btn btn-transparent dropdown-toggle text-uppercase " id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php  echo $_SESSION['username'];?></a>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="profile.php" >MON PROFILE</a>
									<a class="dropdown-item" href="deconnexion.php">SE DÉCONNECTER</a>
								</div>
							</div>
							<img src="<?php echo $affiche['avatar'] ?>" alt="" class="rounded-circle " style="width=45px;height:40px;">
						</div>
					<?php }else{  ?>
						<div class="collapse navbar-collapse ml-5 " id="navbarTogglerDemo02">
							<a href="ssign-in.php" class="btn btn-transparent text-uppercase mr-2">Se connecter</a>
						</div>
					<?php }  ?>
				</div>
			</div>
		</nav>
	</div>
</header>

<main>

<!-- mobile-->
<div class="profile-mob">
	<div class="mt-5 text-uppercase">
			
		<div class="container col-md-8">
			<ul class="nav nav-tabs nav-justified">
                <li class="nav-item">
                    <a class="nav-link " href="profile-admin.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="articles_admin.php">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-info" href="categorie_admin.php">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="affiche_users.php">Utilisateurs</a>
                </li>
            </ul>               
		</div>             

	</div> 

	<div class="container col-md-11  mt-5 mb-4" > 		
		<h5 class="ml-3"><u><b>Les categories :</b></u></h5> 				
	</div> 

	<div class="container col-md-11" style="font-size:0.9rem"> 
			<table class="table table-bordered text-center">
				<thead class="thead-light">
					<tr>
						<th>Catégorie</th>
						<th>Actions</th>
					</tr>
				</thead>

			
				<tbody>
					<?php  while($affiche=$requette_select_cat->fetch()) {   ?>
                        <tr>
                                <td  class="text-capitalize"><?php  echo $affiche['intitule_categorie'] ?></td>
                                <td><button class="border bg-warning"><a href="modification_categorie_admin.php?id_categorie=<?php echo $affiche['id_categorie'] ?>" class='text-dark'><i class="fas fa-pencil-alt"></i></a></button>
                                <button class="border bg-danger "><a href="categorie_admin.php?id_categorie=<?php echo $affiche['id_categorie'] ?>" class=' text-white'><i class="fas fa-trash-alt"></i></a></button></td>
                        </tr>
                    <?php  } ?>     
				</tbody>       
			</table>
	</div>

	<div class="container col-md-11  mt-5 mb-3" > 		
		<h5 class="ml-3"><u><b>Ajout d'une catégorie :</b></u></h5> 				
	</div>

	<form action="" method="POST">
            <div class="input-group mb-4 col-md-8">
                <input type="text" name="intitule_categorie" class="form-control" placeholder="Ajouter une catégorie">
                <button name="ajouter" class="btn btn-sm border bg-secondary">Ajouter</button>
            </div>
    </form>


	<div class="container col-md-11  mt-5 mb-3 " > 		
		<h5 class="ml-3"><u><b>Nbre d'articles par catégorie :</b></u></h5> 				
	</div>

	<div class="container col-md-11 mb-5" style="font-size:0.9rem"> 
			<table class="table table-bordered text-center">
				<thead class="thead-light">
					<tr>
						<th>Catégorie</th>
                        <th>Nbre d'articles</th>
					</tr>
				</thead>

			
				<tbody>
					<?php  while( $calcul=$requete_calcul->fetch()) {   ?>
                        <tr>
                                <td class="text-capitalize"><?php  echo $calcul['intitule_categorie'] ?></td>
                                <td><?php echo $calcul['calcul'] ?></td>
                        </tr>
                    <?php  } ?>    
				</tbody>       
			</table>
	</div>

</div>




  <!-- desktop--> 
  <div class="profile-desk">
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
                        <a class="nav-link active bg-info" href="categorie_admin.php">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="affiche_users.php">Utilisateurs</a>
                    </li>
                </ul>                
           </div>
           <div class="col-md-1"></div>        
        </div>    
    </div> 

    <div class="container text-uppercase">

      <h3 class="m-5"><b><u> Les Catégories :</u></b></h3>
      <form action="" method="POST">
            <div class="input-group mb-3 ml-3 col-md-6 ">
                <input type="text" name="intitule_categorie" class="form-control" placeholder="Ajouter une catégorie">
                <button name="ajouter" class="btn  border bg-secondary">Ajouter</button>
            </div>
        </form>
      
        <div class="d-flex">
            <table class="table table-bordered text-center col-md-7 mr-3">
                <thead class="thead-light">
                    <tr>
                        <th>Catégorie</th>
                        <th>Modification</th>
                        <th>Suppression</th>
                    </tr>
                </thead>
            
                <tbody>
                    <?php  while($affiche2=$requette_select_cat2->fetch()) {   ?>
                        <tr>
                                <td><?php  echo $affiche2['intitule_categorie'] ?></td>
                                <td><button class="border bg-warning"><a href="modification_categorie_admin.php?id_categorie=<?php echo $affiche2['id_categorie'] ?>" class='text-dark'>Modifier</a></button></td>
                                <td ><button class="border bg-danger "><a href="categorie_admin.php?id_categorie=<?php echo $affiche2['id_categorie'] ?>" class=' text-white'>Supprimer</a></button></td>
                        </tr>
                    <?php  } ?>    
                </tbody>       
            </table>

            <table class="table table-bordered text-center col-md-5 ml-3">
                <thead class="thead-light">
                    <tr>
                        <th>Catégorie</th>
                        <th>Nbre d'articles</th>
                    </tr>
                </thead>
            
                <tbody>
                    <?php  while( $calcul=$requete_calcul2->fetch()) {   ?>
                        <tr>
                                <td><?php  echo $calcul['intitule_categorie'] ?></td>
                                <td><?php echo $calcul['calcul'] ?></td>
                        </tr>
                    <?php  } ?>    
                </tbody>       
            </table>
        </div>
    </div>

	<div class=" col-md-12 mb-5 bg-light"></div>		
	</div>
</main>


<footer class="bg-light">

	<!-- desktop-->
	<div class="container bg-white col-md-11 pt-5 foo-desk">

		<div class="container col-md-4 " >
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

	<!-- mobile-->
	<div class="container bg-white col-md-11 foo-mob">

		<div class="container col-md-4 text-center mt-5">
			<h5 class="mb-3 pt-1 pb-1">EN SAVOIR PLUS</h5>
			<div ><a href=""><label for="" class="text-dark mb-3">QUI SOMMES-NOUS ?</label></a></div>
			<div ><a href="contact.php"><label for="" class="text-dark mb-3">CONTACT</label></a></div>
			<div ><a href=""><label for="" class="text-dark  mb-3">A PROPOS</label></a></div>
		</div>

		<div class="container col-md-4 text-center mt-3 bg-light">
			<h5 class="mb-2 pt-1 pb-1 ">NEWS LETTER</h5>
			<p class="mb-4">Abonnez-vous pour recevoir nos derniers articles</p>
			<form action="" method="POST">
				<div class="mb-4  ">
					<input type="text" placeholder="Entrez un username" style="width:240px;" class="pl-3">
				</div>
				<div class="mt-4 ">
					<input type="email" placeholder="Entrez un email" style="width:240px;" class="pl-3">
				</div>
				<div class="container col-md-8 mt-4 ">
					<button class="btn btn-sm btn-warning ">ENVOYER</button>
				</div>
			</form>
		</div>

		<div class="container col-md-4 text-center mt-4 ">
			<h5 class="mb-3 pt-1 pb-1">CHAINE YOUTUBE</h5>
			<iframe width="280" height="170" src="https://www.youtube.com/embed/azgptq1JsFg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>

		<div class="container col-md-4 mt-3 bg-light" >
			<h5 class="text-center mb-2 pt-1 pb-1">RESEAUX SOCIAUX</h5>
			<div class=" d-flex mb-5 pt-3 " style="margin-left:65px;">
				<div class="mb-4">
					<div id="carre1" class="mr-3"><a href="https://www.facebook.com/" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://www.facebook.com/" class=""><i class="fa fa-facebook nav-item nav-link reseaux-sociaux "></i></a></div>
				</div>
				<div class="mb-4">
					<div id="carre2" class="mr-3"><a href="https://www.instagram.com/?hl=fr" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://www.instagram.com/?hl=fr" class=""><i class="fa fa-instagram nav-item nav-link reseaux-sociaux "></i></a></div>
				</div>
				<div class="mb-4">
					<div id="carre1" class="mr-3"><a href="https://twitter.com/login?lang=fr" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://twitter.com/login?lang=fr" class=""><i class="fa fa-twitter nav-item nav-link  reseaux-sociaux "></i></a></div>
				</div>
				<div class="mb-4">
					<div id="carre2" class="mr-3"><a href="https://www.pinterest.fr/" class="nav-item nav-link "></a></div>
					<div class="ml-4"><a href="https://www.pinterest.fr/" class=""><i class="fa fa-pinterest-p nav-item nav-link reseaux-sociaux "></i></a></div>
				</div>
			</div>
		</div>

	</div>

</footer>





<script src="//cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script>
                // Replace the <textarea id="editor1"> with a CKEditor 4
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
</script>
</body>
</html>