> ##### __Notice__
> The Vanilla API is still undergoing development and gets rewritten every so often. Do therefore __not__ use it for __anything__ just yet. You're more than welcome to take a look at the source though and any contribution would be greatly appreciated!

## Getting started

To get started using Vanilla API, either:

* __[Download the latest release](https://bitbucket.org/kasperisager/vanillaapi/get/master.zip)__
* Clone the repository directly into your Vanilla `applications` directory:
 
```sh
$ git clone git://github.com/kasperisager/VanillaAPI.git api
```

When you've done this, make sure the newly created folder is named `api` and not `VanillaAPI`. Now simply go to your dashboard, enable the API in the "Applications" menu and you're all set!

### Generating documentation

The application source is well-documented and the API comes bundled with [Sami](https://github.com/fabpot/Sami) for generating the documentation. You will however need to install Sami first after which you can generate the documentation:

```sh
$ composer install
$ php vendors/sami/sami/sami.php update config.php -v
```

If the Composer installation fails, if might be a good idea to [read the instructions](http://getcomposer.org/doc/00-intro.md#installation-nix) on how to use Composer. When you've got it all up and running, navigate to http://your-domain.com/applications/api/build to read the generated documentation.

## How does it work?

Vanilla API is in fact not an API in itself. A more fitting description would be that it's a mapper tool whose purpose is to implement a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. It also translates PUT and DELETE requests into POST requests so that Garden can understand and process these.

In the end, you can look at Vanilla API as being an application that sits of top of the default API, translating and handling different requests for use by the application and core controllers who in return carry out the actual methods.

## Configuration

The API can be configured through the dashboard using the "Application Interface" menu found in the "Forums Settings" section. Here you can see the main API endpoint and you can re-generate the application secret used for signature based authentication. Please be aware that there's no way for you to manually set the application secret in the dashboard - it enforces using a UUID v4 key which is a randomly generated, cryptographically secure string.

Should you wish to change to request expiration time, you can do this using the following configuration statement:

```php
<?php $Configuration['API']['Expiration'] = 5 * 60; // Defaults to 5 minutes
```

### [Authentication](http://code.webhutt.com/vanillaapi/wiki/Authentication#repo-content)

Vanilla API supports two different authentication methods: A semi-stateless session based method as well as a stateless signature based method. The two are completely compatible so you are free to chose between one or other or use them both for different aspects of the same application. Both methods are also highly secure so you won't necessarily need to perform them over HTTPS although it is highly recommended doing so.

__[Read more about authentication](http://code.webhutt.com/vanillaapi/wiki/Authentication#repo-content)__

### [Extending](http://code.webhutt.com/vanillaapi/wiki/Extending#repo-content)

Vanilla API allows you to easily integrate your own plugins and applications with the API Mapper - it's as simple as creating a new API class and putting it anywhere in your application or plugin where the Garden autoloader can find it. You can also write your own autoloader which is what I've done for loading the core API classes - this merely because I'm pretty nit-picky when it comes to my folder structure.

__[Read more about extending the API](http://code.webhutt.com/vanillaapi/wiki/Extending#repo-content)__

### [Issue tracking](http://code.webhutt.com/vanillaapi/issues)
If you come across any bugs or if you have a feature request, please file an issue using the Bitbucket Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using Bitbucket for inquires about bugs and feature requests. Thanks!

__[File a new issue or feature request](http://code.webhutt.com/vanillaapi/issues/new)__

### [Changelog](http://code.webhutt.com/vanillaapi/pull-requests)

I use pull-requests on Bitbucket to handle merging new code into the master branch so take a look at the closed pull-requests to see exact changes between each version.

__[Look through the closed pull-requests](http://code.webhutt.com/vanillaapi/pull-requests)__

---

Copyright 2013 © __[Kasper K. Isager](http://webhutt.com)__. Licensed under the terms of the __[MIT License](http://code.webhutt.com/vanillaapi/wiki/License#repo-content)__