<?php

require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/S3Client.php';
require_once __DIR__ . '/S3Func.php';
include './validate.php';

function addElement($element)
{
  try {
    $pdo = dbConnect();
    $stmt = $pdo->prepare("INSERT INTO bookshelf (title, image_url, image_key, score, mediums, status) VALUES (:title, :image_url, :image_key, :score, :mediums, :status)");
    $stmt->bindParam(":title", $element['title'], PDO::PARAM_STR);
    $stmt->bindParam(":image_url", $element['image_url'], PDO::PARAM_STR);
    $stmt->bindParam(":image_key", $element['image_key'], PDO::PARAM_STR);
    $stmt->bindParam(":score", $element['score'], PDO::PARAM_INT);
    $stmt->bindParam(":mediums", $element['mediums'], PDO::PARAM_STR);
    $stmt->bindParam(":status", $element['status'], PDO::PARAM_STR);
    $stmt->execute();
  } catch (PDOException $e) {
    echo "データ追加失敗" . $e->getMessage() . PHP_EOL;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (empty($_POST['status'])) {
    $_POST['status'] = '';
  }

  $allow_exts = array("png", "pdf", "jpg", "jpeg");
  $tmp_file   = $_FILES['image']['tmp_name'];
  $file_name  = basename($_FILES['image']['name']);
  $tmp_file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

  $element = [
    'title' => $_POST['title'],
    'score' => $_POST['score'],
    'image_url' => '',
    'image_key' => '',
    'mediums' => $_POST['mediums'],
    'status' => $_POST['status']
  ];

  $errors = validate($element);

  if (!checkFileExtension($file_name, $allow_exts)) {
    $errors['image'] = 'png, pdf, jpgのいずれかのファイルを選択してください';
  }

  if (!count($errors)) {
    $result = uploadToS3($s3, $file_name, $tmp_file);
    $element['image_key'] = $file_name;
    $element['image_url'] = $result['ObjectURL'];
    addElement($element);
    header("Location: ./index.php");
  }
}

$title = "新規登録";
$action = "add.php";
include('html/form.php');
