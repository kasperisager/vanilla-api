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

```sh
$ composer install && php vendors/sami/sami/sami.php update config.php -v
```

If the Composer installation fails, if might be a good idea to [read the instructions](http://getcomposer.org/doc/00-intro.md#installation-nix) on how to use Composer. When you've got it all up and running, navigate to http://your-domain.com/applications/api/build to read the generated documentation.

## How does it work?

Vanilla API is in fact not an API in itself. A more fitting description would be that it's a mapper tool whose purpose is to implement a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. It also translates PUT and DELETE requests into POST requests so that Garden can understand and process these.

In the end, you can look at Vanilla API as being an application that sits of top of the default API, translating and handling different requests for use by the application and core controllers who in return carry out the actual methods.

## Configuration

The API can be configured through the dashboard using the "Application Interface" menu found in the "Forums Settings" section. Here you can see the main API endpoint, you can adjust the request expiration (5 minutes by default) and you can re-generate the application secret used for signature based authentication. Please be aware that there's no way for you to manually set the application secret - it enforces using a UUID v4 key which is a randomly generated, cryptographically secure string. This means that when re-generating the secret, there's no way for you to undo it so caution is advised.

## Authentication

Vanilla API support two different authentications methods: A semi-stateless session based method as well as a stateless signature based method. The two are completely compatible so you are free to chose between one or other or use them both for different aspects of the same application. Both methods are also highly secure so you won't necessarily need to perform them over HTTPS although it _is_ highly recommended doing so.

### Session based authentication

There's nothing too fancy about the session based authentication method from an API point of view: This method is in fact just the default Vanilla authentication. It is semi-stateless in the sense that when authenticating with the server, each client gets assigned a cookie which initiates a session. If a client accesses the API with a valid session, he or she can freely interface with Vanilla using the API, only restricted by their permissions.

Should you wish to use the session based authentication method, you'll need to use one of the many SSO solutions available for Vanilla to authenticate users from within your application. I have a custom SSO API class planned but it's not going to be ready anytime soon.

### Signature based authentication

The signature based authentication method is similar to that used by [Amazon Web Services](http://aws.amazon.com/articles/1928#HTTP). To make an API call, you'll need 3 things: A public key, a private key and a signature (also referred to as _token_). The public key can be either the username or the email of the user making the request whilst the private key is an application secret generated for you upon enabling Vanilla API __*__. The signature is an HMAC-SHA256 hash created by combining the public and the private key with any queries you want to pass along with your request and then signing it all with a Unix timestamp (UTC).

The request is then sent along to the server and the signature recreated using the information sent with the request. The server then compares its generated signature with the one from the request - if these match, then the client is considered legitimate and an authenticated session is started for the duration of the request.

#### Implementations

Coming soon...

#### Security precautions

It is highly recommended to include the HTTP method as well the request URI in your query, and thus in the hash generation as well, as this can help prevent man-in-the-middle attacks where an attacker could potentially modify the endpoint you are operating on as well as the HTTP method.

## Extending

Vanilla API allows you to easily integrate your own plugins and applications with the API Mapper - it's as simple as creating a new API class and putting it anywhere in your application or plugin where the Garden autoloader can find it. Once I've established the final architecture for v0.1.0, I'll describe the whole process in depth. Here's how it looks so far:

In `class.api_class_foo.php` placed in your application's or plugin's `library` directory:

```php
<?php if (!defined('APPLICATION')) exit();

/**
 * Foo API
 *
 * Description of Foo API
 *
 * @package    [name]
 * @since      [version]
 * @author     [author] <[email]>
 * @copyright  [description]
 * @license    [url] [description]
 */
class API_Class_Foo implements API_IMapper
{
   // API operations go here
}
```

More coming soon...

## Issue tracking

If you come across any bugs or if you have a feature request, please [file an issue](https://github.com/kasperisager/VanillaAPI/issues) using the Github Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using Github for inquires about bugs and feature requests. Thanks!

---
Copyright 2013 © [Kasper K. Isager](https://github.com/kasperisager). Licensed under the terms of the [MIT License](LICENSE.md)
