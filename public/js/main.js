/******* Slide de la homepage *******/

let i = 0;
let images = ['../img/entree.jpg', '../img/salon.jpg', '../img/jardin.jpg', '../img/lits.jpg'];
let time = 8000;
let carousel = document.querySelector('#slides');

function changeImg(){
    document.slide.src = images[i];
    if(i < images.length - 1){
        i++;
    } else {
        i = 0;
    }
    setTimeout("changeImg()", time);
}
if(carousel){
    window.onload = changeImg();
}




AOS.init({
    duration: "1500",
    easing : 'ease-in-out'   
});

/******* editeur de text *********/
if(document.querySelector('#article_content')){
    CKEDITOR.replace('article[content]');
} else if(document.querySelector('#comment_content')){
    CKEDITOR.replace('comment[content]');
} 

// générateur de mot de passe
    function generate(l) {
        if (typeof l==='undefined'){var l=10;}
        /* c : chaîne de caractères alphanumérique */
        var c='abcdefghijknopqrstuvwxyzACDEFGHJKLMNPQRSTUVWXYZ12345679',
        n=c.length,
        /* p : chaîne de caractères spéciaux */
        p='!@#$+-*&_/*',
        o=p.length,
        r='',
        n=c.length,
        /* s : determine la position du caractère spécial dans le mdp */
        s=Math.floor(Math.random() * (p.length-1));

        for(var i=0; i<l; ++i){
            if(s == i){
                /* on insère à la position donnée un caractère spécial aléatoire */
                r += p.charAt(Math.floor(Math.random() * o));
            }else{
                /* on insère un caractère alphanumérique aléatoire */
                r += c.charAt(Math.floor(Math.random() * n));
            }
        }
        return r;
    }    

    /* exemple de fonction generation de mdp dans un form (utilise JQuery) */
    $(document).ready(function() {
        /* on détècte un des champ du formulaire contient une class "gen", on insèrera un bouton dans sa div parent qui appelera la fonction generate() */
        if($('input.gen').length){
            $('input.gen').each(function(){
                $('<span class="generate" style="cursor:pointer; background-color:"bleu"><i class="fas fa-sync-alt"></i></span>').appendTo($(this).parent());
            });
        }
        
        /* évènement click sur un element de class "generate" > appelle la fonction generate() */
        $(document).on('click','.generate', function(e){
            e.preventDefault();
            /* ajout du mot de passe + changement du paramètre type de password vers text (pour lisibilité) */
            $(this).parent().children('input').val(generate()).attr('type','text');
        });
    });


// champ input file affiche le texte

     $("input[type=file]").change(function (e){$(this).next('.custom-file-label').text(e.target.files[0].name);})

   /* $("input[type=file]").change(function () {
        var fieldVal = $(this).val();
        if (fieldVal != undefined || fieldVal != "") {
          $(this).next(".custom-file-label").text(fieldVal);
        }
      });*/


      $("input[type=file]").change(function (e) {
        var files = [];
        for (var i = 0; i < $(this)[0].files.length; i++) {
            files.push($(this)[0].files[i].name);
        }
        if(files.length == 1){
            $(this).next('.custom-file-label').html(files);
        } else {
            $(this).next('.custom-file-label').html(files + ' , ');
        }    
    });

//supprimer les images uploads

    window.onload = () => {
        // Gestion des boutons "Supprimer"
        let links = document.querySelectorAll("[data-delete]")
        
        // On boucle sur links
        for(link of links){
            // On écoute le clic
            link.addEventListener("click", function(e){
                // On empêche la navigation
                e.preventDefault()

                // On demande confirmation
                if(confirm("Voulez-vous supprimer cette image ?")){
                    // On envoie une requête Ajax vers le href du lien avec la méthode DELETE
                    fetch(this.getAttribute("href"), {
                        method: "DELETE",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({"_token": this.dataset.token})
                    }).then(
                        // On récupère la réponse en JSON
                        response => response.json()
                    ).then(data => {
                        if(data.success)
                            this.parentElement.remove()
                        else
                            alert(data.error)
                    }).catch(e => alert(e))
                }
            })
        }
    }



/***** gallerie d'image ******/

$(document).on('click', '[data-toggle="lightbox"]', function(event){
   event.preventDefault();
   $(this).ekkoLightbox(); 
})