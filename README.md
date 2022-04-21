# Client

## Requirments

PHP >= 8.0


## Install

`composer require phant/client`


## Usages

### S3

```php
use Phant\Client\S3 as ClientS3;

$clientS3 = new ClientS3(
	$region,
	$endpoint,
	$accessKey,
	$secretKey,
	$bucket
);
$clientS3->set('foo', 'bar');
$bar = $clientS3->get('foo');
$clientS3->delete('foo');
```
