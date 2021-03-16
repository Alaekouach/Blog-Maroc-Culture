<?php

     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    }
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }
     SESSION_START();

     // la selection pour l'affichage de mon avatar
	 if(isset($_SESSION['username'])){
		$requette1=$bdd->prepare('SELECT * FROM utilisateurs WHERE username=?');
		$requette1->execute(array($_SESSION['username']));
		$affiche=$requette1->fetch();
		}

     // pour l'affichage d'un article
    $requette_affiche=$bdd->prepare("SELECT * FROM articles,utilisateurs WHERE id_article=? and articles.id_user=utilisateurs.id_user");
    $requette_affiche->execute(array($_GET['id_article']));
    $affiche_article=$requette_affiche->fetch();

    // pour insérer les commentaires

    if(isset($_POST['envoyer']))
    {
		if(isset($_SESSION['username']))
		{
			$commentaire=htmlspecialchars($_POST['commentaire']);
			$commentaire=strip_tags($_POST['commentaire']);
			$date_commentaire=date('y-m-d h:i:s');

			if(isset($commentaire))
			{
				$requete_insert= $bdd->prepare("INSERT INTO commentaire(contenu_commentaire,date_commentaire,id_article,id_user) values (?,?,?,?) ");
				$requete_insert->execute(array($commentaire,$date_commentaire,$_GET['id_article'],$_SESSION['id_user']));
			}
		}
    }

   // selection de la table commentaire pour les afficher

   $requete_select= $bdd->prepare("SELECT * FROM commentaire,utilisateurs,articles WHERE articles.id_article=? and articles.id_article=commentaire.id_article AND commentaire.id_user=utilisateurs.id_user ");
   $requete_select->execute(array($_GET['id_article']));

   $requete_select2= $bdd->prepare("SELECT * FROM commentaire,utilisateurs,articles WHERE articles.id_article=? and articles.id_article=commentaire.id_article AND commentaire.id_user=utilisateurs.id_user ");
   $requete_select2->execute(array($_GET['id_article']));


// calculer nombres commentaires :

$requete_calcul= $bdd->prepare("SELECT count(*) as calcul FROM articles,commentaire WHERE articles.id_article=? and articles.id_article=commentaire.id_article ");
$requete_calcul->execute(array($_GET['id_article']));
$calcul=$requete_calcul->fetch();

 //selection des 3 articles les plus récentes
 $requette3=$bdd->prepare('SELECT utilisateurs.username,articles.titre,articles.id_article,articles.img_article FROM articles,utilisateurs WHERE utilisateurs.id_user=articles.id_user ORDER BY articles.date_creation DESC LIMIT 4');
 $requette3->execute(array());


 // faire une recherche sur un mot dans la table article :
 if(isset($_POST['search']))
 {
	
	$_SESSION['text-search']=$_POST['text-search'];
	header('Location:search.php');
 }

 // insérer un like pour un article d'un user
 if(isset($_POST['liker']))
 {
	$requete_insert= $bdd->prepare("INSERT INTO likes(id_article,id_user) values (?,?)");
	$requete_insert->execute(array($_GET['id_article'],$_SESSION['id_user']));
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>affiche article</title>


<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.0/css/all.css" integrity="sha384-OLYO0LymqQ+uHXELyx93kblK5YIS3B2ZfLGBmsJaUyor7CpMTBsahDHByqSuWW+q" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Raleway">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/accueil.css">
    <link rel="stylesheet" type="text/css" href="css/users.css">
	<link rel="stylesheet" type="text/css" href="css/article.css?v=<?php echo time(); ?>">


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

    <div class="container col-md-11 d-flex">
        <div class="container aff col-md-7 bg-white">

            <h1 class="pt-3 pl-3 titr-desk"><?php echo $affiche_article['titre'] ?></h1>
			<h4 class="pt-3 text-center titr-mob"><?php echo $affiche_article['titre'] ?></h4>

            <div class="pl-3 mb-3 text-uppercase">
                <small>
                    <?php echo $affiche_article['date_creation'] ?> <span>, <?php echo $affiche_article['username'] ?></span>
                </small>
            </div>
            <div class="text-center">
                <img src="<?php echo $affiche_article['img_article'] ?>" alt="" class="img-art ">
            </div>
            <div class="mt-4 pl-3">
                <p><strong><?php echo $affiche_article['description_article'] ?></strong></p>
            </div>
            <div class="mt-5">
                <p><?php echo $affiche_article['contenu'] ?></p>
			</div>
        </div>

        <div class="container col-md-3 text-center mt-3 bg-white aaside">

			<img src="images\img-avatarIMG_0966-copy-copy-ConvertImage.jpg" alt="" class="rounded-circle "  width=75%>
			<h5 class="text-uppercase mt-2">Alae eddine</h5>
			<small>Blogueur , Full-stack Developer , Network Engineer</small>
			<small>alae.kouach@gmail.com</small><br>
			<a href="contact.php"><button class="btn mt-2 text-white" style="background-color: rgb(208,179, 94)">Contact</button></a>


			<div class="mt-5 mb-4 pt-3 border-top border-bottom">
			<p class="text-uppercase">Catégories</p>
			</div>

			<div class="container d-flex flex-wrap justify-content-between col-md-10 mt-4 " >
                <div class="mr-4 mt-2 ">
					<a href="histoire.php"><div><i class="fa fa-history couleur1" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Histoire</small></div></a>
				</div>
                <div class="mr-4 mt-2 ">
					<a href="voyage.php"><div><i class="fa fa-plane couleur2" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Voyage</small></div></a>
				</div>
				<div class="mr-4 mt-2 ">
					<a href="gastronomie.php"><div><i class="fa fa-cutlery couleur1" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Gastronomie</small></div></a>
				</div>
				<div class="mr-4 mt-4 ">
					<a href="artisanat.php"><div><i class="fa fa-scissors couleur2" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Artisanat</small></div></a>
				</div>
				<div class="mr-3 mt-4 ">
					<a href="folklores.php"><div><i class="fa fa-music couleur1" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Folklores</small></div></a>
				</div>
				<div class="mr-3 mt-4">
					<a href="personnalites.php"><div><i class="fa fa-male couleur2" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Personnalités</small></div></a>
				</div>
			</div>


			<div class="mt-5 mb-4 pt-3 border-top border-bottom">
			<p class="text-uppercase">Réseaux Sociaux </p>
			</div>

			<div class="container d-flex justify-content-center mt-5">
				<div>
					<div id="carre1" class="mr-3"><a href="#" class="nav-item nav-link "></a></div>
					<div><a href="https://www.facebook.com/" class=""><i class="fa fa-facebook nav-item nav-link reseaux-sociaux"></i></a></div>
				</div>
				<div>
					<div id="carre2" class="mr-3"><a href="#" class="nav-item nav-link "></a></div>
					<div><a href="https://www.instagram.com/?hl=fr" class=""><i class="fa fa-instagram nav-item nav-link reseaux-sociaux "></i></a></div>
				</div>
				<div>
					<div id="carre1" class="mr-3"><a href="#" class="nav-item nav-link "></a></div>
					<div><a href="https://twitter.com/login?lang=fr" class=""><i class="fa fa-twitter nav-item nav-link  reseaux-sociaux"></i></a></div>
				</div>
				<div>
					<div id="carre2" class="mr-3"><a href="#" class="nav-item nav-link "></a></div>
					<div><a href="https://www.pinterest.fr/" class=""><i class="fa fa-pinterest-p nav-item nav-link reseaux-sociaux "></i></a></div>
				</div>
			</div>


			<div class="mt-5 mb-4 pt-3 border-top border-bottom">
				<p class="text-uppercase">Ma chaîne Youtube</p>
			</div>

			<div>
				<iframe width="320" height="240" src="https://www.youtube.com/embed/azgptq1JsFg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>


			<div class="mt-3 mb-4 pt-3 border-top border-bottom">
				<p class="text-uppercase">Les articles récents</p>
			</div>

			<div class="col-md-12 ">
				<?php  while($affiche_article_recent=$requette3->fetch() ) {   ?>
					<div>
						<a href="affiche_article.php?id_article=<?php echo $affiche_article_recent['id_article'] ?>"><img src="<?php echo $affiche_article_recent['img_article'] ?>" alt="" class="mb-2" style="width:100%"></a>
					</div>
					<div>
						<a href="affiche_article.php?id_article=<?php echo $affiche_article_recent['id_article'] ?>"><small ><strong ><?php echo $affiche_article_recent['titre'] ?></strong></small></a><br>
						<small class="text-info text-uppercase float-right mb-3">écrit par : <?php echo $affiche_article_recent['username'] ?></small>
					</div>
				<?php   }  ?>
			</div>

		</div>       

    </div>

	<!-- desktop-->
    <div class="container aff aff-desk col-md-8 bg-white">
        <h3 class="pt-3 ml-3"><?php echo $calcul['calcul'] ?> Commentaires :</h3>

        <div class=" com justify-content-around ">

            <div class=" col-md-6 mb-3 mt-4 border">
                <?php  while($affiche_commentaire=$requete_select->fetch() ) {   ?>
                    <div class="d-flex justify-content-start " >
                        <img src="<?php echo $affiche_commentaire['avatar'] ?>" alt="" class="rounded-circle mr-3 "  width=15%>
                        <div class="">
                            <small class="text-primary text-uppercase"><?php echo $affiche_commentaire['username'] ?></small><br>
                            <small class="text-muted"><?php echo $affiche_commentaire['date_commentaire'] ?></small>
                        </div>
                    </div>
                    <div class="container pt-1 col-md-12">
                        <p class="pl-5"><?php echo $affiche_commentaire['contenu_commentaire'] ?></p>
                    </div>
                <?php   }  ?>
            </div>



            <div class="col-md-6 mb-3 mt-4 ">
                <form action="#" method="POST">
                    <h5 class="mb-3 ">Laisser un commentaire :</h5>
                    <textarea name="commentaire" id="" cols="60" rows="8"></textarea>
                    <button type="submit" class="btn btn-lg btn-warning float-right mt-3 mr-5" name="envoyer">Envoyer</button>
                </form>
            </div>
        </div>
    </div>

	

	<!-- mobile-->
	<div class="container aff-mob bg-light mt-3">
        <h6 class="pt-3 ml-3"><?php echo $calcul['calcul'] ?><b> Commentaires :</b></h6>
       
            <div class=" mb-3 mt-4 border" >
                <?php  while($affiche_commentaire=$requete_select2->fetch() ) {   ?>
                    <div class="d-flex justify-content-start " >
                        <img src="<?php echo $affiche_commentaire['avatar'] ?>" alt="" class="rounded-circle mr-3 "  width=15%>
                        <div class="">
                            <small class="text-primary text-uppercase"><?php echo $affiche_commentaire['username'] ?></small><br>
                            <small class="text-muted"><?php echo $affiche_commentaire['date_commentaire'] ?></small>
                        </div>
                    </div>
                    <div class="container pt-1">
                        <p class=""><?php echo $affiche_commentaire['contenu_commentaire'] ?></p>
                    </div>
                <?php   }  ?>
            </div>

			<div class="mb-3 mt-4 pb-2">
                <form action="#" method="POST">
                    <h6 class="mb-3 ">Laisser un commentaire :</h6>
                    <textarea name="commentaire" id="" cols="35" rows="4"></textarea>
                    <button type="submit" class="btn btn-sm btn-warning float-right mt-3 mr-2" name="envoyer">Envoyer</button>
                </form>
            </div>

    </div>

	<div class=" col-md-12 mb-5 bg-light"></div>

	
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

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="liker.js"></script>

<script>
function myFunction(x) {
  x.classList.toggle("couleur");
}
</script>


</body>
</html>