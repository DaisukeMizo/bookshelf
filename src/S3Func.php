<?php

use Aws\S3\Exception\S3Exception;

function uploadToS3($s3, $image_key, $tmp_image)
{
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
  try {
    return $s3->putObject([
      'Bucket' => $_ENV['S3_BUCKET_NAME'],
      'Key'    => $image_key,
      'SourceFile' => $tmp_image,
      'ContentType' => $_FILES['image']['type'],
    ]);
  } catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }
}

function DeleteS3Object($s3, $image_key)
{
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  try {
    $s3->deleteObject([
      'Bucket' => $_ENV['S3_BUCKET_NAME'],
      'Key'    => $image_key,
    ]);
  } catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }
}
