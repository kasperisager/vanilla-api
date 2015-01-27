[![Vanilla API](https://rawgithub.com/kasperisager/vanilla-api/master/icon.svg)](https://github.com/kasperisager/vanilla-api)

# Vanilla API

[![Release](http://img.shields.io/github/release/kasperisager/vanilla-api.svg?style=flat)](https://github.com/kasperisager/vanilla-api/releases) [![Code Climate](http://img.shields.io/codeclimate/github/kasperisager/vanilla-api.svg?style=flat)](https://codeclimate.com/github/kasperisager/vanilla-api)

Extensible RESTful API shim for Vanilla that operates in JSON or XML. Supports [JSONP](http://en.wikipedia.org/wiki/JSONP) and [CORS](http://en.wikipedia.org/wiki/Cross-origin_resource_sharing)

## Getting started

To get started using Vanilla API, either:

- [__Download the latest release__](https://github.com/kasperisager/vanilla-api/releases/latest)
- Clone the repository directly into your Vanilla `applications` directory:

```sh
$ git clone kasperisager/vanilla-api api
```

When you've done this, make sure the newly created folder is named `api` and not `vanilla-api`. Now simply go to your dashboard, enable Vanilla API in the "Applications" menu and you're all set!

## How does it work?

Vanilla API is a shim that implements a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. The API then translates and redirects your request to the corresponding application controller which in return caries out the requested method.

## Documentation

You can find documentation on how to use, configure and extend Vanilla API in the [Wiki](https://github.com/kasperisager/vanilla-api/wiki).

## [Issue tracking](https://github.com/kasperisager/vanilla-api/issues)

If you come across any bugs or if you have a feature request, please file an issue using the GitHub Issue tracker. Vanilla API won't be supported through http://vanillaforums.org so please stick to using GitHub for inquiries about bugs and feature requests. Thanks!

[__File a new issue or feature request__](https://github.com/kasperisager/vanilla-api/issues/new)

---

Copyright &copy; 2013-2015 [Kasper Kronborg Isager](http://kasperisager.github.io). Licensed under the terms of the [MIT License](LICENSE.md)
