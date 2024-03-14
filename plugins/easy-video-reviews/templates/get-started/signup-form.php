<?php
/**
 * Easy Video Reviews - Signup Form
 * Signup Form
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<form @submit.prevent="signup" v-if="screen == 'signup' || screen == 'reviews'">

	<div href="#" class="my-3 py-2 text-slate-500 text-sm tracking-wide">
		<?php esc_html_e( 'Get started within just 3 seconds!', 'easy-video-reviews' ); ?>
	</div>


	<block class="mb-5">
		<col-label class="text-slate-400"><?php esc_html_e( 'Enter email', 'easy-video-reviews' ); ?> <span class="ml-1 text-red-500">*</span></col-label>
		<block no_margin>
			<input type="email" class="form-input" :class="form.signup.error || (form.signup.email && !isEmail(form.signup.email)) ? 'border-error': ''" placeholder="Enter email" v-model="form.signup.email" required @input="form.signup.error = false" />
		</block>
	</block>

	<div class="flex flex-col gap-1.5 mb-2">
		<col-label class="text-slate-400"><?php esc_html_e( 'Choose password', 'easy-video-reviews' ); ?> <span class="ml-1 text-red-500">*</span></col-label>
		<block small class="relative">
			<input :type="form.signup.showPassword ? 'text' : 'password'" class="form-input" placeholder="Enter Password" v-model="form.signup.password" :class="form.signup.password && form.signup.password.length < 8 ? 'border-error' : ''" required />
			<v-button @click.prevent="form.signup.showPassword = !form.signup.showPassword" size="sm" class="text-xs absolute right-0 top-1/2 h-full -translate-y-1/2"><span class="dashicons" :class="!form.signup.showPassword ? 'dashicons-hidden' : 'dashicons-visibility'"></span></v-button>
		</block>
	</div>

	<div class="text-sm text-gray-400 mb-3 pt-0">
		By getting started, you're accepting our <a class="text-sky-600 hover:text-sky-700 no-underline" href="https://wppool.dev/privacy-policy/" target="_blank" rel="nofollow">Privacy Policy</a>..
	</div>

	<div class="my-2 py-2 rounded px-4 bg-red-50 text-red-700 text-sm border border-red-100 rounded-sm text-center" v-if="form.signup.error">{{form.signup.error}}</div>

	<v-button size="sm" :disabled="form.reset.loading" :loading="form.signup.loading" message="Please wait...">
		<span><?php esc_html_e( 'Get Started', 'easy-video-reviews' ); ?></span>
	</v-button>

	<div class="mt-6 text-slate-500 text-sm text-center">
		<?php esc_html_e( 'Already have an account?', 'easy-video-reviews' ); ?>
		<a href="#" class="no-underline text-sky-500 hover:text-sky-600 cursor-pointer" @click.prevent="setScreen('signin')">Login</a>
	</div>
</form>
