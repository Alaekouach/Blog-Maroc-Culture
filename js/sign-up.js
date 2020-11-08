const valider= document.getElementById("valider")
const username= document.getElementById("username")
const email= document.getElementById("email")
const pswd= document.getElementById("pswd")
const confirm_pswd= document.getElementById("confirm_pswd")


valider.addEventListener("click",function(event){

    event.preventDefault()

    const msgusername=document.getElementById("msgusername")
    if(username.value.length==0 || username.value.length<3) 
    {
        msgusername.innerHTML="Le nom d'utilisateur doit être valide" 
    } 
    else
    {
        msgusername.innerHTML=""    
    }     


    const msgemail=document.getElementById("msgemail")   
    if(email.value.length>=3 &&  email.value.length<=25) 
    {    
        msgemail.innerHTML=""         
    }
    else{
        msgemail.innerHTML="L'adresse mail n'est pas valide"

    }

    const msgpswd=document.getElementById("msgpswd")
    if(pswd.value.length==0 || pswd.value.length<3)
    {
        msgpswd.innerHTML="Le mot de passe doit au minimum 3 caractères"
    }
    else{
            
        msgpswd.innerHTML="" 
    }

    const msgconfirm_pswd=document.getElementById("msgconfirm_pswd")
    if(confirm_pswd.value.length==0 || confirm_pswd.value.length<3)
    {
        
        msgconfirm_pswd.innerHTML="" 
    }
    else
    {
        if(confirm_pswd.value == pswd.value && pswd.value == confirm_pswd.value)
        {
               
            msgconfirm_pswd.innerHTML="" 
        }
        else{
            msgpswd.innerHTML="Les mots de passe ne correspondant pas"
            msgconfirm_pswd.innerHTML="Les mots de passe ne correspondant pas"
          
        }
    }


})


const liker=document.getElementById("liker");
		liker.addEventListener('click',function(){
   				liker.classList.toggle('couleur');
	})