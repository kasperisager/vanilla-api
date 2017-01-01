[![Vanilla API](https://rawgithub.com/kasperisager/vanilla-api/master/icon.svg)](https://github.com/kasperisager/vanilla-api)

# Vanilla API

> Extensible RESTful API shim for Vanilla that operates in JSON or XML. Supports [JSONP](http://en.wikipedia.org/wiki/JSONP) and [CORS](http://en.wikipedia.org/wiki/Cross-origin_resource_sharing).

## Getting started

To get started using Vanilla API, either:

- [__Download the latest release__](https://github.com/kasperisager/vanilla-api/releases/latest)
- Clone the repository directly into your Vanilla `applications` directory:

```console
$ git clone kasperisager/vanilla-api api
```

When you've done this, make sure the newly created folder is named `api` and not `vanilla-api`. Now simply go to your dashboard, enable Vanilla API in the "Applications" menu and you're all set!

## How does it work?

Vanilla API is a shim that implements a RESTful URI scheme upon with you can invoke different methods using the standard GET, POST, PUT and DELETE HTTP verbs. The API then translates and redirects your request to the corresponding application controller which in return caries out the requested method.

## Documentation

You can find documentation on how to use, configure and extend Vanilla API in the [Wiki](https://github.com/kasperisager/vanilla-api/wiki).

## License

Copyright &copy; 2013-2017 [Kasper Kronborg Isager](http://kasperisager.github.io). Licensed under the terms of the [MIT License](LICENSE.md)
