# Notes

The backend is a REST API secured by OAuth2 (Resource Owner Password Credentials Grant) based on [Symfony2](http://symfony.com)
and the frontend is a [Marionette](http://marionettejs.com) MVC application in CoffeeScript.

## Installation

### Requirements

- [Composer](https://getcomposer.org/download)
- [NPM](https://www.npmjs.org)
- [Bower](http://bower.io)
- [Grunt](http://gruntjs.com)

### Steps

```bash
$ git clone https://github.com/florianv/notes
$ composer update
$ php app/console doctrine:database:create --env=prod
$ php app/console doctrine:schema:update --force --env=prod
$ php app/console doctrine:fixtures:load --env=prod
$ npm install
$ bower install
$ grunt
```

## Running tests

Requires the `pdo_sqlite` extension.

```bash
$ phpunit -c app/
```

## License

[MIT](https://github.com/florianv/notes/blob/master/LICENSE)
