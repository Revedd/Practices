<?php

set_time_limit(0); // Be khater error: Maximum execution time of 120 seconds exceeded


$pdo = new PDO("mysql:host=localhost;dbname=Practice1;charset=utf8mb4", "root", "");




$sql = "SELECT link FROM links";
$statement = $pdo->query($sql);
$StoredLinks = $statement->fetchAll(PDO::FETCH_COLUMN);

// Original url:
$OriginalUrl = "https://www.mehrnews.com";

foreach ($StoredLinks as $StoredLinks) {
    $Flink = $OriginalUrl . $StoredLinks;

    // Fetch page content
    $data = @file_get_contents($Flink);
    if ($data === FALSE) {
        echo "Can't open $Flink";
        continue;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($data); //"@" ba'es misheh ke warning ha ignore beshan

    //hata ba inke az xpath estefadeh shod hamchenan nashod title grab besheh
    $xpath = new DOMXPath($dom);
    $titleNode = $xpath->query('//*[@id="item"]/div[2]/div[2]/h1');
    $title = 'No Title';
    if ($titleNode->length > 0) {
        $title = trim($titleNode->item(0)->nodeValue);
    }



// Kalame "مجلس" kheili natijeh dasht
    $majles = "ظهوریان";

    // Majles Check
    if (strpos($data, $majles) !== false) {


        preg_match_all('/<img[^>]+src="([^">]+)"/i', $data, $imatches);
        $images = json_encode($imatches[1]);

        preg_match_all('/<a[^>]+href="([^">]+)"/i', $data, $lmatches);
        $PageLinks = [];

        foreach ($lmatches[1] as $PageLink) {
            if (strpos($PageLink, 'http') === false) {
                $PageLink = $OriginalUrl . $PageLink;
            }
            $PageLinks[] = $PageLink;
        }

        $PageLinksJson = json_encode($PageLinks);

        $created_At = date('d-m-Y H:i:s');

        $insertSql = "INSERT INTO found_pages (title, link, found_links, images, created_at) VALUES (:title, :link, :found_links, :images, :created_at)";
        $insertStatement = $pdo->prepare($insertSql);
        $insertStatement->bindParam(':title', $title);
        $insertStatement->bindParam(':link', $Flink);
        $insertStatement->bindParam(':found_links', $PageLinksJson);
        $insertStatement->bindParam(':images', $images);
        $insertStatement->bindParam(':created_at', $created_At);
        $insertStatement->execute();

        echo "$majles found: $Flink"."<br>---<br>";
        echo "title: $title"."<br>---<br>";
        echo "images: $images"."<br>---<br>";
        echo "in page links: " . print_r($PageLinks, true)."<br>---<br>";
        echo "created at: $created_At"."<br>---<br>";
        echo "<br>-------<br>";
    } else {
        echo "$majles not in: $Flink"."<br>---<br>";
    }
}

?>