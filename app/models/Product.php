<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $mainCategory;
    public $subCategory;
    public $image;
    public $quantity;
    public $error;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, 
                          mainCategory=:mainCategory, subCategory=:subCategory, image=:image, quantity=:quantity";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":mainCategory", $this->mainCategory);
            $stmt->bindParam(":subCategory", $this->subCategory);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":quantity", $this->quantity);

            if ($stmt->execute()) {
                return true;
            }

            $this->error = implode(", ", $stmt->errorInfo());
            return false;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function uploadImage($file) {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $publicPath = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($file["name"]);
        $targetFile = $uploadDir . $fileName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);

        if ($check === false) return false;
        if ($file["size"] > 5000000) return false;
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) return false;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $publicPath . $fileName;
        }

        return false;
    }


    function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readPaging($from_record_num, $records_per_page) {
        $query = "SELECT * FROM " . $this->table_name . " LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function count() {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    public function getAllProducts() {
        $query = "SELECT id, name FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getProductById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET name = :name,
                      description = :description,
                      price = :price,
                      mainCategory = :mainCategory,
                      subCategory = :subCategory,
                      image = :image,
                      quantity = :quantity
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':mainCategory', $this->mainCategory);
        $stmt->bindParam(':subCategory', $this->subCategory);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':quantity', $this->quantity);

        if ($stmt->execute()) {
            return true;
        } else {
            $this->error = $stmt->errorInfo()[2];
            return false;
        }
    }

    public function validateProduct() {
        $errors = [];

        if (empty($this->name)) {
            $errors[] = "Product name is required.";
        }

        if (empty($this->description)) {
            $errors[] = "Product description is required.";
        }

        if (!is_numeric($this->price) || $this->price <= 0) {
            $errors[] = "Product price must be a valid number greater than zero.";
        }

        if (!is_numeric($this->quantity) || $this->quantity < 0) {
            $errors[] = "Product quantity must be a valid number greater than or equal to zero.";
        }

        return $errors;
    }

    function fetchProductDetails() {
        $query = "SELECT description, price, mainCategory, subCategory, image, quantity FROM " . $this->table_name . " WHERE name = :name LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->name);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function searchProducts($query) {
        $query = "%" . htmlspecialchars(strip_tags($query)) . "%";
        $searchQuery = "SELECT id, name, description, price, mainCategory, subCategory, image, quantity FROM " . $this->table_name . " WHERE name LIKE :query OR description LIKE :query";
        $stmt = $this->conn->prepare($searchQuery);
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt;
    }

    public function getBySubCategory(string $subCategory, int $offset, int $limit): array {
        $sql = "SELECT * FROM {$this->table_name} 
                WHERE subCategory = :subCategory 
                LIMIT :offset, :limit";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':subCategory', $subCategory, PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $id): bool
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
