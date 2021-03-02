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
   $affiche_avatar=$requette1->fetch(); 


    // pour la modification de l'article
    $requette_modif=$bdd->prepare("SELECT * FROM articles WHERE id_article=?");
    $requette_modif->execute(array($_GET['id_article']));
    $affiche_article=$requette_modif->fetch();



    if(isset($_POST['modifier']))
    {
         $titre=$_POST['titre'];
         $description_article=$_POST['description_article'];
         $contenu=$_POST['editor1'];
         $date_creation=date('y-m-d h:i:s');
         $id_categorie=$_POST['id_categorie'];

         if(isset($_FILES['img_article']) and $_FILES['img_article']['error']==0)
        {
           if($_FILES['img_article']['size']< 1000000)
            {
                $list_extensions=array('png','jpg','jpeg','gif');
                $details=pathinfo($_FILES['img_article']['name']);
                $extension=$details['extension'];

                $resultat=in_array($extension,$list_extensions);       

                if($resultat)
                {   
                    $chemin='images/img-articles'.$details['basename'];
                    move_uploaded_file($_FILES['img_article']['tmp_name'],$chemin);
                }
            }
        }
        else{
            $chemin=$affiche_article['img_article'];
        }
        
        $requete_update= $bdd->prepare("UPDATE articles SET titre=?,description_article=?,id_categorie=?,contenu=?,date_creation=?,img_article=? where id_article=?");
        $requete_update->execute(array($titre,$description_article,$id_categorie,$contenu,$date_creation,$chemin,$_GET['id_article']));
        header('Location:articles_admin.php');
    }


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
    <title>Mes Articles</title>
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
        <div class="container text-uppercase">

            <h1 class="m-5">Modifier un article</h1>
            <form method="POST" class="ml-5 pl-5" enctype="multipart/form-data">
                <fieldset >
                    
                    <div class="form-group col-md-6">

                        <label for="nom">Entrez un titre à votre article :</label>
                        <input type="text" class="form-control" name="titre" value="<?php echo $affiche_article['titre'] ?>">
                    
                    </div>
                    
                    <div class="form-group col-md-6">

                        <label for="">Entrez Une description à votre article :</label>
                        <textarea class="form-control" name="description_article" rows="3"><?php echo $affiche_article['description_article'] ?></textarea>
                    </div>
                    
                    <div class="form-group col-md-3">

                        <label for="selection" >Séléctionnez une catégorie :</label>
                        <select name="id_categorie" class="form-control">
                                <option value="<?php echo $affiche_article['id_categorie'] ?>">Votre choix ...</option>
                                <option value="1">Histoire</option>
                                <option value="2">Voyage</option>
                                <option value="3">Gastronomie</option>
                                <option value="4">Artisanat</option>
                                <option value="5">Folklores</option>
                                <option value="6">Personnalités</option>     
                        </select>

                    </div>

                    <div class="form-group col-md-9">
                        <label for="">Le contenu de votre article :</label>
                        <textarea class="form-control" name="editor1" id="editor1" rows="3"><?php echo $affiche_article['contenu'] ?></textarea>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">Ajoutez une image pour votre article</label>
                        <input type="file" class="form-control" id="inputFile" name="img_article" >       
                    </div>

                    <div class="form-group col-md-7">
                                <button type="submit" class="btn btn-lg btn-warning float-right" name="modifier">Modifier</button>
                    </div>

                </fieldset>
            </form>      
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




<script src="//cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script>
                // Replace the <textarea id="editor1"> with a CKEditor 4
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
</script>
</body>
</html>