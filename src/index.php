<?php

require_once __DIR__ .  '/get_lists.php';

$element = [
  'title' => '',
  'image_url' => '',
  'image_key' => '',
  'score' => '',
  'mediums' => '',
  'status' => ''
];
// $errors = [];
$status = ['未読' => 'unread', '読了' => 'done', '欲しい' => 'want'];
$title = "新規登録";
$action = "add.php";

include('html/view.php');
