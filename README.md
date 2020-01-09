Revisionable with Force Delete
=====================

This package is an improvement on another package - [Revisionable](https://github.com/VentureCraft/revisionable/pull/364/files). You must install it before installing this package.

This package adds support for saving Force deleted models.

Install
-----------------------------------

Run:
```php
    composer require "adiafora/revisionable-force-delete"
```

Usage
-----------------------------------
Just add a trait `RevisionableForceDeleteTrait` to the model whose revision you want to save.

```php
   use Adiafora\RevisionableForceDelete\RevisionableForceDeleteTrait;
```

### Storing Force Delete
By default the Force Delete of a model is not stored as a revision.

If you want to store the Force Delete as a revision you can override this behavior by setting `revisionForceDeleteEnabled ` to `true` by adding the following to your model:
```php
protected $revisionForceDeleteEnabled = true;
```

In which case, the `created_at` field will be stored as a key with the `oldValue()` value equal to the model creation date and the `newValue()` value equal to `null`.

**Attention!** Turn on this setting carefully! Since the model saved in the revision, now does not exist, so you will not be able to get its object or its relations. 
