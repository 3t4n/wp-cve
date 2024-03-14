<?php
/**
 * Easy Video Reviews - Signin Form
 * Signin Form
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<form @submit.prevent="signin" v-if="screen==='signin'">



	<block small>
		<col-label for="signin_email"><?php esc_html_e( 'Email', 'easy-video-reviews' ); ?></col-label>
		<block no_margin>
			<input id="signin_email" type="email" v-model="form.signin.email" class="form-input" :class="form.signin.error || (form.signin.email && !isEmail(form.signin.email)) ? 'border-error': ''" placeholder="Enter email" required @input="form.signin.error = false" />
		</block>
	</block>

	<block small>
		<col-label><?php esc_html_e( 'Password', 'easy-video-reviews' ); ?></col-label>
		<block no_margin>
			<input type="password" class="form-input" :class="form.signin.error === 'password' ? ['ring-1 ring-red-400']: ''" placeholder="Enter Password" v-model="form.signin.password" required @input="form.signin.error = false" />
		</block>
	</block>

	<div class="my-2 py-2 rounded px-4 bg-red-50 text-red-700 text-sm border border-red-100 rounded-sm text-center" v-if="form.signin.error">{{form.signin.error}}</div>

	<v-button size="sm" :loading="form.signin.loading" message="Please wait..">
		<span><?php esc_html_e( 'Login', 'easy-video-reviews' ); ?></span>
	</v-button>

	<div class="text-slate-600 text-sm mt-4 text-center">
		<a @click.prevent="setScreen('reset')" class="text-sky-500 hover:text-sky-600 no-underline cursor-pointer"><?php esc_html_e( 'Forgot Password?', 'easy-video-reviews' ); ?></a>
	</div>

	<div href="#" class="text-slate-500 text-sm py-2 text-center">
		<?php esc_html_e( 'Don\'t have any account?', 'easy-video-reviews' ); ?>
		<a href="#" class="text-sky-500 hover:text-sky-600 no-underline cursor-pointer" @click.prevent="setScreen('signup')"><?php esc_html_e( 'Get Started', 'easy-video-reviews' ); ?></a>
	</div>


</form>
