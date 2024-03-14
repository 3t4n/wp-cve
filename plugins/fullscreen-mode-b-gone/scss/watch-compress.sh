# sh watch-compressed.sh
find ../../. -name "._*" -type f -delete
sass --watch --style compressed fullscreen-mode-b-gone-admin.scss:../css/fullscreen-mode-b-gone-admin.min.css -C