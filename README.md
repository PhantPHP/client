# Client

## Requirments

PHP >= 8.0


## Install

`composer require phant/client`


## Usages

### MySQL

```php
use Phant\Client\Service\MySQL as ClientMySQL;

$clientMySQL = new ClientMySQL(
	'127.0.0.1',
	'my_db',
	'my-user',
	'my-pass',
	'3306',
	'utf8mb4'
);

$clientMySQL->execute(
	'
	INSERT INTO `my_table`
	SET	`col_1`	= :val_col_1
	,	`col_2`	= :val_col_2
	',
	[
		':val_col_1' => 'foo',
		':val_col_2' => 'bar',
	]
);

$list = $clientMySQL->getList(
	'
	SELECT *
	FROM `my_table`
	WHERE	`col_1`	= :val_col_1
	,		`col_2`	= :val_col_2
	',
	[
		':val_col_1' => 'foo',
		':val_col_2' => 'bar',
	]
);

$list = $clientMySQL->getListByColumnValue(
	'my_table',
	'col_1',
	'foo'
);

$item = $clientMySQL->get(
	'
	SELECT *
	FROM `my_table`
	WHERE	`col_1`	= :val_col_1
	,		`col_2`	= :val_col_2
	',
	[
		':val_col_1' => 'foo',
		':val_col_2' => 'bar',
	]
);

$item = $clientMySQL->getByColumnValue(
	'my_table',
	'col_1',
	'foo'
);

$exist = $clientMySQL->get(
	'
	SELECT *
	FROM `my_table`
	WHERE	`col_1`	= :val_col_1
	,		`col_2`	= :val_col_2
	',
	[
		':val_col_1' => 'foo',
		':val_col_2' => 'bar',
	]
);

$exist = $clientMySQL->existByColumnValue(
	'my_table',
	'col_1',
	'foo'
);
```

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
