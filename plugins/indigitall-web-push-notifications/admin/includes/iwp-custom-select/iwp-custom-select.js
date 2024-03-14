    /*if the user clicks anywhere outside the select box, then close all select boxes:*/
    document.addEventListener("click", closeAllSelect);

    function createCustomSelectById(selectId, searchBox = false) {
        let selectedOption, newSelect, newOption;
        let defaultOption = '<option value="">EMPTY</option>';
        let currentSelect = document.getElementById(selectId);
        let customSelect = currentSelect.closest('.iwp-custom-select');

        if (currentSelect.options.length === 0) {
            currentSelect.innerHTML = defaultOption;
        }

        /*for each element, create a new DIV that will act as the selected item:*/
        selectedOption = document.createElement("DIV");
        selectedOption.classList.add("iwp-select-selected");
        selectedOption.innerHTML = getDataHtmlOrInnerHtml(currentSelect.options[currentSelect.selectedIndex]);
        customSelect.appendChild(selectedOption);

        /*for each element, create a new DIV that will contain the option list:*/
        newSelect = document.createElement("DIV");
        newSelect.classList.add("iwp-select-items", "iwp-select-hide");

        if (searchBox) {
            let searchBoxContainer = document.createElement("DIV");
            searchBoxContainer.classList.add("iwp-select-search-box");
            let searchBoxInput = document.createElement("INPUT");
            searchBoxInput.setAttribute('type', 'text');
            searchBoxInput.setAttribute('id', 'iwpSelectSearchBoxInput');
            searchBoxInput.classList.add('iwp-select-search-box-input');
            searchBoxContainer.appendChild(searchBoxInput);
            newSelect.appendChild(searchBoxContainer);
            searchBoxInput.addEventListener("click", function (e) {
                e.stopPropagation();
                e.stopImmediatePropagation();
            });
            searchBoxInput.addEventListener("keyup", function (e) {
                e.stopPropagation();
                e.stopImmediatePropagation();
                let valueToSearch = document.getElementById('iwpSelectSearchBoxInput').value.trim().toLowerCase();
                let itemsContainer = searchBoxInput.closest('.iwp-select-items');
                Array.from(itemsContainer.querySelectorAll('.iwp-select-items-item')).forEach(function(option) {
                    if (option.getAttribute('data-name').includes(valueToSearch)) {
                        option.classList.remove('iwp-hide');
                    } else {
                        option.classList.add('iwp-hide');
                    }
                });
            });
        }

        for (let j = 0; j < currentSelect.options.length; j++) {
            newOption = document.createElement("DIV");
            newOption.setAttribute('class', 'iwp-select-items-item');
            newOption.innerHTML = getDataHtmlOrInnerHtml(currentSelect.options[j]);
            newOption.dataset.id = currentSelect.options[j].value;
            if (currentSelect.options[j].hasAttribute('data-name')) {
                newOption.dataset.name = atob(currentSelect.options[j].dataset.name);
            }
            if (currentSelect.selectedIndex === j) {
                newOption.classList.add('iwp-same-as-selected');
            }

            newOption.addEventListener("click", function (e) {
                let originalSelect = document.getElementById(selectId);
                let currentTarget = e.target;
                if (!currentTarget.classList.contains('iwp-select-items-item')) {
                    // Si el target del clic es un elemento interno, seleccionamos el elemento padre correspondiente
                    currentTarget = this.closest('.iwp-select-items-item');
                }
                originalSelect.value = currentTarget.getAttribute('data-id');
                let outputLabel = originalSelect.closest('.iwp-custom-select').getElementsByClassName('iwp-select-selected')[0];
                outputLabel.innerHTML = currentTarget.innerHTML;
                originalSelect.dispatchEvent(new CustomEvent("click-custom-select-item", {
                    detail: {
                        value: currentTarget.getAttribute('data-id'),
                    },
                    bubbles: false,
                    composed: false
                }));
                Array.from(document.querySelectorAll('.iwp-select-items-item')).forEach(function(option) {
                    if (option.getAttribute('data-id') === currentTarget.getAttribute('data-id')) {
                        option.classList.add('iwp-same-as-selected');
                    } else {
                        option.classList.remove('iwp-same-as-selected');
                    }
                });
            });
            newSelect.appendChild(newOption);
        }
        customSelect.appendChild(newSelect);
        selectedOption.addEventListener("click", function (e) {
            /*when the select box is clicked, close any other select boxes,
            and open/close the current select box:*/
            e.stopPropagation();
            closeAllSelect(this);
            if (this.classList.contains('iwp-select-arrow-active')) {
                this.closest('.iwp-custom-select').getElementsByClassName('iwp-select-items')[0].classList.add('iwp-select-hide');
                this.classList.remove("iwp-select-arrow-active");
            } else {
                this.closest('.iwp-custom-select').getElementsByClassName('iwp-select-items')[0].classList.remove('iwp-select-hide');
                this.classList.add("iwp-select-arrow-active");
                if (this.closest('.iwp-custom-select').getElementsByClassName('iwp-select-search-box-input').length) {
                    this.closest('.iwp-custom-select').getElementsByClassName('iwp-select-search-box-input')[0].focus();
                }
            }
        });
    }

    /**
     * Busca si el elemento tiene el atributo 'data-html'.
     * Si el atributo existe, debe estar codificado en base64
     * Si lo tiene, devuelve su contenido descodificado.
     * Si no lo tiene, devuelve el innerHtml del elemento
     */
    function getDataHtmlOrInnerHtml(element) {
        let innerHtml = element.innerHTML;
        if (element.hasAttribute('data-html')) {
            let dataHtml = element.getAttribute('data-html');
            if (dataHtml !== '') {
                innerHtml = atob(dataHtml);
            }
        }
        return innerHtml;
    }

    function closeAllSelect(element) {
        /*a function that will close all select boxes in the document,
        except the current select box:*/
        let x, y, i, xl, yl, arrNo = [];
        x = document.getElementsByClassName("iwp-select-items");
        y = document.getElementsByClassName("iwp-select-selected");
        xl = x.length;
        yl = y.length;
        for (i = 0; i < yl; i++) {
            if (element === y[i]) {
                arrNo.push(i)
            } else {
                y[i].classList.remove("iwp-select-arrow-active");
            }
        }
        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("iwp-select-hide");
            }
        }
    }