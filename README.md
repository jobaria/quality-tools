# quality-tools
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/jobaria/quality-tools.svg?style=flat-square&include_prereleases)](https://packagist.org/packages/jobaria/quality-tools)
[![PHP Version](https://img.shields.io/badge/php-%5E8.0-8892BF.svg?style=flat-square)](http://www.php.net)

Set of quality tools for your application. Based on [GrumPHP](https://github.com/phpro/grumphp), including the following tools:

- [Composer Normalize](https://github.com/ergebnis/composer-normalize)
- [Codesniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- [PHPMD](https://github.com/phpmd/phpmd)
- [PHPStan](https://github.com/phpstan/phpstan)
- [Psalm](https://github.com/vimeo/psalm)

## Requirements
Required to install and run quality-tools:
- Composer (v2.0) (https://getcomposer.org)
- Phive (https://phar.io)

## Installation
Install via composer:
```
$ composer require --dev jobaria/quality-tools
```

When composer completes, run the following commands:
```shell script
# Install the necessary quality tools
$ phive install

# Enable GrumPHP and sniff your commits
$ tools/grumphp git:init
```

After install, edit configuration files (copied to your root folder) to your personal preferences.

## Usage
All tools will be run automatically on git-commit via GrumPHP.
