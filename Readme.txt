two tables needed:

CREATE TABLE links (
    link LONGTEXT
);

CREATE TABLE found_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    link TEXT,
    found_links TEXT,
    images TEXT,
    created_at DATETIME
);

aval index.php va sepas searchData.php ro run mikonim