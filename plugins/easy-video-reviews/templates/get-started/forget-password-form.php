<?php
/**
 * Easy Video Reviews - Forget Password Form
 * Forget Password Form
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<form @submit.prevent="reset" v-if="screen === 'reset'">

	<block small>
		<col-label><?php esc_html_e( 'Email', 'easy-video-reviews' ); ?></col-label>
		<block no_margin>
			<input type="email" class="form-input login-input block w-full" placeholder="Enter email" v-model="form.reset.email" :class="form.reset.error || (form.reset.email && !isEmail(form.reset.email)) ? 'border-error': ''" required @input="form.reset.error = false" />
		</block>
	</block>

	<div class="my-2 py-2 rounded px-4 bg-red-50 text-red-700 text-sm border border-red-100 rounded-sm text-center" v-if="form.reset.error">{{form.reset.error}}</div>

	<v-button size="sm" :loading="form.reset.loading" message="Please wait..">
		<?php esc_html_e( 'Send Me Email', 'easy-video-reviews' ); ?>
	</v-button>

	<div href="#" class="mt-4 mb-2 text-slate-500 my-3 text-sm text-center">
		<?php esc_html_e( 'Don\'t have any account?', 'easy-video-reviews' ); ?>
		<a href="#" class="no-underline text-sky-500 hover:text-sky-600 cursor-pointer" @click.prevent="setScreen('signup')">Get Started</a>
	</div>

	<div href="#" class=" text-slate-500 text-sm text-center">
		<?php esc_html_e( 'Already have an account?', 'easy-video-reviews' ); ?>
		<a href="#" class="no-underline text-sky-500 hover:text-sky-600 cursor-pointer" @click.prevent="setScreen('signin')">Login</a>
	</div>

</form>
