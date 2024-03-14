        jQuery(document).ready(function($) {
			

            function limpa_formulário_cep(obj) {
                // Limpa valores do formulário de cep.
                obj.closest('form').find(".cf7-cep-autofill__rua, .cf7-cep-autofill__rua input").val("");
                obj.closest('form').find(".cf7-cep-autofill__bairro, .cf7-cep-autofill__bairro input").val("");
                obj.closest('form').find(".cf7-cep-autofill__cidade, .cf7-cep-autofill__cidade input").val("");
                obj.closest('form').find(".cf7-cep-autofill__uf, .cf7-cep-autofill__uf input, .cf7-cep-autofill__uf select").val("");
            }
            
            //Quando o campo cep perde o foco.
            $(".cf7-cep-autofill, .cf7-cep-autofill input").on('blur', function() {
				
				var obj = $(this);

                //Nova variável "cep" somente com dígitos.
                var cep = obj.val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        obj.closest('form').find(".cf7-cep-autofill__rua, .cf7-cep-autofill__rua input").val("...");
                        obj.closest('form').find(".cf7-cep-autofill__bairro, .cf7-cep-autofill__bairro input").val("...");
                        obj.closest('form').find(".cf7-cep-autofill__cidade, .cf7-cep-autofill__cidade input").val("...");
                        obj.closest('form').find(".cf7-cep-autofill__uf, .cf7-cep-autofill__uf input").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                obj.closest('form').find(".cf7-cep-autofill__rua, .cf7-cep-autofill__rua input").val(dados.logradouro);
                                obj.closest('form').find(".cf7-cep-autofill__bairro, .cf7-cep-autofill__bairro input").val(dados.bairro);
                                obj.closest('form').find(".cf7-cep-autofill__cidade, .cf7-cep-autofill__cidade input").val(dados.localidade);
                                obj.closest('form').find(".cf7-cep-autofill__uf, .cf7-cep-autofill__uf input, .cf7-cep-autofill__uf select").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep(obj);
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });