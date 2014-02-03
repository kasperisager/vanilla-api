![](icon.png)

# Vanilla API

[![GitHub version](https://badge.fury.io/gh/kasperisager%2Fvanilla-api.png)](http://badge.fury.io/gh/kasperisager%2Fvanilla-api)

Extensible RESTful API shim for Vanilla that operates in JSON or XML. Supports [JSONP](http://en.wikipedia.org/wiki/JSONP) and [CORS](http://en.wikipedia.org/wiki/Cross-origin_resource_sharing)


## Getting started

To get started using Vanilla API, either:

- [__Download the latest release__](https://github.com/kasperisager/vanilla-api/releases)
- Clone the repository directly into your Vanilla `applications` directory:

```sh
$ git clone kasperisager/vanilla-api api
```

When you've done this, make sure the newly created folder is named `api` and not `vanilla-api`. Now simply go to your dashboard, enable Vanilla API in the "Applications" menu and you're all set!


## How does it work?

Vanilla API is a shim that implements a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. The API then translates and redirects your request to the corrosponding application controller which in return caries out the requested method.


## Documentation

You can find documentation on how to use, configure and extend Vanilla API in the [Wiki](https://github.com/kasperisager/vanilla-api/wiki).

A [Postman](http://getpostman.com) Collection containing all the available endpoints as well as the arguments they take (required + some optional) can be found here: [__Vanilla API Collection__](https://www.getpostman.com/collections/88c667ea752bc4f0186e). _Make sure to update it every now and then!_


## [Issue tracking](https://github.com/kasperisager/vanilla-api/issues)

If you come across any bugs or if you have a feature request, please file an issue using the GitHub Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using GitHub for inquiries about bugs and feature requests. Thanks!

[__File a new issue or feature request__](https://github.com/kasperisager/vanilla-api/issues/new)

---

Copyright 2014 © [Kasper Kronborg Isager](http://kasperisager.github.io). Licensed under the terms of the [MIT License](LICENSE.md)
