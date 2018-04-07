<?php

$dataFile = "bbs.dat";
$count_matsutake = "matsutake.dat";
$count_satoimo = "satoimo.dat";

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['name']) &&
    isset($_POST['address']) &&
    isset($_POST['message'])) {

  $select = $_POST['select'];

  //まつたけ・さといもの個数を記録する。
  //trueはまつたけ、elseはさといも
  if ($_POST['select'] == 'matsutake') {
    $fp = fopen($count_matsutake, 'a');
    fwrite($fp, "0\n");
    fclose($fp);
  } else {
    $fp = fopen($count_satoimo, 'a');
    fwrite($fp, "0\n");
    fclose($fp);
  }

  $name = $_POST['name'];
  $address = $_POST['address'];
  $message = $_POST['message'];

  $postedAt = date('Y-m-d H:i:s');

  $newData = $select . "\t" . $name ."\t" . $address . "\t" . $message . "\t" . $postedAt . "\n";

  $fp = fopen($dataFile, 'a');
  fwrite($fp, $newData);
  fclose($fp);

}

$posts = file($dataFile, FILE_IGNORE_NEW_LINES);
$posts = array_reverse($posts);

$matsutake = file($count_matsutake, FILE_IGNORE_NEW_LINES);
$satoimo = file($count_satoimo, FILE_IGNORE_NEW_LINES);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>まつたけの村・さといもの畑総選挙</title>
</head>
<body>
  <h1>まつたけの村・さといもの畑総選挙</h1>
  <p>あなたはどっち？メッセージと共に投票しよう！</p>
  <form action="" method="post">
    まつたけ：<input type="radio" name="select" value="まつたけ" checked="checked">
    さといも：<input type="radio" name="select" value="さといも"><br>
    お名前：<input type ="text" name="name"><br>
    ご住所：<input type ="text" name="address"><br>
    メッセージ：<input type ="text" name="message"><br>
    <input type="submit" name="" value="投票">
  </form>
  <h2>投票総数<?php echo count($posts); ?>件　まつたけ<?php echo count($matsutake); ?>件　さといも<?php echo count($satoimo); ?>件</h2>
  <ul>
    <?php if(count($posts)) : ?>
      <?php foreach($posts as $post) : ?>
        <?php list($select, $name, $address, $message, $postedAt) = explode("\t", $post); ?>
        <li><?php echo h($select); ?> <?php echo h($name); ?> <?php echo h($address); ?> <?php echo h($postedAt); ?> <?php echo h($message); ?> </li>
      <?php endforeach; ?>
    <?php else : ?>
    <li>まだ投稿はありません。</li>
  <?php endif; ?>
  </ul>
</body>
</html>
