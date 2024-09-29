<?php


$pdo = new PDO("mysql:host=localhost;dbname=Practice1;charset=utf8mb4", "root", "");

$pageConuter=1;

$url="https://www.mehrnews.com/page/archive.xhtml?mn=7&wide=0&dy=7&ms=0&pi=$pageConuter&yr=1403";
$data=file_get_contents($url);
$data = strip_tags($data,"<a>");
$d = preg_split("/<\/a>/",$data);


for ($pageCounter = 1; $pageCounter <= 100; $pageCounter++){
    foreach ($d as $k=> $link )
{
    if( strpos($link, "<a href=") !== FALSE )
    {
        $link = preg_replace("/.*<a\s+href=\"/sm","",$link);
        $link = preg_replace("/\".*/","",$link);
        var_dump($link."<br>----</br>");
        $sql = "INSERT INTO links (link) VALUES (:link)";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':link' ,$link);
        $statement->execute();
        echo 'done';
    }
}
    $pageConuter++;
}
