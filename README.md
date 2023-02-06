Yii2-gii extension
=======================
The Gii extension for the Yii framework,This extension provides a Web-based code generator, called Gii, for [Yii framework 2.0] applications.
You can use Gii to quickly generate models, forms, modules, CRUD, etc.

[![Latest Stable Version](http://poser.pugx.org/hjp1011/yii2-gii/v)](https://packagist.org/packages/hjp1011/yii2-gii) [![Total Downloads](http://poser.pugx.org/hjp1011/yii2-gii/downloads)](https://packagist.org/packages/hjp1011/yii2-gii) [![Latest Unstable Version](http://poser.pugx.org/hjp1011/yii2-gii/v/unstable)](https://packagist.org/packages/hjp1011/yii2-gii) [![License](http://poser.pugx.org/hjp1011/yii2-gii/license)](https://packagist.org/packages/hjp1011/yii2-gii) [![PHP Version Require](http://poser.pugx.org/hjp1011/yii2-gii/require/php)](https://packagist.org/packages/hjp1011/yii2-gii)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --dev --prefer-dist hjp1011/yii2-gii
```

or add

```
"hjp1011/yii2-gii": "~2.1.0"
```

to the require-dev section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => [
            'class' => 'yiiframe\gii\Module',
        ],
        // ...
    ],
    // ...
];
```

You can then access Gii through the following URL:

```
http://localhost/path/to/index.php?r=gii
```

or if you have enabled pretty URLs, you may use the following URL:

```
http://localhost/path/to/index.php/gii
```

Using the same configuration for your console application, you will also be able to access Gii via
command line as follows,

```
# change path to your application's base path
cd path/to/AppBasePath

# show help information about Gii
yii help gii

# show help information about the model generator in Gii
yii help gii/model

# generate City model from city table
yii gii/model --tableName=city --modelClass=City
```
