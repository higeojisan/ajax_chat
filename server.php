<?php
  // データベースへの接続
  try {
    $pdo = new PDO('mysql:host=localhost;dbname=group_chat;charset=utf8','root','');
  } catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
  }

  // actionの取得
  $action = $_REQUEST['action'];

  switch ($action) {
    case "initialize":
      $all_comments = array();
      $get_all_comments_sql = $pdo->query('SELECT * FROM chats');
      while($row = $get_all_comments_sql->fetch(PDO::FETCH_ASSOC)) {
        $all_comments[] = array('id' => $row['id'], 'name' => $row['name'], 'comment' => $row['comment']);
      }
      echo json_encode($all_comments);
      break;
    case "addComment":
      $stmt = $pdo->prepare("INSERT INTO chats (name, comment) VALUES (:name, :comment)");
      $stmt->execute(array(':name' => $_REQUEST['name'], ':comment' => $_REQUEST['comment']));
      break;
    case "updateComment":
      $updated_comments = array();
      if ($_REQUEST['last_comment_id'] == 0) {
        $get_all_comments_sql = $pdo->query('SELECT * FROM chats');
        while($row = $get_all_comments_sql->fetch(PDO::FETCH_ASSOC)) {
          $updated_comments[] = array('id' => $row['id'], 'name' => $row['name'], 'comment' => $row['comment']);
        }
      } else {
        $get_updated_comments_sql = $pdo->query("SELECT * FROM chats where id > $_REQUEST[last_comment_id]");
        while($row = $get_updated_comments_sql->fetch(PDO::FETCH_ASSOC)) {
          $updated_comments[] = array('id' => $row['id'], 'name' => $row['name'], 'comment' => $row['comment']);
        }
      }
      echo json_encode($updated_comments);
      break;
  }
?>
