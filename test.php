<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 13:48
 */
include_once ('config/config.php');

$id = 2321;

$db = Db::getConnection();
$sql = 'UPDATE catego1ries SET title = :title WHERE id = :id';
$result = $db->prepare($sql);
$result->bindParam(':title', $title, PDO::PARAM_STR);
$result->bindParam(':id', $id, PDO::PARAM_INT);
$result->setFetchMode(PDO::FETCH_OBJ);

echo $result->execute();