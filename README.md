# Heap Helper
[![GitHub issues](https://img.shields.io/github/issues/mitquinn/HeapHelper)](https://github.com/mitquinn/HeapHelper/issues)
[![GitHub stars](https://img.shields.io/github/stars/mitquinn/HeapHelper)](https://github.com/mitquinn/HeapHelper/stargazers)
[![GitHub license](https://img.shields.io/github/license/mitquinn/HeapHelper)](https://github.com/mitquinn/HeapHelper/blob/master/LICENSE.md)

## Description
Heap Helper is a simple wrapper for the Heap server side API.

Heap Helper provides:
* simple integration with the Heap server side API.
* wrapper for the Heap API endpoints. 
* individual resources that the API is expecting to be provided.

You can learn more about Heap here: https://heapanalytics.com/

```php
$configuration = new \Mitquinn\HeapHelper\HeapConfiguration('apiKey', 'appId');
$heapHelper = new \Mitquinn\HeapHelper\HeapHelper($configuration);

$event = new \Mitquinn\HeapHelper\Resources\HeapEvent(
    'eventName', 
    'alice@example.com', 
    ['propertyKey' => 'propertyValue']
);

$heapResponse = $heapHelper->track($event);
```

## Installation
The recommended way to install Heap Helper is through [Composer.](https://getcomposer.org/)
```
composer require mitquinn/heap-helper
```

## License
Heap Helper is made available under the GNU General Public License (GNU). Please see the [License File](https://github.com/mitquinn/HeapHelper/blob/master/LICENSE.md) for more information.

## Contributors

* Sponsor - Mitchell Quinn - [<mitchell.david.quinn@gmail.com>](mailto:mitchell.david.quinn@gmail.com)




