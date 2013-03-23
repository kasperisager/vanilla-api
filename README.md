[![Vanilla API](design/images/api.png)](https://github.com/kasperisager/VanillaAPI)

# Vanilla API [![Dependency Status](https://gemnasium.com/kasperisager/VanillaAPI.png)](https://gemnasium.com/kasperisager/VanillaAPI)

A super early version of a RESTful API for Vanilla that responds in JSON and XML

## Library dependencies

### Doctrine Common [![Build Status](https://travis-ci.org/doctrine/common.png)](https://travis-ci.org/doctrine/common)
The Doctrine Common project is a library that provides extensions to core PHP functionality.

### Swagger PHP [![Build Status](https://travis-ci.org/zircote/swagger-php.png)](https://travis-ci.org/zircote/swagger-php)
Swagger-PHP is a PHP library that servers as an annotations toolkit to produce [Swagger Doc](http://swagger.wordnik.com).
It makes extensive use of the [Doctine Common library](http://www.doctrine-project.org/projects/common.html) for
annotations support and caching.

## Getting started

To get started using Ninja, either:
- [Download the latest release](https://github.com/kasperisager/VanillaAPI/archive/master.zip)
- Clone the repository directly into your Vanilla `applications` directory:  
`$ cd /path/to/vanilla/applications/`  
`$ git clone git://github.com/kasperisager/VanillaAPI.git`

Now that you've downloaded Vanilla API, it's time to get it installed. Vanilla API comes with a Makefile that makes installation a breeze:

```sh
$ [sudo] make
```

## Makefile tasks

#### install - `make install`
Downloads Composer and installs the required Composer and NPM packages.

#### update - `make update`
Downloads and install new versions of the installed Composer packages.

#### clean - `make clean`
Removes all files and directories created by `make install` and `make update`.

---
Copyright © 2013 [Kasper K. Isager](https://github.com/kasperisager). Licensed under the terms of the [MIT License](LICENSE.md)
