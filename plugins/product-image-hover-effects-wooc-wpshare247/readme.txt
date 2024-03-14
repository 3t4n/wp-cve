=== Plugin Name ===
Contributors: wpshare247, website366
Donate link: https://paypal.me/auvuonle/5
Tags: woocommerce, effect, product hover, product shadow, wooc
Requires at least: 4.9
Tested up to: 6.2
Requires PHP: 5.6
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Add effects when hovering over product Loop woocommerce, display more photo gallery, enlarge product photo gallery

Thêm hiệu ứng khi hover sản phẩm Loop woocommerce, hiển thị thêm thư viện ảnh, phóng to thư viện ảnh sản phẩm

== Description ==

Add effects when hovering over product Loop woocommerce, display more photo gallery, enlarge product photo gallery

Thêm hiệu ứng khi hover sản phẩm Loop woocommerce, hiển thị thêm thư viện ảnh, phóng to thư viện ảnh sản phẩm

**Video**

https://www.youtube.com/watch?v=QV14Olk9xJU

**Features**

* There are many effects to change
* Show product photo gallery
* photo gallery border
* Photo gallery border color
* Photo gallery location
* Outline of the product
* Product border color
* Create product shadow

**Tính năng**

* Có nhiều hiệu ứng để thay đổi
* Hiển thị thư viện ảnh sản phẩm
* Bo góc thư viện ảnh
* Màu viền thư viện ảnh
* Vị trí thư viện ảnh
* Viền ngoài sản phẩm
* Màu viền sản phẩm
* Tạo bóng sản phẩm

**Front page**

Change the number of photo galleries with filter ** ws247_piew_small_gallery_limit **, default : 4,
add below code into functions.php

Thay đổi số lượng thư viện ảnh bằng filter **ws247_piew_small_gallery_limit**, mặc định là 4,
Thêm đoạn code bên dưới vào file: functions.php

`
add_filter( 'ws247_piew_small_gallery_limit', 'your_new_piew_small_gallery_limit');
function your_new_piew_small_gallery_limit(){
	$new_limit = 3;
	return $new_limit;
}
`

**Liên hệ - Contact Us**

Professional website design - Thiết kế website chuyên nghiệp: [tbay.vn](https://tbay.vn)
Web design - Thiết kế web trọn gói: [website366.com](https://website366.com)
Sample Website - Thiết kế web giá rẻ: [waoweb.vn](https://waoweb.vn)
Wordpress Share - Học wordpress: [wpshare247.com](https://wpshare247.com)
Web Content - Viết bài chăm sóc web: [vietbaigiare.com](http://vietbaigiare.com)

== Installation ==

1. Tải thư mục `wooc-product-img-effects-wpshare247` vào đường dẫn `/wp-content/plugins/`
2. Kích hoạt từ menu **Plugins** (**Plugins > Installed Plugins**).

Tìm **Cấu hình Hover Wooc** hoặc **Configure Piew** menu trong WooCommerce.

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png

== Changelog ==

= 1.0 =

* Publishing plugin

= 1.0.1 =

* add_filter: ws247_piew_small_gallery_limit

= 1.0.2 =

* New effect: Overflow Background

= 1.0.3 =

* Compatible with flatsome theme

= 1.0.4 =

* New effect: Show Short Description

= 1.0.5 =

* Add to cart style

* Product border radius


= 1.0.6 =

* Fixed on Flatsome Child

= 1.0.7 =

* Check warning php

== Upgrade Notice ==

