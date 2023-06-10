<?php

function validate($element)
{
  $errors = [];

  if (empty($element['title'])) {
    $errors['title'] = 'タイトルを入力してください';
  } elseif (mb_strlen($element['title']) > 255) {
    $errors['title'] = 'タイトルは255文字以内で入力してください';
  }

  if (empty($element['status'])) {
    $errors['status'] = '読書状況は「未読」「読了」「欲しい」のいずれかを入力してください';
  }

  return $errors;
}

function checkFileExtension($file_name, $allow_exts)
{
  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
  return in_array($file_ext, $allow_exts);
}
