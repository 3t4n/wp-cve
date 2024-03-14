=== VietQR ==
Contributors: thichvivu, huuhao1999, diepmagik
Tags: vietqr, viet qr, qrcode, vietcombank, techcombank
Requires at least: 4.7
Tested up to: 6.0
Stable tag: 3.5.2
Version: 3.5.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Tự động tạo mã QR ngân hàng cho từng đơn hàng. Mã QR sẽ nhúng sẵn số tiền, mã đơn hàng, người mua quét QR xong chỉ cần bấm xác nhận là chuyển xong ngay, không cần nhập thêm thông tin. VietQR là tiêu chuẩn QR ngân hàng tạo bởi NAPAS, được chấp nhận bởi 37 ứng dụng ngân hàng lớn nhất Việt Nam: Vietcombank, Vietinbank, BIDV, ACB, VPBank, MBank, TPBank, Digimi, MSB
Chuyển khoản ngân hàng "siêu nhanh" nhờ mã VietQR. Đây là mã chuẩn mã QR tạo bởi NAPAS, được chấp nhận bởi 37 ứng dụng ngân hàng lớn nhất Việt Nam: Vietcombank, Vietinbank, BIDV, ACB, VPBank, MBank, TPBank, Digimi, MSB ... Dành riêng cho Woocommerce.

== Description ==

VietQR hiện hỗ trợ 50 ngân hàng nhận tiền. Bất kì ngân hàng nào tham gia vào hệ thống chuyển khoản ngân hàng 24/7 đều có thể tạo ra một mã QR để nhận tiền.
Cho đến hiện tại, Có 37 ngân hàng hoạt động tại Việt Nam đã hỗ trợ quét mã VietQR để chuyển tiền trên ứng dụng ngân hàng.

Tính năng : 

* Thêm lựa chọn thanh toán: Chuyển khoản ngân hàng (Quét Mã QR)
* Tự động tạo nội dung chuyển tiền chứa mã đơn hàng, dễ cho quản trị viên hoặc phần mềm xác nhận giao dịch thanh toán.
* Liên tục cập nhật các ngân hàng mới
* Hỗ trợ nhiều loại mẫu QR khác nhau

Plugin sử dụng công nghệ tạo QR cung cấp tại [VietQR.IO](https://www.vietqr.io )

Danh sách các ngân hàng đã hỗ trợ quét mã VietQR trong App:

* ABBANK - Ngân hàng TMCP An Bình
* ACB - Ngân hàng TMCP Á Châu
* BacABank - Ngân hàng TMCP Bắc Á
* BIDV - Ngân hàng TMCP Đầu tư và Phát triển Việt Nam
* BaoVietBank - Ngân hàng TMCP Bảo Việt
* Eximbank - Ngân hàng TMCP Xuất Nhập khẩu Việt Nam
* HDBank - Ngân hàng TMCP Phát triển Thành phố Hồ Chí Minh
* VietinBank - Ngân hàng TMCP Công thương Việt Nam
* KienLongBank - Ngân hàng TMCP Kiên Long
* LienVietPostBank - Ngân hàng TMCP Bưu Điện Liên Việt
* MBBank - Ngân hàng TMCP Quân đội
* MSB - Ngân hàng TMCP Hàng Hải
* NamABank - Ngân hàng TMCP Nam Á
* NCB - Ngân hàng TMCP Quốc Dân
* OCB - Ngân hàng TMCP Phương Đông
* Oceanbank - Ngân hàng Thương mại TNHH MTV Đại Dương
* PGBank - Ngân hàng TMCP Xăng dầu Petrolimex
* PVcomBank - Ngân hàng TMCP Đại Chúng Việt Nam
* SCB - Ngân hàng TMCP Sài Gòn
* SeABank - Ngân hàng TMCP Đông Nam Á
* SaigonBank - Ngân hàng TMCP Sài Gòn Công Thương
* SHB - Ngân hàng TMCP Sài Gòn - Hà Nội
* Sacombank - Ngân hàng TMCP Sài Gòn Thương Tín
* ShinhanBank - Ngân hàng TNHH MTV Shinhan Việt Nam
* Techcombank - Ngân hàng TMCP Kỹ thương Việt Nam
* TPBank - Ngân hàng TMCP Tiên Phong
* VietABank - Ngân hàng TMCP Việt Á
* Agribank - Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam
* Vietcombank - Ngân hàng TMCP Ngoại Thương Việt Nam
* VietCapitalBank - Ngân hàng TMCP Bản Việt
* VIB - Ngân hàng TMCP Quốc tế Việt Nam
* VietBank - Ngân hàng TMCP Việt Nam Thương Tín
* VPBank - Ngân hàng TMCP Việt Nam Thịnh Vượng
* COOPBANK - Ngân hàng Hợp tác xã Việt Nam
* CAKE - TMCP Việt Nam Thịnh Vượng - Ngân hàng số CAKE by VPBank
* Ubank - TMCP Việt Nam Thịnh Vượng - Ngân hàng số Ubank by VPBank
* KBank - Ngân hàng Đại chúng TNHH Kasikornbank

== Frequently Asked Questions ==

== Screenshots ==

1. Giao diện thanh toán trên Điện thoại
2. Giao diện thanh toán trên Máy tính
3. Giao diện chọn thanh toán bằng VietQR
4. Giao diện quản trị
4. Giao diện email thông báo cho người dùng đã đặt hàng thành công, quét mã để thanh toán.

== Changelog ==

= 3.5.0 =
* Hỗ trợ giao diện checkout mới của WooCommerce

= 2.1.1 =
* Fix một số lỗi về chính tả

= 2.1 =
* Support more 20 banks.

= 1.7 =
Fix compatible with some themes.

= 1.5 =
* Fixed display of QR codes in emails

= 1.4 =
* Cache API call for improve performance

= 1.3 =
* Improve performance

= 1.2 =
* Show Button download QR Code on Mobile 

= 1.0.0 =
* First version
* Support 50 vietnam banks.