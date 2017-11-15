# Network

[![Build Status](https://travis-ci.org/simonhamp/network.svg?branch=master)](https://travis-ci.org/simonhamp/network)
[![StyleCI](https://styleci.io/repos/109927594/shield?branch=master)](https://styleci.io/repos/109927594)

**Network** is a new kind of social network that lets you take back control of your content and your privacy.

This is heavily WIP and there's really not much to see yet, but if you want to have a play feel free.

## Installation

The simplest way to install Network right now is via the command line:

```bash
$ composer create-project simonhamp/network /path/to/install/to
```

After the initial package has downloaded, the install process should kick-off automatically. It's a guided CLI installation that should be pretty easy to follow.

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