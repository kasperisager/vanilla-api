[![Vanilla API](http://i.imgur.com/6Qawex7.png)](https://github.com/kasperisager/VanillaAPI)

# Vanilla API [![Build Status](https://travis-ci.org/kasperisager/VanillaAPI.png?branch=master)](https://travis-ci.org/kasperisager/VanillaAPI) [![Dependency Status](https://gemnasium.com/kasperisager/VanillaAPI.png)](https://gemnasium.com/kasperisager/VanillaAPI)

A super early version of a RESTful API for Vanilla that responds in JSON and XML.

> #### Notice
> The Vanilla API is still undergoing heavy development and gets rewritten every so often. Do therefore __not__ use it for __anything__ just yet. You're more than welcome to take a look at the source though and any contribution would be greatly appreciated!

## Getting started

To get started using Vanilla API, either:
- [Download the latest release](https://github.com/kasperisager/VanillaAPI/archive/master.zip)
- Clone the repository directly into your Vanilla `applications` directory:  
`$ cd /path/to/vanilla/applications/`  
`$ git clone git://github.com/kasperisager/VanillaAPI.git`

When you've done this, make sure the newly created folder is named `api` and not `VanillaAPI`. Now simply go to your dashboard, enable the API in the "Applications" menu and you're all set!

### Generating documentation

The application source is well-documented and the API comes bundled with [Sami](https://github.com/fabpot/Sami) for generating the documentation. You will however need to install Sami first after which you can generate the documentation:

```shell
$ composer install && php vendors/sami/sami/sami.php update config.php -v
```

If the Composer installation fails, if might be a good idea to [read the instructions](http://getcomposer.org/doc/00-intro.md#installation-nix) on how to use Composer. When you've got it all up and running, navigate to http://your-domain.com/applications/api/build to read the generated documentation.

## How does it work?

Vanilla API is in fact not an API in itself. A more fitting description would be that it's a mapper tool whose purpose is to implement a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. It also translates PUT and DELETE requests into POST requests so that Garden can understand and process these.

In the end, you can look at Vanilla API as being an application that sits of top of the default API, translating and handling different requests for use by the application and core controllers who in return carry out the actual methods.

## Issue tracking

If you come across any bugs or if you have a feature request, please [file an issue](https://github.com/kasperisager/VanillaAPI/issues) using the Github Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using Github for inquires about bugs and feature requests. Thanks!

---
Copyright 2013 © [Kasper K. Isager](https://github.com/kasperisager). Licensed under the terms of the [MIT License](LICENSE.md)
