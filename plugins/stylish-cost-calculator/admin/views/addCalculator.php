<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// declaring the templates to load from json file
$options = [
    'Venue Rental (template)',
    'Website Designer (template)',
    'Wedding Photographer (template)',
    'Car Rental (template)',
    'T-Shirt Printing (template)',
    'Cleaning Company (template)',
    'Funeral Home Company (template)',
    'Content Writing Agency (template)',
    'Audio Editing Services (template)',
    'Social Media Management (template)',
    'Student Fees (template)',
    'Digital Print and Lamination (template)',
    'Kitchens Renovations (template)',
    'Simple Video Budget (template)',
    'Food Catering (template)',
    'Pest Control Services (template)',
    'Book Publisher Service (template)',
    'Home Furniture Calculator (template)',
    'Vehicle Parts Calculator (template)',
    'Landscape & Patio Cost Calculator (template)',
    'Home Improvement Template (kitchen included) (template)',
    'Flooring, Carpet & Hardwood Estimator (template)',
    'Engaging Product & Service Quiz (template)',
    'Bakery Template (template)',
    'Grocery Store - Online Order Form (template) (available in premium version)',
    'Loan Calculator (template) (available in premium version)',
    'Painting Service Quote (template) (available in premium version)',
];

$choices_data = DF_SCC_QUIZ_CHOICES;
$icons_list   = [];

foreach ( $choices_data as $choices_collection ) {
    // $choices_data[$key]['choiceTitle'] = __( $value['choiceTitle'], 'smartcat-calculator' );
    foreach ( $choices_collection as $choice_props ) {
        if ( isset( $choice_props['icon'] ) && ! is_array( $choice_props['icon'] ) ) {
            array_push( $icons_list, $choice_props['icon'] );
        }

        if ( isset( $choice_props['icon'] ) && is_array( $choice_props['icon'] ) ) {
            foreach ( $choice_props['icon'] as $icon ) {
                array_push( $icons_list, $icon );
            }
        }
    }
}
$icons_list = array_unique( $icons_list );

array_push( $icons_list, 'arrow-left' );

$icons_rsrc = [];

foreach ( $icons_list as $icon ) {
    if ( isset( $this->scc_icons[ $icon ] ) ) {
        $icons_rsrc[ $icon ] = scc_get_kses_extended_ruleset( $this->scc_icons[ $icon ] );
    }
}

$opt_user_email = get_option( 'df_scc_emailsender', get_option( 'admin_email' ) );

if ( empty( $user_email ) ) {
    $opt_user_email = get_option( 'admin_email' );
}
$logged_in_user     = wp_get_current_user();
$opt_user_full_name = $logged_in_user->display_name;

if ( empty( $opt_user_full_name ) ) {
    $opt_user_full_name = $logged_in_user->user_login;
}

wp_localize_script( 'scc-backend', 'pageAddCalculator', [ 'nonce' => wp_create_nonce( 'add-calculator-page' ) ] );
?>
<div id="add-new-page-wrapper" class="container-fluid my-5 mx-auto with-vh">
	<div class="row">
		<div class="col page-section" id="welcome-section">
			<div class="bg-white">
				<div class="mx-auto py-5 text-center">
					<div class="head">
						<strong class="lead fw-bold display-5">Welcome! 👋</strong>
						<p class="fw-bold">Let’s build a new calculator</p>
					</div>
					<div class="action-btn mx-auto">
						<button type="button" class="btn-lg bg-scc-secondary mb-2 text-capitalize" data-btn-action="startQuiz">
							<span class="scc-icn-wrapper text-dark">
								<?php echo scc_get_kses_extended_ruleset( $this->scc_icons['check-square'] ); ?>
							</span>
							<span class="btn-text ms-2 text-dark">Start Setup Wizard</span>
						</button>
						<?php if ( ! defined( 'SCC_DEMO_SITE' ) ) { ?>
							<button type="button" class="btn-lg mb-2 bg-white scc-btn-primary-outlined text-capitalize" data-btn-action="showNewCalcNameInput">
								<span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit-2'] ); ?>
								</span>
								<span class="btn-text ms-2">Start from scratch</span>
							</button>
							<button type="button" class="btn-lg bg-white scc-btn-primary-outlined text-capitalize" data-btn-action="showTemplateChoices">
								<span class="scc-icn-wrapper">
									<?php echo scc_get_kses_extended_ruleset( $this->scc_icons['new-page-template-icon'] ); ?>
								</span>
								<span class="btn-text ms-2">Start with a template</span></button>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col page-section d-none" id="new-calc-creator-section">
			<div class="px-4 py-3 bg-white">
				<div class="head">
					<div class="text-muted text-uppercase">Option B</div>
					<strong>Start from scratch</strong>
				</div>
				<div class="body">
					<div class="input-group my-3">
						<input type="text" class="form-control" id="new-calc-name" placeholder="Calculator name">
					</div>
					<p>Create a new calculator from scratch with your own layout and style.</p>
					<p class="text-danger d-none">Please enter a name for the calculator</p>
				</div>
				<div class="action-btn">
					<button type="button" data-relative-field="new-calc-name" class="btn scc-btn-primary">Create calculator</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="template-loader-wrapper" class="container-fluid with-vh d-none mt-5">
	<div class="row row-cols-1 row-cols-sm-2 g-3">
		<div class="col" id="template-loader">
			<div class="bg-white">
				<div class="px-4 py-3">
					<div class="head">
						<div class="text-muted text-uppercase">Option A</div>
						<strong>Ready-to-Play template</strong>
					</div>
					<div class="body">
						<div class="input-group my-3">
							<select class="form-select scc-ts-search" id="choose-a-template">
								<?php for ( $i = 0; $i < count( $options ); $i++ ) { ?>
									<option data-preview-image="<?php echo esc_html( $options[ $i ] ) . '.png'; ?>" value=<?php echo intval( $i ); ?> <?php
                                    if ( $i == 15 ) {
                                        echo 'disabled';
                                    }
								    ?>
																																						><?php echo intval( $i ) + 1 . ' - ' . esc_html( $options[ $i ] ); ?></option>
									<?php
								}
?>
							</select>
						</div>
						<p>Select and lead a fully customizable template to start building with the first blocks</p>
						<p class="text-danger d-none">You have to choose an option</p>
					</div>
					<div class="action-btn">
						<button type="button" class="btn scc-btn-primary" data-relative-field="choose-a-template">Create Calculator</button>
					</div>
				</div>
			</div>
		</div>
		<div class="text-center d-none" id="calc-preview-wrapper">
			<!-- Bootstrap alert to show that the template might look different due to not all elements available in the free version -->
			<div class="d-none alert alert-warning alert-dismissible fade show" role="alert">
				<strong>Heads up!</strong> This template might look different in the free version of the plugin. Some elements are only available in the pro version.
			</div>
			<img src="<?php echo esc_url( SCC_TEMPLATE_PREVIEW_BASEURL . '/Audio%20Editing%20Services%20(template).png' ); ?>" alt="">
		</div>
	</div>
</div>
<div class="modal fade quiz-modal" id="quizModal" aria-hidden="true" aria-labelledby="quizModalLabel" tabindex="-1">
</div>
<div class="modal fade quiz-modal" id="quizModal2" aria-hidden="true" aria-labelledby="quizModalLabel2" tabindex="-1">
</div>
<div class="modal fade quiz-modal" id="quizModal3" aria-hidden="true" aria-labelledby="quizModalLabel3" tabindex="-1">
</div>
<div class="modal fade quiz-modal" id="quizModal4" aria-hidden="true" aria-labelledby="quizModalLabel4" tabindex="-1">
</div>
<div class="modal fade quiz-modal" id="quizModal5" aria-hidden="true" aria-labelledby="quizModalLabel5" tabindex="-1">
</div>
<div class="modal fade quiz-modal" id="quizResult" aria-hidden="true" aria-labelledby="quizModalResult" tabindex="-1">
</div>
<style>
	.action-btn {
		display: flex;
		flex-direction: column;
		width: 50%;
	}

	.backdropped-swal {
    	backdrop-filter: blur(5px);
	}

	.swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation {
    	background: rgba(0, 0, 0, .85) !important;
	}

	#quiz-result-email-form .scc-wql-field-warnings input {
		border: 1px solid #dc3545 !important;
		border-radius: 0.25rem;
	}

	#quiz-result-email-form #wq_field_wrapper:not(.scc-wql-field-warnings) p.text-danger {
		display: none;
	}

	.vh-95 {
		height: 95vh;
	}

	.modal-head .text-muted {
		font-size: 16px;
	}

	#wq_optin_for_comm {
		margin: 0.25em 0 0 0.5em;
	}

	#wq-optin-checkbox-wrapper {
		box-shadow: unset;
		background-color: #fcfcfe;
		padding: 10px 0;
		border-radius: 5px;
	}

	.action-btn span.scc-icn-wrapper {
		color: var(--scc-color-primary);
		float: left;
	}

	.action-btn span.btn-text {
		color: var(--scc-color-primary);
		float: left;
	}

	.modal .card .scc-icn-wrapper {
		color: var(--scc-color-primary);
	}

	label.card {
		position: relative;
		user-select: none;
	}
	@media (max-width: 768px) {
		.action-btn {
			width: 80%;
		}
		label.card {
			padding: 0.7em 0.5em 1em;
		}
		label.card .btn-content{
			display: flex;
			flex-direction: column;
			align-items: center;
		}
	}

	a.card-help-icn:before {
		background-color: white;
		color: black;
		display: inline-block;
		border-radius: 50%;
		border: 1px solid grey;
		text-decoration: none;
		text-align: center;
		line-height: 18px;
		width: 18px;
		transition-duration: 0.4s;
		transform: scale(0);
	}

	a.card-help-icn {
		display: inline;
	}
	.has-help-article a.card-help-icn:before {
		border: 1px solid var(--bs-gray-dark);
		font-style: normal;
		transform: scale(1);
		content: "?";
	}

	a.card-help-icn:focus {
		box-shadow: unset;
		outline: unset;
		color: initial;
	}

	input:checked + label.card,
	input:checked + .btn-checkbox {
		background-color: var(--scc-wizard-quiz-card-color);
		box-shadow: 0px 0px 0 0px #fff, 0 0 0 0.25rem rgb(59 60 62 / 9%);
	}

	.modal .card {
		border: unset;
		box-shadow: 0px -1px 0 1px #fff, 0 0 0 0.25rem rgb(59 60 62 / 9%);
		/* padding: unset; */
	}

	.modal.fade .modal-dialog {
		max-width: 780px !important;
	}

	/**
	* font settings for modals
	 */
	.modal.fade .modal-title {
		font-size: 24px;
	}

	.choices-wrapper p {
		font-size: 12px;
	}

	.choices-wrapper p.mb-0,
	p.lead-text {
		font-size: 18px;
	}

	.choices-wrapper label.card p {
		font-size: 14px;
	}

	.choices-wrapper label.card p.result-card {
		display: inline;
	}

	.two-row-btn {
		min-height: 80px;
	}

	.single-row-btn {
		min-height: 40px;
	}

	.two-row-btn .btn-content,
	.single-row-btn .btn-content {
		margin: auto 0;
	}

	#quiz-result-email-form {
		padding: 1rem 1rem 1rem 1rem;
		background: var(--scc-color-primary);
		border-radius: 10px;
	}
	.scc-result-section-padding{
		padding-left: 5rem !important;
		padding-right: 5rem !important;
	}
	.scc-result-section-margin{
		margin-left: 5rem !important;
		margin-right: 5rem !important;
	}
	#welcome-section p,
	#welcome-section button {
		font-size: 16px;
	}
	#welcome-section .action-btn button {
		height: 55px;
	}

	.btn.scc-btn-primary:hover,
	.btn.scc-btn-primary:focus {
		color: #fff;
	}

	.modal input[type=checkbox]:not(.form-check-input), .modal input[type=radio] {
		margin: .25em 0 0 0;
	}
	.click-tip {
		color: var(--scc-color-primary);
	}

	.modal-back-nav {
		cursor: pointer;
		float: left;
	}

	.choices-wrapper .form-check {
		/* padding: 15px; */
		box-shadow: 0px 0px 10px #2b2b2b15;
		/* margin-top: 10px;
		margin-left: -5px;
    	margin-right: 10px;
		border-radius: 5px; */
	}

	.question-desc-text::first-letter {
		text-transform: capitalize;
	}


	@media (min-width: 1400px) {
		.container-fluid.my-5 {
			max-width: 40% !important;
		}
	}
</style>
<script type="text/json" id="svgCollection">
<?php
echo wp_json_encode(
    $icons_rsrc
);
?>
</script>
<script type="text/json" id="choices-data">
	<?php
    echo wp_json_encode( $choices_data );
?>
</script>
<script type="text/json" id="telemetry-status">
	<?php
$opted_in_for_telemetry = get_option( 'scc_opted_in_for_telemetry', false );
echo wp_json_encode( [ 'usageTrackingAllowed' => $opted_in_for_telemetry ] );
?>
</script>
<script type="text/html" id="tmpl-quiz-modal-content">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content p-3 vh-95">
			<div class="px-3 pt-3 text-center modal-head">
				<# if (data.currentStep != 1) { #>
					<span class="scc-icn-wrapper modal-back-nav" data-dismiss="modal" onclick="handleBackNavigation({{ data.currentStep == 'Result' ? 4 : data.currentStep - 1 }}, this)">{{{svgCollection['arrow-left']}}}</span>
				<# } #>
				<h1 class="{{ data.currentStep === 'Result' ? 'mt-5 mb-4' : ''  }}">{{{data.title}}}</h1>
				<p class="text-muted <# if ( data.currentStep == 'Result' ) { #> scc-text-black <# } #> scc-result-section-padding">{{{data.subtitle}}}</p>
			</div>
			<div class="modal-body pt-0">
				<# if (data.modalLead && data.modalLead.length) { #>
				<p class="fw-bold lead-text">{{{data.modalLead}}}</p>
				<# } #>
				<# if (data?.showFormNameInput) { #>
					<div class="px-4 py-3 bg-white">
						<div class="head">
							<strong>Please enter a name for the calculator form</strong>
						</div>
						<div class="body">
							<div class="input-group my-3">
								<input type="text" autofocus class="form-control" id="calc-from-answes-name" placeholder="Calculator name">
							</div>
							<p>Create a new calculator from the answers you have given in the previous steps.</p>
							<p class="text-danger d-none" data-warning-for="calc-from-answes-name">The above field cannot be empty</p>
						</div>
						<div class="action-btn">
							<button type="button" data-relative-field="calc-from-answes-name" class="btn scc-btn-primary">Create calculator</button>
						</div>
					</div>
				<# } #>
				<# if ( data.currentStep == 'Result' ) { #>
				<div class="choices-wrapper"></div>
				<# } else { #>
				<div class="choices-wrapper row row-cols-2"></div>
				<# } #>
			</div>
			<# if ( Boolean( data?.actionBtnTitle?.length ) ) { #>
				<# if ( data.currentStep !== 'Result' ) { #>
				<div class="modal-footer d-block">
					<div class="d-grid gap-2">
						<button class="btn scc-setup-wizard-button scc-btn-primary" data-max-steps="5" data-next-step={{ data.quizNextStep }} type="button">{{data.actionBtnTitle}}</button>
					</div>
				</div>
				<# } #>	
			<# } #>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-quiz-choices-content">
		<# data.choices.forEach( (choice, i) => {
			let currentKey = UUIDv4.generate();
			let answersStoreIndex = quizAnswersStore['step' + data.step][choice.key];
			let hasHelpLink = choice.helpLink && choice.helpLink !== '#';
		#>
			<div class="col d-flex flex-column g-3 user-select-none">	
                <input class="form-check-input col-sm-1" hidden {{ answersStoreIndex == true ? 'checked' : '' }} type="checkbox" name="{{ choice.key }}" id="key-{{ currentKey }}">
				<label class="card {{ choice.key !== 'others' ? 'has-help-article' : '' }} m-0 flex-sm-grow-1 justify-content-center" for="key-{{ currentKey }}">
					<div class="form-check-label col-sm-11 d-inline-block ps-1">
						<div class="question-title-wrapper">
							<i class="material-icons align-middle text-primary">{{ choice.icon }}</i>
							<strong>{{ choice.choiceTitle }}</strong>&nbsp;
							<# if (hasHelpLink) { #>
								<a href="{{{ choice.helpLink }}}" target="_blank" class="card-help-icn" title="Click to learn more"></a>
							<# } #>
						</div>
						<p class="pt-1 question-desc-text">{{ choice.choiceDescription }}</p>
					</div>
				</label>
			</div>
			<# if (choice.key == 'others') { #>
				<div class="form-check w-100 d-none">
					<input class="form-control form-control-sm mb-2 mt-2 w-100 others-input" type="text" name="step{{data.step}}-othersInput" id="othersInput-{{currentKey}}" placeholder="Please specify" required="">
					<label class="form-check-label" for="othersInput-{{currentKey}}">Others</label>
				</div>
			<# } #>
		<# }) #>
</script>
<script type="text/html" id="tmpl-quiz-columned-card-choices-content">
<# if (data.step == 'Result' && data.choices?.length) {  #>
	<p class="mb-0">Recommended <b>Features</b></p>
<# } #>
<div class="row row-cols-3 g-0 w-100">
	<# 
	if (data.step == 'Result') {
		data = filterResultPageSuggestions(data);
	}
	data.choices.forEach( (choice, i) => {
		let currentKey = UUIDv4.generate();
		let icon = typeof(choice.icon) == 'string' ? svgCollection[choice.icon] : choice.icon.map(z => svgCollection[z]).join('');
		let choiceCardClasses = [];
		if (choice.helpLink && choice.helpLink.length > 1) {
			choiceCardClasses.push('has-help-article');
		}
		let title = choice?.choiceSimpleTitle ? choice.choiceSimpleTitle : choice.choiceTitle;
		let hasHelpLink = choice.helpLink && choice.helpLink !== '#';
	#>
	<div class="col d-flex flex-column g-3 {{ data.step === 'Result' ? 'd-none' : '' }}">
		<input type="checkbox" {{ quizAnswersStore['step' + data.step][choice.key] == true ? 'checked' : '' }} class="d-none" name="{{ choice.key }}" id={{currentKey}}>
		<label class="card {{ choiceCardClasses.join( ' ' ) }} two-row-btn text-center mt-0 py-0 flex-sm-grow-1" role="button" for={{currentKey}}>
			<span class="btn-content">
				<span class="scc-icn-wrapper">{{{ icon }}}</span>
				<p class="mb-0 mt-2 result-card me-1">{{ title }}</p>
				<# if ( hasHelpLink ) { #>
				<a href="{{{ choice.helpLink }}}" target="_blank" class="card-help-icn" title="click to learn more"></a>
				<# } #>
			</span>
		</label>
	</div>
	<# }) #>
  </div>
  <# if (data.step == 'Result') { #>
	<# if (data.elementSuggestions?.length) { #>
  <div class="mt-3 d-none">
	<p class="mb-0">Recommended <b>Elements</b></p>
	<div class="row row-cols-3 g-0">
		<# data.elementSuggestions.forEach( (choice, i) => {
			let currentKey = UUIDv4.generate();
			let choiceCardClasses = [];
			let icon = typeof(choice.icon) == 'string' ? svgCollection[choice.icon] : choice.icon.map(z => svgCollection[z]).join('');
			if (choice.helpLink) {
				choiceCardClasses.push('has-help-article');
			}
			let title = choice?.choiceSimpleTitle ? choice.choiceSimpleTitle : choice.choiceTitle;
			let hasHelpLink = choice.helpLink && choice.helpLink !== '#';
		#>
		<div class="col d-flex flex-column g-3">
			<input type="checkbox" {{ quizAnswersStore['step' + data.step][choice.key] == true ? 'checked' : '' }} data-element-suggestion="1" class="d-none" name="{{ choice.key }}" id={{currentKey}}>
			<label class="card {{ choiceCardClasses.join( ' ' ) }} single-row-btn text-center mt-0 py-0 flex-sm-grow-1" role="button" for={{currentKey}}>
				<span class="btn-content">
					<span class="scc-icn-wrapper">{{{ icon }}}</span>
					<p class="mb-0 mt-2 result-card me-1">{{ title }}</p>
					<# if ( hasHelpLink ) { #>
						<a href="{{{ choice.helpLink }}}" target="_blank" class="card-help-icn" title="Click to learn more"></a>
					<# } #>
				</span>
			</label>
		</div>
		<# }) #>
	</div>
  </div>
  <# } #>
  <form class="mt-4 scc-result-section-margin" id="quiz-result-email-form">
	<div id="wq_field_wrapper">
		<div class="mt-3">
			<label for="wq_your_name" class="form-label text-white">Your Name</label>
			<input type="text" class="form-control" value="<?php echo esc_attr( $opt_user_full_name ); ?>" id="wq_your_name">
		</div>
		<p class="m-0 text-danger">Please fill out the field above</p>
		<div class="mt-3">
			<label for="wq_your_email" class="form-label text-white">Your Email</label>
			<input type="email" class="form-control" value="<?php echo esc_attr( $opt_user_email ); ?>" id="wq_your_email">
		</div>
		<p class="m-0 text-danger">Please fill out the field above</p>
		<button class="btn scc-setup-wizard-button scc-backend-wizard-button mt-4 w-100" data-max-steps="5" data-next-step="0" data-result-action="email" type="button">	
			Email Setup Steps & Open Builder
			<div class="scc-liquid-shape"></div>
		</button>
  </div>
</form>
<div class="text-center pt-4 mt-4 mb-3 pb-4 scc-backend-wizard-text"><strong>Or</strong></div>
<div class="text-center"><a href="#" class="scc-text-black scc-setup-wizard-button scc-font-size-normal" data-max-steps="5" data-next-step="0" data-result-action="pdf" ><strong>Download Step-by-Step PDF & Open Builder</strong></a></div>
  <# } #>
</script>
