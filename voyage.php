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
	 
	 // la selection pour l'affichage des articles de la catégorie séléctionné
	 $requette2=$bdd->prepare('SELECT * FROM articles,utilisateurs,categorie WHERE articles.id_user=utilisateurs.id_user AND articles.id_categorie=categorie.id_categorie AND categorie.id_categorie=2');
     $requette2->execute(array());
	 
	 //selection des catégories
	 $requete_categorie= $bdd->prepare("SELECT intitule_categorie FROM articles,categorie WHERE articles.id_categorie=categorie.id_categorie ");
     $requete_categorie->execute(array());
	 $categ=$requete_categorie->fetch();
	 
	 //selection des 3 articles les plus récentes 
	 $requette3=$bdd->prepare('SELECT utilisateurs.username,articles.titre,articles.id_article,articles.img_article FROM articles,utilisateurs WHERE utilisateurs.id_user=articles.id_user ORDER BY articles.date_creation DESC LIMIT 4');
	 $requette3->execute(array());
	 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégorie Voyage</title>
	<link rel="stylesheet" type="text/css" href="css/accueil.css">
	
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.0/css/all.css" integrity="sha384-OLYO0LymqQ+uHXELyx93kblK5YIS3B2ZfLGBmsJaUyor7CpMTBsahDHByqSuWW+q" crossorigin="anonymous">

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Raleway">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</head>

<style>

.bouton5{
	position:absolute;
	width:90px;
	cursor:pointer;
	text-transform:capitalize;
	border-radius:12px 0 12px 0;
	background: #dd9e3f;
	border:none;
	color:#fff;
	font:bold 12px Verdana;
	padding:6px 0 6px 0;
	margin :10px;
}



</style>
<body>

<header>

    <nav class="navbar navbar-expand-lg  navbar-light bg-light">
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
	</nav>
	
</header>

<main>

<div class="container col-md-11 mt-4 mb-4">
		<div id="carouselExampleIndicators" class="carousel slide " data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
		</ol>
		<div class="carousel-inner" style="height:450px;">
			<div class="carousel-item active">
			    <img class="d-block w-100" src="images\img-slide\gastr_0.jpg" alt="First slide">
			</div>
			<div class="carousel-item">
			    <img class="d-block w-100" src="images\img-slide\atay-1068x445.jpg" alt="Second slide">
			</div>
			<div class="carousel-item">
			    <img class="d-block w-100" src="images\img-slide\1df0b865555d402ac6df4c362bb90ec7.jpg" alt="Third slide">
			</div>
		</div>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
		</div>
	</div>

    <div class="col-md-11 mt-5 ">
				<!-- <h3 class="container col-md-7 mt-3 pt-1 pb-1 display-5 text-info rounded text-center text-uppercase">Les destinations incontournables du Maroc</h3> -->
    </div>
    
	<div class="container col-md-11 d-flex">

        <div>   
			<h4 class="col-md-5 ml-5"><small> Catégorie > Voyage </small></h4>
			<div class=" container d-flex flex-wrap col-md-11 m-5">
				<?php  while($affiche_article=$requette2->fetch() ) {   ?>
					<div class="container col-md-5 mb-4 pt-2 bg-white">
						<button class="bouton5"><?php echo $affiche_article['intitule_categorie'] ?></button>
						<div class="">
							<a href="affiche_article.php?id_article=<?php echo $affiche_article['id_article'] ?>"><img src="<?php echo $affiche_article['img_article'] ?>" alt="" class="mb-2" style="width:100%"></a>
						</div>
						<a href="affiche_article.php?id_article=<?php echo $affiche_article['id_article'] ?>"><small ><strong ><?php echo $affiche_article['titre'] ?></strong></small></a>
						<p class="mt-3"><?php echo $affiche_article['description_article'] ?>...</p>
						<div class="">
							<small class="text-info text-uppercase"><?php echo $affiche_article['username'] ?></small>
							<small><?php echo $affiche_article['date_creation'] ?></small>
							<a href="affiche_article.php?id_article=<?php echo $affiche_article['id_article'] ?>" class="float-right">Lire la suite...</a>
						</div>
						
					</div>
				<?php   }  ?>
			</div>	
		</div>

		<div class="container col-md-3 text-center mt-3 bg-white">

			<img src="images\img-avatarIMG_0966-copy-copy-ConvertImage.jpg" alt="" class="rounded-circle "  width=75%>
			<h5 class="text-uppercase mt-2">Alae eddine</h5>
			<small>Blogueur , Full-stack Developer , Network Engineer</small>
			<small>alae.kouach@gmail.com</small><br>
			<a href="contact.php"><button class="btn mt-2 text-white" style="background-color: rgb(208,179, 94)">Contact</button></a>


			<div class="mt-5 mb-4 pt-3 border-top border-bottom">
			<p class="text-uppercase">Catégories</p>
			</div>

			<div class="container d-flex flex-wrap justify-content-between col-md-10 mt-4 " >
				<div class="mr-4 mt-2">
					<a href="histoire.php"><div><i class="fa fa-history couleur1" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Histoire</small></div></a>
				</div>
				<div class="mr-4 mt-2">
					<a href="voyage.php"><div><i class="fa fa-plane couleur2" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Voyage</small></div></a>
				</div>
				<div class="mr-4 mt-2">
					<a href="gastronomie.php"><div><i class="fa fa-cutlery couleur1" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Gastronomie</small></div></a>
				</div>
				<div class="mr-4 mt-4">
					<a href="artisanat.php"><div><i class="fa fa-scissors couleur2" aria-hidden="true"></i></div>
					<div class="text-dark"><small>Artisanat</small></div></a>
				</div>
				<div class="mr-3 mt-4">
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