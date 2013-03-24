COMPOSER	= composer.phar
NPM 		= node_modules
BOWER		= components
VENDORS 	= vendors
BIN 		= $(VENDORS)/bin

DEBUG 		= 0
OMIT_0 		= &>/dev/null
OMIT_1 		=
OMIT 		= $(OMIT_$(DEBUG))

CHECK		=\033[32m✔ Done\033[39m
HR  		=\033[37m--------------------------------------------------\033[39m

check-composer:
	@printf "Checking if Composer is installed..."
	@test -f $(COMPOSER) || curl -s -S http://getcomposer.org/installer | php $(OMIT)
	@echo "        $(CHECK)"

check-installation:
	@make check-composer

	@printf "Checking for vendor directory..."
	@test -d $(VENDORS) || make install $(OMIT)
	@echo "            $(CHECK)"

	@printf "Checking for bin directory..."
	@test -d $(BIN) || make install $(OMIT)
	@echo "               $(CHECK)"

install:
	@echo "\n"
	@echo "\033[36mInstalling Vanilla API...\033[39m"
	@echo "${HR}"

	@make check-composer

	@printf "Installing Composer packages..."
	@php $(COMPOSER) install --dev $(OMIT)
	@echo "             $(CHECK)"

	@printf "Removing unnecessary directories..."
	@rm -rf $(VENDORS)/doctrine/common/bin/
	@rm -rf $(VENDORS)/doctrine/common/tests/
	@rm -rf $(VENDORS)/zircote/swagger-php/scripts/
	@rm -rf $(VENDORS)/zircote/swagger-php/tests/
	@echo "         $(CHECK)"

	@printf "Installing Node.js packages..."
	@npm install $(OMIT)
	@echo "              $(CHECK)"

	@printf "Installing Bower packages..."
	@bower install $(OMIT)
	@echo "                $(CHECK)"

	@printf "Compiling the API Explorer..."
	@npm run-script build $(OMIT)
	@grunt compile $(OMIT)
	@echo "               $(CHECK)"

	@echo "${HR}"
	@echo "\033[36mSuccess!\n\033[39m"

update:
	@echo "\n"
	@echo "\033[36mUpdating Vanilla API...\033[39m"
	@echo "${HR}"

	@make check-installation

	@printf "Updating Composer packages..."
	@php $(COMPOSER) update --dev $(OMIT)
	@echo "               $(CHECK)"

	@echo "${HR}"
	@echo "\033[36mSuccess!\n\033[39m"

clean:
	@echo "\n"
	@echo "\033[36mUninstalling Vanilla API...\033[39m"
	@echo "${HR}"

	@printf "Removing Composer files and packages..."
	@rm -f composer.phar
	@rm -f composer.lock
	@rm -rf $(VENDORS)
	@echo "     $(CHECK)"

	@printf "Removing Node.js packages..."
	@rm -rf $(NPM)
	@echo "                $(CHECK)"

	@printf "Removing Bower packages..."
	@rm -rf $(BOWER)
	@echo "                  $(CHECK)"

	@printf "Removing the API Explorer..."
	@rm -f design/api.css
	@echo "                $(CHECK)"
	
	@echo "${HR}"
	@echo "\033[36mSuccess!\n\033[39m"