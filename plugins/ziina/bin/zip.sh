#!/usr/bin/env bash

# Exit if any command fails.
set -e

# Change to the expected directory.
DIR=$(pwd)
BUILD_DIR="$DIR/build/woocommerce-gateway-ziina-payment"

# Enable nicer messaging for build status.
BLUE_BOLD='\033[1;34m';
GREEN_BOLD='\033[1;32m';
RED_BOLD='\033[1;31m';
YELLOW_BOLD='\033[1;33m';
COLOR_RESET='\033[0m';
error () {
    echo -e "\n${RED_BOLD}$1${COLOR_RESET}\n"
}
status () {
    echo -e "\n${BLUE_BOLD}$1${COLOR_RESET}\n"
}
success () {
    echo -e "\n${GREEN_BOLD}$1${COLOR_RESET}\n"
}
warning () {
    echo -e "\n${YELLOW_BOLD}$1${COLOR_RESET}\n"
}

status "💃 Time to build 🕺"

# remove the build directory if exists and create one
rm -rf "$DIR/build"
mkdir -p "$BUILD_DIR"

# Install composer dependencies.
composer install --optimize-autoloader --no-dev -q

# Copy all files
status "Copying files... ✌️"
FILES=(includes languages vendor index.php main-class-shortcut.php readme.txt)

for file in ${FILES[@]}; do
  cp -R $file $BUILD_DIR
done

# go one up, to the build dir
if command -v zip
then
  status "Creating archive... 🎁"

  cd ./build
  #zip -r -q woocommerce-gateway-ziina-payment.zip woocommerce-gateway-ziina-payment

  # remove the source directory
  #rm -rf ./woocommerce-gateway-ziina-payment
else
  warning "zip command not found. Create archive by yourself ./build/woocommerce-gateway-ziina-payment"
fi

success "Done. You've built plugin! 🎉 "