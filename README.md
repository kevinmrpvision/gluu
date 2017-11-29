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


# License

[MIT](LICENSE).
