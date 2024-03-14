<?php
/**
 * Easy Video Reviews - Common
 * Common
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<!-- these elements wont' be shown on backend or frontend. These are only for compiling extra tailwind classes for compitibling with themes and plugins  -->

<!-- extra  -->
<div class="bg-red-600"></div>
<a href="" class="bg-indigo-400 hover:bg-indigo-500 text-sm bg-red-600"></a>
<a href="" class="bg-blue-400 hover:bg-blue-500 text-lg"></a>
<a href="" class="bg-red-400 hover:bg-red-500 text-xs"></a>
<a href="" class="bg-yellow-400 hover:bg-yellow-500 text-2xl"></a>
<a href="" class="bg-green-400 hover:bg-green-500 text-4xl"></a>
<a href="" class="mb-0 bg-pink-400 hover:bg-pink-500 text-md"></a>

<div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-3 lg:gap-4 lg:grid-cols-1">
	<div class="bg-gray-100 rounded-md"></div>
	<div class="bg-gray-100 rounded-md"></div>
	<div class="bg-gray-100 rounded-md"></div>
</div>
<div class="swiper-container evr-swiper">
	<div class="swiper-wrapper">

		<!-- single slider  -->
		<div class="bg-gray-100 rounded-md evr-reviews">
			<video class="z-50 cursor-pointer rounded-sm" controls data-src="https://storage.googleapis.com/wp-video-testimonial.appspot.com/user_admin/testimonial_1619939206_YWE0ODA0ODZhOQ%3D%3D.webm?GoogleAccessId=firebase-adminsdk-7gxkc%40wp-video-testimonial.iam.gserviceaccount.com&Expires=4070908800&Signature=p%2B7ut7tMgr8IYjCnHl8tO%2FN7a3gxwxXJsnPlBdCBhwZKLoO2zPLsjkpLArnOxjnUszQTlhdr55Crw5aatyyXYfUa%2BCvVzMVq8CImA7sg2ADGQEXALix8fKyr1INtHoOyweA2BjnnwOA3euDtPRc2iyD86DB1fwtAsEsjOM6XHFu8YaKfWxK5%2BBt6sFMKcke12l7pyGlxYD73Lr0wjgMBqjYDp1evrgftswYsB4o6YB0Cw2DxH1%2FpN3t8L8gbWPN9%2F28%2BgFOLRwRYlVd%2FIjrRBOtcHF7JPo6H%2FNE2tFyis6yV5tz1DZIbYf95M2clyefAioV5q43JW3qSLi69nuXZYA%3D%3D&generation=1619939273398194"></video>.
			<div class="swiper-lazy-preloader"></div>
			<div class="py-3 px-4 text-gray-400 text-sm flex items-center justify-between">
				<div class="flex items-center">
					<a href="javascript:;" data-play class="text-indigo-400 focus:text-indigo-500 w-6 h-6">
						<svg xmlns="http://www.w3.org/2000/svg" class="fill-current" viewBox="0 0 24 24">.
							<path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-3 17v-10l9 5.146-9 4.854z" />
						</svg>
					</a>
					<a style="display: none;" href="javascript:;" data-stop class="text-indigo-400 focus:text-indigo-500 w-6 h-6">
						<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">.
							<path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5 17h-10v-10h10v10z" />
						</svg>
					</a>
				</div>
				<span></span>
			</div>
		</div>


	</div>
	<div class="swiper-button-next w-4 h-4"></div>
	<div class="swiper-button-prev w-4 h-4"></div>
</div>
<div class="mt-6 text-left text-center text-right">
</div>

<button class="px-3 py-2  border rounded-l-md flex items-center justify-center text-gray-400 hover:text-indigo-500" href="">
	<Dashicon icon="align-left"></Dashicon>
</button>
<button class="px-3 py-2 border flex items-center justify-center text-gray-400 hover:text-indigo-500" href="">
	<Dashicon icon="align-center"></Dashicon>
</button>
<button class="px-3 py-2 border text-indigo-400 hover:text-indigo-400 rounded-r-md flex items-center justify-center text-gray-400 hover:text-indigo-500" href="">
	<Dashicon icon="align-right my-5 bg-blue-500 bg-gray-50"></Dashicon>
</button>
<!-- extra  -->
<a href="" class="bg-indigo-400 hover:bg-indigo-500 text-sm"></a>
<a href="" class="bg-blue-400 hover:bg-blue-500 text-lg"></a>
<a href="" class="bg-red-400 hover:bg-red-500 text-xs"></a>
<a href="" class="bg-yellow-400 hover:bg-yellow-500 text-2xl"></a>
<a href="" class="bg-green-400 hover:bg-green-500 text-4xl"></a>
<a href="" class="bg-pink-400 hover:bg-pink-500 text-md"></a>
<a href="" class="bg-purple-400 hover:bg-purple-500 text-md"></a>

<div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-3 lg:gap-4">
	<div class="bg-gray-100 rounded-md"></div>
	<div class="bg-gray-100 rounded-md"></div>
	<div class="bg-gray-100 rounded-md border border-red-200"></div>
</div>
<div class="my-4"><a href="javascript:;" class="text-2xl transition duration-100 py-2 px-4 rounded-sm bg-green-400 hover:bg-green-500 text-white" evr-open="product"></a></div>
<div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2 md:gap-3 lg:gap-4">
	<div class="swiper-lazy-preloader"></div>
</div>

<div class="relative container min-h-screen flex justify-center items-center"> <button id="share" class="cursor-pointer h-14 rounded-full w-14 hover:bg-blue-700 bg-blue-500 flex items-center justify-center focus:outline-none"><i class='bx bxs-share-alt text-2xl text-white'></i></button>
	<div id="share_icons" class="absolute opacity-0"> <span class="absolute flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white -top-14 left-8 cursor-pointer"><i class="bx bxl-twitter"></i></span> <span class="absolute flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white -top-4 left-12 cursor-pointer"><i class="bx bxl-facebook"></i></span> <span class="absolute flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white top-6 left-8 cursor-pointer"><i class="bx bxl-linkedin"></i></span> </div>
</div>

<div class="evr-recorder-button"></div>
<div class="grid bg-gray-50 grid-cols-1 grid-cols-2 mt-3 mb-2 mb-4  grid-cols-3 grid-cols-4 grid-cols-5 grid-cols-6 grid-cols-7 grid-cols-8 grid-cols-9 grid-cols-10 grid-cols-11 grid-cols-12  gap-1 sm:gap-2 md:gap-3 lg:gap-4"></div>

<div class="absolute top-0 left-0 w-full h-full flex items-end justify-center z-50 fill-current text-gray-50 h-20 w-20 text-white  w-40 absolute top-0 left-0 w-full h-full flex items-center justify-center z-50 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 mt-3 sm:grid-cols-1 grid-cols-1 md:grid-cols-1 lg:grid-cols-1 sm:grid-cols-2 grid-cols-2 md:grid-cols-2 lg:grid-cols-2 sm:grid-cols-3 grid-cols-3 md:grid-cols-3 lg:grid-cols-3 sm:grid-cols-4 grid-cols-4 md:grid-cols-4 lg:grid-cols-4 sm:grid-cols-5 grid-cols-5 md:grid-cols-5 lg:grid-cols-5 sm:grid-cols-6 grid-cols-6 md:grid-cols-6 lg:grid-cols-6 sm:grid-cols-7 grid-cols-7 md:grid-cols-7 lg:grid-cols-7 sm:grid-cols-8 grid-cols-8 md:grid-cols-8 lg:grid-cols-8 sm:grid-cols-9 grid-cols-9 md:grid-cols-9 lg:grid-cols-9 sm:grid-cols-10 grid-cols-10 md:grid-cols-10 lg:grid-cols-10 sm:grid-cols-11 grid-cols-11 md:grid-cols-11 lg:grid-cols-11 sm:grid-cols-12 grid-cols-12 md:grid-cols-12 lg:grid-cols-12"></div>
<div class="gap-0 sm:gap-0 md:gap-0 lg:gap-0 gap-1 sm:gap-1 md:gap-1 lg:gap-1 gap-2 sm:gap-2 md:gap-2 lg:gap-2 gap-3 sm:gap-3 md:gap-3 lg:gap-3 gap-4 sm:gap-4 md:gap-4 lg:gap-4 gap-5 sm:gap-5 md:gap-5 lg:gap-5 gap-6 sm:gap-6 md:gap-6 lg:gap-6 gap-7 sm:gap-7 md:gap-7 lg:gap-7 gap-8 sm:gap-8 md:gap-8 lg:gap-8 gap-9 sm:gap-9 md:gap-9 lg:gap-9 gap-10 sm:gap-10 md:gap-10 lg:gap-10 gap-11 sm:gap-11 md:gap-11 lg:gap-11 gap-12 sm:gap-12 md:gap-12 lg:gap-12"></div>
<button class="bg-bgGray-50 cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white"></button>
