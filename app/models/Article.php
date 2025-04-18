<?php

class Article {
    private $conn;
    private $table_name = "articles";

    public $id;
    public $title;
    public $content;
    public $author_id;
    public $pen_name_id;
    public $date;
    public $page_number;
    public $download_count;
    public $images = [];

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (title, content, author_id, pen_name_id, date, page_number)
                VALUES
                (:title, :content, :author_id, :pen_name_id, :date, :page_number)";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->pen_name_id = htmlspecialchars(strip_tags($this->pen_name_id));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->page_number = htmlspecialchars(strip_tags($this->page_number));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":author_id", $this->author_id);
        $stmt->bindParam(":pen_name_id", $this->pen_name_id);
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":page_number", $this->page_number);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function addImage($image_path, $is_thumbnail = false) {
        $query = "INSERT INTO article_images (article_id, image_path, is_thumbnail)
                VALUES (:article_id, :image_path, :is_thumbnail)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":article_id", $this->id);
        $stmt->bindParam(":image_path", $image_path);
        $stmt->bindParam(":is_thumbnail", $is_thumbnail);

        return $stmt->execute();
    }

    public function getImages() {
        $query = "SELECT * FROM article_images WHERE article_id = :article_id ORDER BY is_thumbnail DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":article_id", $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementDownloadCount() {
        $query = "UPDATE " . $this->table_name . " 
                SET download_count = download_count + 1 
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT a.*, au.name as author_name, pn.name as pen_name
                FROM " . $this->table_name . " a
                LEFT JOIN authors au ON a.author_id = au.id
                LEFT JOIN pen_names pn ON a.pen_name_id = pn.id
                WHERE a.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->author_id = $row['author_id'];
            $this->pen_name_id = $row['pen_name_id'];
            $this->date = $row['date'];
            $this->page_number = $row['page_number'];
            $this->download_count = $row['download_count'];
            $this->author_name = $row['author_name'];
            $this->pen_name = $row['pen_name'];
            $this->images = $this->getImages();
            return true;
        }
        return false;
    }

    public function search($keywords) {
        $query = "SELECT a.*, au.name as author_name, pn.name as pen_name
                FROM " . $this->table_name . " a
                LEFT JOIN authors au ON a.author_id = au.id
                LEFT JOIN pen_names pn ON a.pen_name_id = pn.id
                WHERE a.title LIKE ? OR au.name LIKE ? OR pn.name LIKE ?
                ORDER BY a.date DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();

        return $stmt;
    }
} 