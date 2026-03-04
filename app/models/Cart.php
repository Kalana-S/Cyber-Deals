<?php

class Cart
{
    private $conn;
    private $table_name = "cart";

    public $id;
    public $product_id;
    public $product_name;
    public $product_price;
    public $user_id;
    public $quantity;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Add product to cart
    public function addProduct()
    {
        $query = "INSERT INTO " . $this->table_name . "
                (product_id, product_name, product_price, user_id, quantity)
                VALUES (:product_id, :product_name, :product_price, :user_id, :quantity)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':product_name', $this->product_name);
        $stmt->bindParam(':product_price', $this->product_price);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':quantity', $this->quantity);

        return $stmt->execute();
    }

    // Get all cart items for logged user
    public function getCartItems()
    {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE user_id = :user_id
                  ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();

        return $stmt;
    }

    // Delete cart item (only owner can delete)
    public function deleteProduct()
    {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        return $stmt->execute();
    }
}
