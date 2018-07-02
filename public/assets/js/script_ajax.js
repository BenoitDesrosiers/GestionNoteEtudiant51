/**
 * ensemble de functions permettant de faire le call ajax permettant de faire le refresh de la liste 
 * des items sur les pages d'index. 
 * 
 * Le select servant à filter les items doit avoir l'id "belongsToListSelect".
 * Si il y a un bouton servant à la création d'un item, ce bouton doit avoir l'id "belongsToId". La valeur de ce bouton
 * sera mise à jour (updateCreateButton) lorsque le select changera. Cette valeur pourra servir dans l'écran de création afin 
 * de choisir une catégorie par défaut. 
 * 
 * La liste d'item est vide lors du load original de la page. Elle est remplie avec la valeur retourné par l'appel Ajax dans "afficheListeItems"
 * qui est appelé sur document.ready et lorsque que "belongsToListSelect" change. 
 */
function updateCreateButton() {
	document.getElementById('belongsToId').value=document.getElementById('belongsToListSelect').value;
}		
	
$(document).ready(function() {
	afficheListeItems();
	updateCreateButton();
	/*CKEDITOR.replaceALL('ckeditor');*/
});


$("#belongsToListSelect").change(function(e) {
	afficheListeItems();
	updateCreateButton();
	/*CKEDITOR.replaceALL('ckeditor');*/
	
});



/**
 * Change le contenu de la liste d'items afin de réfléter ce qui est sélectionner à l'aide du sélecteur "belongsToSelect" 
 * Le select doit avoir l'id "belongsToListSelect" et optionnellement un filtre ayant l'id "filtre1Select"
 * La valeur du select et du filtre seront envoyés à la route identifiée par "controllerCallBackRoute" qui doit 
 * être définie dans la view appelant cette fonction. 
 * 
 * Au retour du call Ajax, la div ayant l'id "liste-items" sera popullée avec le html retourné. 
 */
function afficheListeItems() {
	dataObject = {_token : $('meta[name="csrf-token"]').attr('content'),
			     belongsToId : document.getElementById('belongsToListSelect').value};
	if(!!document.getElementById('filtre1Select')) {
		$.extend(dataObject, {filtre1Select : document.getElementById('filtre1Select').value  })
	}
	

	
	$.ajax({
		type: 'POST',
		url: controllerCallBackRoute,
		data: dataObject, 
		timeout: 10000,  //laisser une grosse valeur, sinon xdebug cause un time out.
		success: function(data){
			document.getElementById('liste-items').innerHTML=data;
			 catchDataConfirm();
				setResizableDiv();
			},
		error: function(jqxhr, textStatus, error){
			document.getElementById('liste-items').innerHTML=textStatus;
		}
	});		
}	

