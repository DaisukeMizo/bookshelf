<?php

require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/S3Client.php';
require_once __DIR__ . '/S3Func.php';

function deleteElement($pdo, $id, $s3)
{
  try {
    $stmt = $pdo->prepare("SELECT image_key FROM bookshelf WHERE id=:id");
    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
    $stmt->execute();
    $image_key = $stmt->fetchColumn();

    DeleteS3Object($s3, $image_key);

    $stmt = $pdo->prepare("DELETE FROM bookshelf WHERE id=:id");
    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
    $stmt->execute();
  } catch (PDOException $e) {
    echo "要素削除失敗" . $e->getMessage() . PHP_EOL;
    die();
  }
};

function getElement($pdo, $id)
{
  try {
    $stmt = $pdo->prepare("SELECT * FROM bookshelf WHERE id=:id ORDER BY score DESC");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
  } catch (PDOException $e) {
    echo "要素取得失敗" . $e->getMessage() . PHP_EOL;
    die();
  }
}

function updateElement($pdo, $element)
{
  try {
    $stmt = $pdo->prepare("UPDATE bookshelf SET title = :title, image_url = :image_url, image_key = :image_key, score = :score, mediums = :mediums, status = :status WHERE id = :id");
    $stmt->bindParam(":id", $element['id']);
    $stmt->bindParam(":title", $element['title']);
    $stmt->bindParam(":image_url", $element['image_url']);
    $stmt->bindParam(":image_key", $element['image_key']);
    $stmt->bindParam(":score", $element['score']);
    $stmt->bindParam(":mediums", $element['mediums']);
    $stmt->bindParam(":status", $element['status']);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result;
  } catch (PDOException $e) {
    echo "アップデート失敗" . $e->getMessage() . PHP_EOL;
    die();
  }
}

$pdo = dbConnect();
$action = "./edit.php";
$title = "編集";
$btn_name = "update";

if (isset($_POST['delete'])) {
  $element[''] = $_POST['delete'];
  $image = deleteElement($pdo, $element[''], $s3);
  header("Location: ./index.php");
}

if (isset($_POST['edit'])) {
  $id = $_POST['edit'];
  $element = getElement($pdo, $id);
}

if (isset($_POST['update'])) {
  include "./validate.php";

  if (empty($_POST['status'])) {
    $_POST['status'] = '';
  }

  $allow_exts = array("png", "pdf", "jpg", "jpeg");
  $tmp_image   = $_FILES['image']['tmp_name'];
  $image_key  = basename($_FILES['image']['name']);
  $file_ext = strtolower(pathinfo($image_key, PATHINFO_EXTENSION));
  $old_image = $_POST['old_image_key'];

  $element = [
    'title' => $_POST['title'],
    'image_url' => $_POST['old_image_URL'],
    'score' => $_POST['score'],
    'mediums' => $_POST['mediums'],
    'status' => $_POST['status'],
    'id' => $_POST['id']
  ];

  $errors = validate($element, $file_ext, $allow_exts);

  if (empty($tmp_image)) {
    $image_key = $old_image;
  } else {
    DeleteS3Object($s3, $old_image);
    $result = uploadToS3($s3, $image_key, $tmp_image);
    $element['image_url'] = $result['ObjectURL'];
  }
  if (!count($errors)) {
    $element['image_key'] = $image_key;
    $pdo = dbConnect();
    updateElement($pdo, $element);
    header("Location: ./index.php");
  }
}

include('html/form.php');
