> ##### __Notice__
> The Vanilla API is still undergoing development and gets rewritten every so often. Do therefore __not__ use it for __anything__ just yet. You're more than welcome to take a look at the source though and any contribution would be greatly appreciated!

## Getting started

To get started using Vanilla API, either:

* __[Download the latest release](https://github.com/kasperisager/vanilla-api/archive/master.zip)__
* Clone the repository directly into your Vanilla `applications` directory:

```sh
$ git clone git://github.com/kasperisager/vanilla-api.git api
```

When you've done this, make sure the newly created folder is named `api` and not `vanilla-api`. Now simply go to your dashboard, enable Vanilla API in the "Applications" menu and you're all set!

## How does it work?

Vanilla API is in fact not an API in itself. A more fitting description would be that it's a mapper tool whose purpose is to implement a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. It also translates PUT and DELETE requests into POST requests so that Garden can understand and process these.

In the end, you can look at Vanilla API as being an application that sits of top of the default API, translating and handling different requests for use by the application and core controllers who in return carry out the actual methods.

## Configuration

The API can be configured through the dashboard using the "Application Interface" menu found in the "Forums Settings" section. Here you can see the main API endpoint and you can re-generate the application secret used for signature based authentication. Please be aware that there's no way for you to manually set the application secret in the dashboard - it enforces using a UUID v4 key which is a randomly generated, cryptographically secure string.

Should you wish to change to request expiration time, you can do this using the following configuration statement:

```php
<?php $Configuration['API']['Expiration'] = 5 * 60; // Defaults to 5 minutes
```

### [Authentication](https://github.com/kasperisager/vanilla-api/wiki/Authentication)

Vanilla API supports two different authentication methods: A semi-stateless session based method as well as a stateless signature based method. The two are completely compatible so you are free to chose between one or other or use them both for different aspects of the same application. Both methods are also highly secure so you won't necessarily need to perform them over HTTPS although it is highly recommended doing so.

[__Read more about authentication__](https://github.com/kasperisager/vanilla-api/wiki/Authentication)

### [Extending](https://github.com/kasperisager/vanilla-api/wiki/Extending)

Vanilla API allows you to easily integrate your own plugins and applications with the API Mapper - it's as simple as creating a new API class and putting it anywhere in your application or plugin where the Garden autoloader can find it. You can also write your own autoloader which is what I've done for loading the core API classes - this merely because I'm pretty nit-picky when it comes to my folder structure.

[__Read more about extending the API__](https://github.com/kasperisager/vanilla-api/wiki/Extending)

### [Issue tracking](https://github.com/kasperisager/vanilla-api/issues)
If you come across any bugs or if you have a feature request, please file an issue using the Github Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using Github for inquires about bugs and feature requests. Thanks!

[__File a new issue or feature request__](https://github.com/kasperisager/vanilla-api/issues/new)

---

Copyright 2013 © [__Kasper K. Isager__](http://webhutt.com). Licensed under the terms of the [__MIT License__](LICENSE.md)
