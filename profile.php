<?php
   SESSION_START();

     try {
        $bdd=new PDO('mysql:host=localhost;dbname=blog;charset=utf8','root','');
    } 
    catch (Exception $err) {
        echo " la connexion avec la base de données est échouée." ;
    }
  

     $requete_select= $bdd->prepare("SELECT id_role from utilisateurs where username=? ");
     $requete_select->execute(array($_SESSION['username']));
        $affiche=$requete_select->fetch();


     if($affiche['id_role']==1)
     {
         header('Location:profile-admin.php');
     }
     else{
        header('Location:profile-user.php');
     }
     

?>
