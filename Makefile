build:
	make install
	make clean

install:
	sudo npm install
	composer install

clean:
	rm -rf vendors/doctrine/common/bin/
	rm -rf vendors/doctrine/common/tests/
	rm -rf vendors/zircote/swagger-php/scripts/
	rm -rf vendors/zircote/swagger-php/tests/
