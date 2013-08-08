# CakePHP CondenseTemplate

Provides RAD cake bake templates for application generation.
CondenseTemplate is based on the default templates.

## Requirements

The master branch has the following requirements:

* CakePHP 2.3.8 or greater.
* CakeDC Search plugin 2.2.0 or greater.
* PHP 5.3.0 or greater.
* jQuery 1.9.1 or greater.

## Installation

* Clone/Copy the files in this directory into `app/Plugin/CondenseTemplate`
* Ensure the plugin is loaded in `app/Config/bootstrap.php` like so:

```php
CakePlugin::load('CondenseTemplate');
```

First bake using the default templates, given Condense
needs a Model for finding the hasMany relations.
Then re-bake, but bake the condense templates.

Find the hasMany relations on any view done by
Ajax/JavaScript pagination in elements.
Second, per index view the Search plugin is baked into your result.
Third, text fields will be surperessed in indexes and
now lastly, one can surpress index fields,
get clickable urls and documents and shared filters in
your resulting application by creating and loading an
application specific Config/condense.php in bootstrap.php like so:

in bootstrap.php add:
```php
include APP . DS . 'Config' . DS . 'condense.php';
```

application specific Config/condense.php might be:

```php
<?php
Configure::write('meta', array(
	'Categorie' => array(
		'Id' => array('suppresIndex' => true),
	),
	'Feedback' => array(
		'Id' => array('suppresIndex' => true),
	),
	'Profiel' => array(
		'Id' => array('suppresIndex' => true),
		'Account' => array('suppresIndex' => true),
		'Bezoekadres' => array('suppresIndex' => true),
		'Telefoonnummer' => array('suppresIndex' => true),
		'Website' => array('type' => 'url'),
		'LinkedIn' => array('type' => 'url'),
		'Created' => array('suppresIndex' => true),
		'CV_in_PDF_of_Word_format' => array('type' => 'file'),
	),
	'Project' => array(
		'Id' => array('suppresIndex' => true),
		'Reacties' => array('suppresIndex' => true),
		'Bekeken' => array('suppresIndex' => true),
		'Project_titel' => array('or' => 'filter'),
		'Project_omschrijving' => array('or' => 'filter'),
	),
));
```

### Using

This should give you Rapid Appication Development (RAD) cake bake
template results that you can immediate use as the basis of your applications.
