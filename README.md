<p align="center">
  <img src="doc/cospirit-connect.png">
</p>

# HAL Navigator [![CircleCI](https://circleci.com/gh/cospirit/hal-navigator.svg?style=shield&circle-token=83d86dff77250ed8812fe50f0df7ad7085e14261)](https://circleci.com/gh/cospirit/hal-navigator)

Simple client to navigate through an HAL response

## Development

### Requirements

Install Docker as described in the [_Docker_](https://app.gitbook.com/@cospirit-connect/s/guide-de-demarrage/installation-des-projets/prerequis/docker) section of the Start Guide.

### Installation

Check the [Start guide](https://app.gitbook.com/@cospirit-connect/s/guide-de-demarrage/) of the documentation for base initialization.

#### Initialize project

```bash
    make development@install
```

### Usage (with Docker)

Install the application :
```bash
    make development@install
```

Restart the docker compose service :
```bash
    make development@restart
```

Remove and clean docker containers :
```bash
    make development@down
```

## Tests

```bash
    make test@coke
```
```bash
    make test@phpunit
```

## Basic usage

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
