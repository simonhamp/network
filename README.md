# Network

[![Build Status](https://travis-ci.org/simonhamp/network.svg?branch=master)](https://travis-ci.org/simonhamp/network)
[![StyleCI](https://styleci.io/repos/109927594/shield?branch=master)](https://styleci.io/repos/109927594)

**Network** is a new kind of social network that lets you take back control of your content and your privacy. Read more on [the Wiki](https://github.com/simonhamp/network/wiki).

This is heavily WIP and there's really not much to see yet, but if you want to have a play feel free.

## Requirements

- PHP 7.1+
- [ZeroMQ](http://zeromq.org/)
- [ZeroMQ PHP extension](https://pecl.php.net/package/zmq)

If you don't have ZeroMQ or the PHP extension installed (if you're not sure, you probably don't), you can install them via your package manager and PECL, for example using Homebrew on a Mac:

```bash
$ brew install zmq
$ pecl install zmq-beta
```

To install ZeroMQ on other platforms, please check the [ZeroMQ docs](http://zeromq.org/intro:get-the-software).

## Installation

The simplest way to install Network right now is via the command line:

```bash
$ composer create-project simonhamp/network /path/to/install/to
```

---

**ALTERNATIVELY**: If you prefer to fork and clone this repo, you will need to install dependencies manually by running the following from the project's root:

```bash
$ composer install
```

---

After all of the dependencies have downloaded, you can kick off the setup process by running:

```bash
$ php artisan network:configure
```

It's a guided CLI installation so it should be pretty easy to follow.

## Updating

Updating is as simple as running:

```bash
$ composer update && php artisan migrate
```

This will pull in the latest version of [the main package](https://github.com/simonhamp/network-elements) and its dependencies and then run any new/outstanding migrations. This should all be pretty painless.

## Problems

Any feedback you can give about your experience on any platform would be great. Please feel free to [open an issue](https://github.com/simonhamp/network/issues).

## Acknowledgements

Network is proudly built on [Laravel](https://laravel.com/).