HAL Navigator
=============

**WIP**

Simple client to navigate through an HAL response

![](http://mondesfrancophones.com/wp-content/uploads/2012/02/Cousteau-2.jpg)

Installation
------------

Basic usage
-----------

```php
<?php

use CoSpirit\HAL\Navigator;

$halContent = <<<EOF
{
    "_links": {
        "self": { "href": "https://hipsters-db.com/john-doe" }
    },
    "firstname": "John",
    "lastname": "Doe",
    "_embedded": {
        "feature": {
            "_links": {
                "self": { "href": "https://hipsters-db.com/john-doe/features" }
            },
            "beard": true,
            "fixie": false,
            "tile_shirt": true,
            "vegan": false,
            "level": 5
        },
        "bikes": [
            {
                "_links": {
                    "self": { "href": "https://hipsters-db.com/john-doe/bikes/1" }
                },
                "brand": "Fix cycles",
                "swag": 10
            },
            {
                "_links": {
                    "self": { "href": "https://hipsters-db.com/john-doe/bikes/2" }
                },
                "brand": "Prestige",
                "swag": 43
            }
        ]
    }
}
EOF;

$nav = new Navigator(json_decode($halContent));

$nav->rels->self; // https://hipsters-db.com/john-doe
$nav->firstname; // John
$nav->feature->rels->self; // https://hipsters-db.com/john-doe/feature
$nav->feature->beard; // true

// Accessing a collection

foreach ($nav->bikes as $bike) {
    $bike->rels->self; // https://hipsters-db.com/john-doe/bikes/1
    $bike->swag; // 10
}

$nav->bikes->first()->brand; // Fix cycles
$nav->bikes[0]->brand; // Fix cycles
$nav->bikes->last()->brand; // Prestige
$nav->bikes[1]->brand; // Prestige

count($nav->bikes); // 2
```
