COMPOSER 	= php composer.phar
VENDORS 	= vendors
BIN 		= $(VENDORS)/bin

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

install: clean .check-composer
	@echo "Install Composer dependencies..."
	$(COMPOSER) install --dev

	@echo "Removing unnecessary directories..."
	rm -rf $(VENDORS)/doctrine/common/bin/
	rm -rf $(VENDORS)/doctrine/common/tests/
	rm -rf $(VENDORS)/zircote/swagger-php/scripts/
	rm -rf $(VENDORS)/zircote/swagger-php/tests/

update: .check-installation
	@echo "Update Composer dependencies..."
	$(COMPOSER) update --dev

clean:
	@echo "Removing Composer..."
	rm -f composer.phar
	rm -f composer.lock
	rm -rf $(VENDORS)