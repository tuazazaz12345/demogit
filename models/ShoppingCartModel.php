<?php
require_once 'BaseModel.php';

class ShoppingCartModel extends BaseModel {
    protected $table = 'shopping_carts';

    /**
     * ✅ Lưu toàn bộ giỏ hàng vào database
     * @param int $userId - ID của user
     * @param array $cartItems - Giỏ hàng từ $_SESSION['cart']
     */
    public function saveCart($userId, $cartItems) {
        if (empty($userId)) {
            error_log("SaveCart: userId is empty");
            return false;
        }
        
        if (empty($cartItems)) {
            error_log("SaveCart: cartItems is empty, clearing cart for user_id: " . $userId);
            return $this->clearCart($userId);
        }

        try {
            // Xóa giỏ hàng cũ
            $this->clearCart($userId);
            error_log("SaveCart: Cleared old cart for user_id: " . $userId);

            // Thêm giỏ hàng mới - dùng INSERT ON DUPLICATE KEY UPDATE để handle unique constraint
            $inserted = 0;
            foreach ($cartItems as $masp => $item) {
                $sql = "INSERT INTO {$this->table} 
                        (user_id, product_id, quantity, original_price, discount_percent, 
                         promotional_price, from_promotion, product_name, product_image, created_at, updated_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                        ON DUPLICATE KEY UPDATE
                            quantity = VALUES(quantity),
                            original_price = VALUES(original_price),
                            discount_percent = VALUES(discount_percent),
                            promotional_price = VALUES(promotional_price),
                            from_promotion = VALUES(from_promotion),
                            product_name = VALUES(product_name),
                            product_image = VALUES(product_image),
                            updated_at = NOW()";
                
                $stmt = $this->query($sql, [
                    $userId,
                    $masp,
                    (int)($item['qty'] ?? 1),
                    (float)($item['giaxuat'] ?? 0),
                    (float)($item['khuyenmai'] ?? 0),
                    isset($item['promotional_price']) ? (float)$item['promotional_price'] : null,
                    $item['from_promotion'] ? 1 : 0,
                    $item['tensp'] ?? '',
                    $item['hinhanh'] ?? ''
                ]);
                
                if ($stmt && $stmt->rowCount() > 0) {
                    $inserted++;
                }
            }
            
            error_log("SaveCart: Inserted/Updated " . $inserted . " items for user_id: " . $userId);
            return true;
        } catch (Exception $e) {
            error_log("SaveCart Error: " . $e->getMessage());
            error_log("SaveCart Stack: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * ✅ Load giỏ hàng từ database về session
     * @param int $userId - ID của user
     * @return array - Giỏ hàng (định dạng $_SESSION['cart'])
     */
    public function loadCart($userId) {
        if (empty($userId)) {
            error_log("LoadCart: userId is empty");
            return [];
        }

        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
            $items = $this->select($sql, [(int)$userId]);
            
            error_log("LoadCart: Found " . count($items ?? []) . " items for user_id: " . $userId);

            if (empty($items)) {
                error_log("LoadCart: No items found for user_id: " . $userId);
                return [];
            }

            $cart = [];
            foreach ($items as $item) {
                $masp = $item['product_id'];
                $cart[$masp] = [
                    'qty' => (int)$item['quantity'],
                    'masp' => $masp,
                    'tensp' => $item['product_name'],
                    'hinhanh' => $item['product_image'],
                    'giaxuat' => (float)$item['original_price'],
                    'khuyenmai' => (float)$item['discount_percent'],
                    'promotional_price' => $item['promotional_price'] ? (float)$item['promotional_price'] : null,
                    'from_promotion' => (bool)$item['from_promotion']
                ];
            }

            error_log("LoadCart: Formatted " . count($cart) . " items for user_id: " . $userId);
            return $cart;
        } catch (Exception $e) {
            error_log("LoadCart Error: " . $e->getMessage());
            error_log("LoadCart Stack: " . $e->getTraceAsString());
            return [];
        }
    }

    /**
     * ✅ Xóa giỏ hàng của user
     * @param int $userId - ID của user
     */
    public function clearCart($userId) {
        if (empty($userId)) {
            return false;
        }

        try {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ?";
            return $this->query($sql, [$userId]) !== null;
        } catch (Exception $e) {
            error_log("ClearCart Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Xóa 1 sản phẩm khỏi giỏ hàng
     * @param int $userId - ID của user
     * @param string $productId - ID sản phẩm
     */
    public function removeItem($userId, $productId) {
        if (empty($userId) || empty($productId)) {
            return false;
        }

        try {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND product_id = ?";
            return $this->query($sql, [$userId, $productId]) !== null;
        } catch (Exception $e) {
            error_log("RemoveItem Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Cập nhật số lượng 1 sản phẩm
     * @param int $userId - ID của user
     * @param string $productId - ID sản phẩm
     * @param int $quantity - Số lượng mới
     */
    public function updateQuantity($userId, $productId, $quantity) {
        if (empty($userId) || empty($productId) || $quantity < 1) {
            return false;
        }

        try {
            $sql = "UPDATE {$this->table} 
                    SET quantity = ? 
                    WHERE user_id = ? AND product_id = ?";
            
            return $this->query($sql, [$quantity, $userId, $productId]) !== null;
        } catch (Exception $e) {
            error_log("UpdateQuantity Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Kiểm tra user có giỏ hàng không
     * @param int $userId - ID của user
     * @return bool
     */
    public function hasCart($userId) {
        if (empty($userId)) {
            return false;
        }

        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = ?";
            $stmt = $this->query($sql, [$userId]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (Exception $e) {
            error_log("HasCart Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
