.PHONY: all
all: assets/js/admin-script.min.js assets/js/script.min.js assets/css/styles.min.css assets/css/admin-styles.min.css

assets/css/admin-styles.min.css: assets/css/admin-styles.css
	lightningcss --minify $^  -o $@

assets/css/styles.min.css: assets/css/styles.css
	lightningcss --minify $^  -o $@

assets/js/admin-script.min.js: assets/js/admin-script.js
	terser $^ --compress --mangle -o $@

assets/js/script.min.js: assets/js/script.js
	terser $^ --compress --mangle -o $@
