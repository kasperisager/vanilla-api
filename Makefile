COMPOSER	= php composer.phar
NPM			= node_modules
VENDORS 	= vendors
BIN			= $(VENDORS)/bin

.check-composer:
	@echo "Checking if Composer is installed..."
	@test -f composer.phar || curl -s http://getcomposer.org/installer | php;

.check-installation: .check-composer
	@echo "Checking for vendor directory..."
	@test -d $(VENDORS) || make install
	@echo "Checking for bin directory..."
	@test -d $(BIN) || make install

build:
	make install

install: .check-composer
	@echo "Installing Composer packages..."
	$(COMPOSER) install --dev

	@echo "Removing unnecessary directories..."
	rm -rf $(VENDORS)/doctrine/common/bin/
	rm -rf $(VENDORS)/doctrine/common/tests/
	rm -rf $(VENDORS)/zircote/swagger-php/scripts/
	rm -rf $(VENDORS)/zircote/swagger-php/tests/

	@echo "Installing Node.js packages..."
	npm install

update: .check-installation
	@echo "Updating Composer packages..."
	$(COMPOSER) update --dev

clean:
	@echo "Uninstalling..."

	@echo "Removing Composer files and packages..."
	rm -f composer.phar
	rm -f composer.lock
	rm -rf $(VENDORS)

	@echo "Removing Node.js packages..."
	rm -rf $(NPM)