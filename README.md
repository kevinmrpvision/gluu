# Introduction
This is a very simple to use OAuth 2.0 client to connect your Gluu Server. It has minimal dependencies.

**NOTE**: You need to prepare your Gluu server before this package follow [this](https://www.gluu.org/docs/ce/user-management/scim2) for that. After this you need to insatll this package using composer.

# Installation

**Composer**

Run the following to include this via Composer

```shell
composer require mrpvision/gluu
```

# Features

* Simplicity
* Works with PHP >= 5.6
* Minimal dependencies;
* Easy integration with your own application and/or framework;
* Does not enforce a framework on you;
* Support List, Create and Update Gluu Users.
* Support List, Create and Update Gluu Groups.

You **MUST** configure PHP in such a way that it enforces secure cookies! 
See 
[this](https://paragonie.com/blog/2015/04/fast-track-safe-and-secure-php-sessions) 
resource for more information.

# API

The API is very simple to use. See the `example/` folder for a working example!

# API protection

It's clear this API must not be anonymously accessed, however the SCIM standard does not define a specific mechanism to prevent unauthorized requests to endpoints. In this regard there are just a few guidelines in section 2 of RFC 7644 concerned with authentication and authorization.

**Gluu Server** CE allows you to protect your endpoints with UMA (a profile of OAuth 2.0). This is a safe and standardized approach for controling access to web resources. For SCIM protection, we strongly recommend its usage.

Alternatively, for testing purposes (as well as learning) you can temporarily enable the test mode. In this "mode" most complexity is taken out of the way so it serves as a quick and easy way to start interacting with your service.

In the next section, we will work using test mode. The topic of UMA will be explored later on.

# License

[MIT](LICENSE).
