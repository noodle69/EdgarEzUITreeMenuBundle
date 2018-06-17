# EdgarEzUITreeMenuBundle

## Installation

### Get the bundle using composer

Add EdgarEzUITreeMenuBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/ez-uitreemenu-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Edgar\EzUITreeMenuBundle\EdgarEzUITreeMenuBundle(),
        // ...
    );
}
```

## Assets/Assetic

```bash
php bin/console assets:install --symlink web
php bin/console assetic:dump --env=prod
```

## Add routing

Add to your global configuration app/config/routing.yml

```yaml
edgar.ezuitreemenu:
    resource: '@EdgarEzUITreeMenuBundle/Resources/config/routing.yml'
    prefix: /_treemenu
    defaults:
        siteaccess_group_whitelist: 'admin_group'
```
