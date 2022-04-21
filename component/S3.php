<?php
declare(strict_types=1);

namespace Phant\Client;

use Aws\S3\S3Client;

use Phant\Error\NotFound;

class S3
{
	protected S3Client $S3Client;
	
	public function __construct(string $region, string $endpoint, string $accessKey, string $secretKey)
	{
		$this->S3Client = new S3Client([
			'region' => $region,
			'version' => '2006-03-01',
			'endpoint' => $$endpoint,
			'credentials' => [
				'key'		=> $accessKey,
				'secret'	=> $secretKey,
			],
			'use_path_style_endpoint' => true
		]);
	}
	
	public function set(string $bucket, string $key, string $body)
	{
		$this->S3Client->putObject([
			'Bucket'	=> $bucket,
			'Key'		=> $key,
			'Body'		=> $body,
		]);
	}
	
	public function get(string $bucket, string $key): string
	{
		try {
			$object = $this->S3Client->getObject([
				'Bucket'	=> $bucket,
				'Key'		=> $key,
			]);
			
			return $object['Body']->getContents();
		} catch (\Exception $e) {
			throw new NotFound('Object : ' . $key);
		}
	}
	
	public function delete(string $bucket, string $key)
	{
		try {
			$this->S3Client->deleteObject([
				'Bucket'	=> $bucket,
				'Key'		=> $key,
			]);
		} catch (\Exception $e) {
			throw new NotFound('Object : ' . $key);
		}
	}
}
