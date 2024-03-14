(function($){
	$('#hidden_wp_excerpt').hide();

	// Affiche le bloc de l'extrait choisi
	$('#wp_excerpt_generator_deleteSelectedExcerpts option').on('dblclick', function() {
		// Affiche le bloc masqué et ajoute l'ID de l'extrait dans une class
		$('#hidden_wp_excerpt').show();
		$('#wp_excerpt_generator_editExcerpt').attr('class', $(this).val());
		$('#wp_excerpt_generator_editExcerpt_id').val($(this).val());

		// Appel Ajax pour récupérer l'extrait choisi
		$.ajax({
			url: ajaxurl,
			type: "GET",
			data: {
				action: 'wp_excerpt_generator_edit', // Nécessaire pour WordPress
				idExcerpt: $(this).val()
			},
			// Si Ajax répond bien !
			success: function(result) {
				// Effet sur le bloc d'image de chargement
				var text = $.trim(result);
				$('#wp_excerpt_generator_editExcerpt').text(text);
			},
			// En cas d'erreur Ajax
			error: function(req, err) {
				console.log('Error: '+err);
			}
		});
	});

	// Cacher le bloc si le bouton "Cancel" est cliqué
	$('#wp_excerpt_generator_cancelSelectedExcerpt').on('click', function() {
		$('#hidden_wp_excerpt').hide();
	});
})(jQuery);