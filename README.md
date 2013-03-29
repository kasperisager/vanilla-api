[![Vanilla API](design/images/api.png)](https://github.com/kasperisager/VanillaAPI)

# Vanilla API [![Dependency Status](https://gemnasium.com/kasperisager/VanillaAPI.png)](https://gemnasium.com/kasperisager/VanillaAPI)

A super early version of a RESTful API for Vanilla that responds in JSON and XML.

> #### Notice
> The Vanilla API is still undergoing heavy development and gets rewritten every so often. Do therefore __not__ use it for __anything__ just yet. You're more than welcome to take a look at the source though and any contribution would be greatly appreciated!

## Library dependencies

### [Doctrine Common](https://github.com/doctrine/common) [![Build Status](https://travis-ci.org/doctrine/common.png?branch=2.3)](https://travis-ci.org/doctrine/common)
The Doctrine Common project is a library that provides extensions to core PHP functionality.

### [Swagger PHP](https://github.com/zircote/swagger-php) [![Build Status](https://travis-ci.org/zircote/swagger-php.png)](https://travis-ci.org/zircote/swagger-php)
Swagger-PHP is a PHP library that servers as an annotations toolkit to produce [Swagger Doc](http://swagger.wordnik.com).
It makes extensive use of the [Doctrine Common library](http://www.doctrine-project.org/projects/common.html) for
annotations support and caching.

## Getting started

To get started using Ninja, either:
- [Download the latest release](https://github.com/kasperisager/VanillaAPI/archive/master.zip)
- Clone the repository directly into your Vanilla `applications` directory:  
`$ cd /path/to/vanilla/applications/`  
`$ git clone git://github.com/kasperisager/VanillaAPI.git`

Now that you've downloaded Vanilla API, it's time to get it installed. Vanilla API comes with a Makefile that makes installation a breeze:

```sh
$ [sudo] make install
```

Now simply go to your Vanilla dashboard, enable Vanilla API in the "Applications" menu and navigate to the API Explorer for all your documentation needs: http://your-domain.com/api

## How does it work?

Vanilla API is in fact not an API in itself. A more fitting description would be that it's a mapper tool whose purpose is to implement a RESTlike URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. It also translates PUT and DELETE requests into POST requests so that Garden can understand and process these.

In the end, you can look at Vanilla API as being an application that sits of top of the default API, translating and handling different requests for use by the application and core controllers who in return carry out the actual methods.

## Makefile tasks

Vanilla API comes with a couple of handy makefile task for installing, updating and unstalling dependencies. Some of these may require that you run them as the root user so be prepared to `sudo` if things go fishy.

#### install - `make install`
Downloads Composer and installs the required Composer and NPM packages.

#### update - `make update`
Downloads and install new versions of the installed Composer packages.

#### clean - `make clean`
Removes all files and directories created by `make install` and `make update`.

> #### Notice
> Please be aware that manually installing the dependencies using `npm install` and `composer install` will result in errors upon running the application. This is due to the fact that the third party libraries used contains tests and binaries that are currently conflicting with Garden. The makefile takes care of removing these, leaving only the actual libraries for use by Vanilla API.

## Issue tracking

If you come across any bugs or if you have a feature request, please [file an issue](https://github.com/kasperisager/VanillaAPI/issues) using the Github Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using Github for inquires about bugs and feature requests. Thanks!

---
Copyright © 2013 [Kasper K. Isager](https://github.com/kasperisager). Licensed under the terms of the [MIT License](LICENSE.md)
