

/**
 * functions affichant un pop-up demandant une confirmation
 * utilisees pour confirmer lors de l'appuie sur un bouton "effacer"
 */
$(function() {
    catchDataConfirm();
	setResizableDiv();

});

$(document).ajaxComplete(function(){
	//refait l'association du bouton data-confirm après un reload ajax.
	catchDataConfirm();
	setResizableDiv();
});

function catchDataConfirm() {
	
	 // Confirm deleting resources
    // méthode pour faire le delete tel que décrit sur http://www.codeforest.net/laravel-4-tutorial-part-2 
    $("form[data-confirm]").submit(function() {
            if ( ! confirm($(this).attr("data-confirm"))) {
                    return false;
            }
    });
}


	
//rend les div de la classe .resizeDiv resizable, c.à.d. qu'une pogné est affichée en bas à droite, permettant de 
//changer la taille de la div à l'écran	
function setResizableDiv() {	
	$('.resizeDiv')
	.resizable()
}


/**
 * change un input select dans le formulaire
 * 
 * @param selectId l'id html du select sur le formulaire
 * @param choix un array d'id d'option a afficher. Les autres seront display:none
 * @param reset un boolean indiquand si on doit reseter les options qui sont "selected".
 * 				Si vrai, la première option sera selected
 * 				Si faux, les options qui étaient selected vont le rester. 
 */
function changeSelect(selectId, choix, reset) {
	var leSelect = document.getElementById(selectId);
	var leSelected = -1;
	for(var j = 0; j < leSelect.options.length; ++j) {
		leSelect.options[j].style = "display:none";
		for(var i = 0; i< choix.length; i++) {
			if(leSelect.options[j].value == choix[i] || ( leSelect.options[j].selected == true && !reset)) {
				leSelect.options[j].style = "";
				if(leSelected == -1)
					{leSelected = leSelect.options[j].value;}
			}
		}
	}
	if(reset) {document.getElementById(selectId).value = leSelected;}
};