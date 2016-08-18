# CakePHP 3.x - Markdown Documents

## Installation

### Composer {#composer}

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require ivanamat/cakephp3-documents
```

### Git submodule {#gitsubmodule}

```
    git submodule add git@github.com:ivanamat/cakephp3-documents.git plugins/Documents
    git submodule init
    git submodule update
```

### Manual installation

Download the .zip or .tar.gz file, unzip and rename the plugin folder "cakephp3-documents" to "Documents" then copy the folder to your plugins folder.

## Configuration

### Load plugin

```php
    Plugin::load('Documents', ['bootstrap' => false, 'routes' => true]);
```

### Database

Import documents.sql `config/cheme/documents.sql` or execute the SQL commands.

```sql
    # documents.sql

    DROP TABLE IF EXISTS `categories`;
    CREATE TABLE `categories` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `parent_id` int(11) DEFAULT NULL,
      `lft` int(11) DEFAULT NULL,
      `rght` int(11) DEFAULT NULL,
      `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `public` tinyint(1) NOT NULL DEFAULT '0',
      `created` datetime NOT NULL,
      `modified` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    DROP TABLE IF EXISTS `documents`;
    CREATE TABLE `documents` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `category_id` int(11) DEFAULT NULL,
      `user_id` int(11) NOT NULL,
      `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `body` text COLLATE utf8_unicode_ci,
      `created` datetime DEFAULT NULL,
      `modified` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```

### Bootstrap

Set in your bootstrap file `src/config/bootstrap.php`.

Specify your homepage's URL.
```php
    Configure::write('Documents.home', ['plugin' => false, 'controller' => 'Pages', 'action' => 'display', 'home']);
```

Create your own INDEX.md and specify the path.
```php
    Configure::write('Documents.index', '../INDEX.md');
```

If the ACL plugin is loaded, you can set action's permissions.
```php
    /**
     * Allow CategoriesController actions
     * @actions: index, edit
     */
    Configure::write('Categories.auth.allow', ['index','edit']);

    /**
     * Allow DocumentsController actions
     * @actions: index, view, add, edit, delete
     **/
    Configure::write('Documents.auth.allow', ['index','view']);
```

## Component and Helper

Documents has a component named DocsComponent and a helper named DocsHelper, both with the same methods.

## Methods

### slugCategory($id)

**$id** int Category id

**return** string Returns category slug

```php
    $slug = $this->Docs->slugCategory($id);
    # Example $slug value: 'proyectos/cakephp/plugins'
    echo '<a href="/' . $this->plugin . DS . $slug . '"><strong>'.$category->title.'</strong></a>';
```
     
### slugDocument($id)

**$id** int

**return** string Returns document slug

```php
    $slug = $this->Docs->slugDocument($id);
    # Example $slug value: 'proyectos/cakephp/plugins/cakephp-3-x-markdown-documents'
    echo '<a href="/' . $this->plugin . DS . $slug . '"><strong>'.$document->title.'</strong></a>';
```
     
### getCategory($id)

**$id** int Category id

**return** object Category

```php
    $category = $this->Docs->getCategory($id);
    echo '<h2>'.$category->title.'</h2>';
```
### getParentSlug($slug)

**$slug** string Slug

**return** string Parent slug

```php
    # $slug = 'projects/cakephp/plugins/cakephp-3-x-markdown-documents'
    $parentSlug = $this->Docs->getParentSlug($slug);
    # $parentSlug will output 'projects/cakephp/plugins'
    echo '<a href="/' . $this->plugin . DS . $parentSlug . '"><strong>Parent category</strong></a>';
```

### getRelatedDocuments($id)

**$id** int Category id

**return** array Array of documents from categories children of the specified category

```php
    $relatedDocuments = $this->Docs->getParentSlug($slug);
    foreach($relatedDocuments as $document) {
        echo '<h4>' . $document->title . '</h4>';
        echo $this->Markdown->parse($document->body);
    }
```

     
## About CakePHP 3.x - Markdown Documents

CakePHP 3.x - Markdown Documents require [CakePHP 3.x - Markdown](https://github.com/ivanamat/cakephp3-markdown) plugin.

### Friendly URLs!

The **URLs** generated are all **friendly**.

Example: http://www.example.com/Documents/tutorials/cakephp/plugins/cakephp-3-x-documents

## Author

Iván Amat on [GitHub](https://github.com/ivanamat)  
[www.ivanamat.es](http://www.ivanamat.es/)
