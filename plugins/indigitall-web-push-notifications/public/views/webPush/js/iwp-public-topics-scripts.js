let iwp_success = function() {
  	const showTopics = parseInt(localStorage.getItem("indigitall.repository.SHOW_TOPICS"));
	if (showTopics !== 1) {
		// Si aún no se han mostrado los topics al usuario final, se abre la modal
		openTopicsModal();
	}
}

let selectedTopic = [];
let iwp_loadTopics = function() {
	if (!indigitall.hasOwnProperty('topicsList')) {
		return false;
	}
	// Cargamos la lista de topics
  	indigitall.topicsList((topics) => {
		if (topics != null) {
			topics.forEach((topic) => {
				// Por cada elemento generamos un código HTML
				let checked = "";
				if (topic.hasOwnProperty('subscribed') && topic.subscribed) {
					checked = " selected ";
					selectedTopic.push(topic.code);
				}
				let itemCheck = "<div class='iwp-public-topics-modal-list-item-check'></div>";
				let itemLabel = "<div class='iwp-public-topics-modal-list-item-label'>" + topic.name + "</div>";
				let linea = "<li class='iwp-public-topics-modal-list-item " + checked + "' data-id='" + topic.code + "'>" + itemCheck + itemLabel + "</li>";
				document.getElementById('topicsUl').insertAdjacentHTML('beforeend', linea);
			});

			let topicElements = document.querySelectorAll('.iwp-public-topics-modal-list-item');
			topicElements.forEach(topic => {
				// A cada topic le asignamos un evento 'clic'
				topic.addEventListener('click', function (event) {
					let currentTopic = event.target;
					if (!currentTopic.classList.contains('.iwp-public-topics-modal-list-item')) {
						currentTopic = currentTopic.closest('.iwp-public-topics-modal-list-item');
					}

					let eventCode = currentTopic.getAttribute('data-id');
					let index = selectedTopic.indexOf(eventCode);

					if (!currentTopic.classList.contains('selected')) {
						if (index < 0) {
							selectedTopic.push(eventCode);
							currentTopic.classList.add('selected');
						}
					} else {
						if (index > -1) {
							selectedTopic.splice(index, 1);
							currentTopic.classList.remove('selected');
						}
					}
				});
			});

			document.getElementById('sendTopics').addEventListener('click', function (event) {
				// Evento asignado al botón que guarda lo seleccionado por el usuario final
				event.preventDefault();
				event.stopPropagation();
				event.stopImmediatePropagation();
				if ((selectedTopic.length !== 0) && indigitall.hasOwnProperty('topicsSubscribe')) {
					let selectedTopicString = selectedTopic.toString();
					console.log('Selected topics [' + selectedTopicString + '] sent');
					indigitall.topicsSubscribe(selectedTopic);
				}
				closeTopicsModal()
			});

			let closeTopicsModalButton = document.getElementById('iwpPublicTopicsModalClose');
			closeTopicsModalButton.addEventListener('click', function () {
				// Evento asignado al botón que cierra la modal
				closeTopicsModal()
			});

			iwp_success();
		}
 	});
}

function openTopicsModal() {
	window.scrollTo(0, 0);
	document.body.style.overflow = "hidden";
	document.getElementById('topicsModal').classList.remove('iwp-hide');
	localStorage.setItem("indigitall.repository.SHOW_TOPICS", '1');
}

function closeTopicsModal() {
	document.getElementById('topicsModal').classList.add('iwp-hide');
	document.body.style.overflow = "auto";
}