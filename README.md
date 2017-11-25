# Introduction
This is a very simple to use OAuth 2.0 client. It has minimal dependencies.

**NOTE**: if you are not bound to PHP 5.4, you are probably better of using the 
OAuth 2.0 client of the League of Extraordinary Packages! It can be found 
[here](http://oauth2-client.thephpleague.com/).

# Features

* Simplicity
* Works with PHP >= 5.4
* Minimal dependencies;
* Supports OAuth refresh tokens.
* Easy integration with your own application and/or framework;
* Does not enforce a framework on you;
* Only "authorization code" profile support, will not implement anything else;
* Only conforming OAuth 2.0 servers will work, this library will not get out of 
  its way to deal with services that violate the OAuth 2.0 RFC;
* Supports Proof Key for Code Exchange for public clients where no secret is
  used;
* There will be no toggles to shoot yourself in the foot;
* Uses `paragonie/constant_time_encoding` for constant time encoding;
* Uses `paragonie/random_compat` polyfill for CSPRNG;
* Uses `symfony/polyfill-php56` polyfill for `hash_equals`;

You **MUST** configure PHP in such a way that it enforces secure cookies! 
See 
[this](https://paragonie.com/blog/2015/04/fast-track-safe-and-secure-php-sessions) 
resource for more information.

# API

The API is very simple to use. See the `example/` folder for a working example!

# Security

As always, make sure you understand what you are doing! Some resources:

* [The Fast Track to Safe and Secure PHP Sessions](https://paragonie.com/blog/2015/04/fast-track-safe-and-secure-php-sessions)
* [The OAuth 2.0 Authorization Framework](https://tools.ietf.org/html/rfc6749)
* [The OAuth 2.0 Authorization Framework: Bearer Token Usage](https://tools.ietf.org/html/rfc6750)
* [OAuth 2.0 Threat Model and Security Considerations](https://tools.ietf.org/html/rfc6819)
* [securityheaders.io](https://securityheaders.io/)
* [Proof Key for Code Exchange by OAuth Public Clients](https://tools.ietf.org/html/rfc7636)

# License

[MIT](LICENSE).
