<?php
require_once __DIR__ . "/BaseModel.php";

class AdProducModel extends BaseModel {
    
    public function getInventoryStatus() {
        $sql = "SELECT p.*, l.tenLoaiSP,
                (SELECT COALESCE(SUM(od.quantity), 0)
                 FROM order_details od
                 INNER JOIN orders o ON CAST(od.order_id AS SIGNED) = o.id
                 WHERE od.product_id = p.masp 
                 AND o.trangthai IN ('đã thanh toán', 'đã giao hàng')) as sold_count
                FROM tblsanpham p
                LEFT JOIN tblloaisp l ON p.maloaisp = l.maLoaiSP
                ORDER BY p.soluong ASC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            error_log("SQL Query: " . $sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Results: " . print_r($results, true));
            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi truy vấn inventory status: " . $e->getMessage());
            return [];
        }
    }
    private $table="tblsanpham";

    public function search($keyword) {
        try {
            // Chuẩn bị từ khóa tìm kiếm
            $searchTerm = "%" . $keyword . "%";
            
            // Truy vấn tìm kiếm trong cả tên và mã sản phẩm
            $sql = "SELECT * FROM {$this->table} WHERE tensp LIKE :keyword OR masp LIKE :keyword";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':keyword' => $searchTerm]);
            
            // Debug thông tin
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Search keyword: " . $keyword);
            error_log("Total results: " . count($results));
            
            if (count($results) > 0) {
                error_log("First product found: " . print_r($results[0], true));
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("Search error: " . $e->getMessage());
            return [];
        }
    }

    public function getByType($maLoaiSP) {
        try {
            // Truy vấn đơn giản hơn, chỉ lấy từ bảng sản phẩm
            $sql = "SELECT * FROM {$this->table} WHERE maLoaiSP = :maLoaiSP";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->execute();
            
            // Debug log
            error_log("getByType Query: " . $sql);
            error_log("getByType maLoaiSP: " . $maLoaiSP);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getByType Results count: " . count($results));
            if (count($results) === 0) {
                error_log("No products found for maLoaiSP: " . $maLoaiSP);
            }
            
            return $results;
        } catch (PDOException $e) {
            error_log("getByType Error: " . $e->getMessage());
            return [];
        }
    }

    public function insert($maLoaiSP,$masp,$tensp,$hinhanh,$soluong,
    $giaNhap,$giaXuat,$khuyenmai,$mota,$createDate) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($this->table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        // Kiểm tra xem mã  sản phẩm đã tồn tại chưa
        $column = $this->primaryKeys[$this->table];
        if($this->check($this->table, $column, $masp)>0){
            echo "Mã sản phẩm đã tồn tại. Vui lòng chọn mã khác.";
            return;
        }
        else{
            // Chuẩn bị câu lệnh INSERT
            $sql = "INSERT INTO tblsanpham (maLoaiSP,masp,tensp,hinhanh,soluong,
            giaNhap,giaXuat,khuyenmai,mota,createDate) 
                    VALUES (:maLoaiSP,:masp,:tensp,:hinhanh,:soluong,:giaNhap,
                    :giaXuat,:khuyenmai,:mota,:createDate)";
            try {
                $stmt = $this->db->prepare($sql);
                // Gán giá trị cho các tham số
                $stmt->bindParam(':maLoaiSP', $maLoaiSP);
                $stmt->bindParam(':masp', $masp);
                $stmt->bindParam(':tensp', $tensp);
                $stmt->bindParam(':hinhanh', $hinhanh);
                $stmt->bindParam(':soluong', $soluong);
                $stmt->bindParam(':giaNhap', $giaNhap);
                $stmt->bindParam(':giaXuat', $giaXuat);
                $stmt->bindParam(':khuyenmai', $khuyenmai);
                $stmt->bindParam(':mota', $mota);
                $stmt->bindParam(':createDate', $createDate);
                $stmt->execute();
                echo "Thêm sản phẩm thành công.";
            } catch (PDOException $e) {
                echo "Thất bại" . $e->getMessage();
            } 
        }    
    }
    public function searchProducts($keyword) {
        $sql = "SELECT * FROM tblsanpham WHERE tensp LIKE :keyword";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $id, $data) {
        try {
            return parent::update($table, $id, $data);
        } catch (Exception $e) {
            error_log("Lỗi cập nhật sản phẩm: " . $e->getMessage());
            throw $e;
        }
    }

    // Thêm phương thức cập nhật số lượng
    public function updateQuantity($masp, $quantity) {
        try {
            $sql = "UPDATE tblsanpham SET soluong = soluong - :quantity WHERE masp = :masp";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':masp', $masp);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật số lượng: " . $e->getMessage());
            return false;
        }
    }

    public function getBestSellers() {
        try {
            $sql = "SELECT p.*, 
                   COUNT(od.product_id) as sold_count
                   FROM tblsanpham p
                   LEFT JOIN order_details od ON p.masp = od.product_id
                   LEFT JOIN orders o ON CAST(od.order_id AS SIGNED) = o.id
                   WHERE o.trangthai = 'đã thanh toán'
                   GROUP BY p.masp
                   ORDER BY sold_count DESC
                   LIMIT 3";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm bán chạy: " . $e->getMessage());
            return [];
        }
    }

    public function getNewProducts() {
        try {
            $sql = "SELECT * FROM tblsanpham ORDER BY createDate DESC LIMIT 3";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm mới: " . $e->getMessage());
            return [];
        }
    }

    public function getAllProductsPaginated($page = 1, $perPage = 8) {
        try {
            // Tính offset
            $offset = ($page - 1) * $perPage;
            
            // Lấy tổng số sản phẩm
            $countSql = "SELECT COUNT(*) as total FROM tblsanpham";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Lấy sản phẩm theo trang
            $sql = "SELECT * FROM tblsanpham ORDER BY createDate DESC LIMIT :offset, :perPage";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'products' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'totalPages' => ceil($total / $perPage),
                'currentPage' => $page
            ];
        } catch (PDOException $e) {
            error_log("Lỗi phân trang sản phẩm: " . $e->getMessage());
            return [
                'products' => [],
                'total' => 0,
                'totalPages' => 0,
                'currentPage' => 1
            ];
        }
    }

    /**
     * Lọc sản phẩm theo khoảng giá
     * @param int $minPrice - Giá tối thiểu
     * @param int $maxPrice - Giá tối đa
     * @param string $sortBy - Sắp xếp (price_asc, price_desc, popularity, rating)
     * @param int $page - Trang hiện tại
     * @param int $perPage - Số sản phẩm mỗi trang
     * @return array
     */
    public function filterAndSort($minPrice = 0, $maxPrice = 999999999, $sortBy = 'price_asc', $page = 1, $perPage = 12) {
        try {
            $offset = ($page - 1) * $perPage;
            
            // Xác định cột sắp xếp
            $orderClause = "ORDER BY p.giaXuat ASC";
            switch ($sortBy) {
                case 'price_desc':
                    $orderClause = "ORDER BY p.giaXuat DESC";
                    break;
                case 'popularity':
                    $orderClause = "ORDER BY COALESCE(sold_count, 0) DESC, p.giaXuat ASC";
                    break;
                case 'rating':
                    $orderClause = "ORDER BY COALESCE(avg_rating, 0) DESC, p.giaXuat ASC";
                    break;
                case 'price_asc':
                default:
                    $orderClause = "ORDER BY p.giaXuat ASC";
                    break;
            }
            
            // Lấy tổng số sản phẩm
            $countSql = "SELECT COUNT(*) as total FROM tblsanpham p 
                        WHERE p.giaXuat >= :minPrice AND p.giaXuat <= :maxPrice";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
            $countStmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Lấy sản phẩm với lọc giá
            $sql = "SELECT p.*,
                    (SELECT COALESCE(SUM(CASE WHEN od.order_id IS NOT NULL THEN 1 ELSE 0 END), 0)
                     FROM order_details od
                     WHERE od.product_id = p.masp) as sold_count,
                    (SELECT COALESCE(AVG(r.sosao), 0)
                     FROM tblreview r
                     WHERE r.masp = p.masp AND r.trangthai = 'đã duyệt') as avg_rating
                    FROM tblsanpham p
                    WHERE p.giaXuat >= :minPrice AND p.giaXuat <= :maxPrice
                    {$orderClause}
                    LIMIT :offset, :perPage";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
            $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            
            return [
                'products' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'total' => $total,
                'totalPages' => ceil($total / $perPage),
                'currentPage' => $page,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
                'sortBy' => $sortBy
            ];
        } catch (PDOException $e) {
            error_log("Lỗi lọc và sắp xếp sản phẩm: " . $e->getMessage());
            return [
                'products' => [],
                'total' => 0,
                'totalPages' => 0,
                'currentPage' => 1,
                'minPrice' => 0,
                'maxPrice' => 0,
                'sortBy' => 'price_asc'
            ];
        }
    }

    /**
     * Lọc sản phẩm theo loại và giá
     * @param string $maLoaiSP - Mã loại sản phẩm
     * @param int $minPrice - Giá tối thiểu
     * @param int $maxPrice - Giá tối đa
     * @param string $sortBy - Sắp xếp
     * @return array
     */
    public function filterByTypeAndPrice($maLoaiSP, $minPrice = 0, $maxPrice = 999999999, $sortBy = 'price_asc') {
        try {
            $orderClause = "ORDER BY p.giaXuat ASC";
            switch ($sortBy) {
                case 'price_desc':
                    $orderClause = "ORDER BY p.giaXuat DESC";
                    break;
                case 'popularity':
                    $orderClause = "ORDER BY COALESCE(sold_count, 0) DESC, p.giaXuat ASC";
                    break;
                case 'rating':
                    $orderClause = "ORDER BY COALESCE(avg_rating, 0) DESC, p.giaXuat ASC";
                    break;
                case 'price_asc':
                default:
                    $orderClause = "ORDER BY p.giaXuat ASC";
                    break;
            }
            
            $sql = "SELECT p.*,
                    (SELECT COALESCE(SUM(CASE WHEN od.order_id IS NOT NULL THEN 1 ELSE 0 END), 0)
                     FROM order_details od
                     WHERE od.product_id = p.masp) as sold_count,
                    (SELECT COALESCE(AVG(r.sosao), 0)
                     FROM tblreview r
                     WHERE r.masp = p.masp AND r.trangthai = 'đã duyệt') as avg_rating
                    FROM tblsanpham p
                    WHERE p.maLoaiSP = :maLoaiSP 
                    AND p.giaXuat >= :minPrice 
                    AND p.giaXuat <= :maxPrice
                    {$orderClause}";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
            $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lọc theo loại và giá: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy giá min và max cho bộ lọc
     * @return array
     */
    public function getPriceRange() {
        try {
            $sql = "SELECT MIN(giaXuat) as minPrice, MAX(giaXuat) as maxPrice FROM tblsanpham";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'minPrice' => $result['minPrice'] ?? 0,
                'maxPrice' => $result['maxPrice'] ?? 1000000
            ];
        } catch (PDOException $e) {
            error_log("Lỗi lấy giá tối đa tối thiểu: " . $e->getMessage());
            return ['minPrice' => 0, 'maxPrice' => 1000000];
        }
    }

    /**
     * Lấy đánh giá trung bình của sản phẩm
     * @param string $masp - Mã sản phẩm
     * @return array
     */
    public function getAverageRating($masp) {
        try {
            $sql = "SELECT COALESCE(AVG(sosao), 0) as avg_rating, COUNT(*) as review_count 
                   FROM tblreview WHERE masp = :masp AND trangthai = 'đã duyệt'";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':masp', $masp);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy đánh giá: " . $e->getMessage());
            return ['avg_rating' => 0, 'review_count' => 0];
        }
    }

    /**
     * Tìm kiếm và lọc sản phẩm
     * @param string $keyword - Từ khóa tìm kiếm
     * @param int $minPrice - Giá tối thiểu
     * @param int $maxPrice - Giá tối đa
     * @param string $sortBy - Cách sắp xếp
     * @return array
     */
    public function searchWithFilter($keyword, $minPrice = 0, $maxPrice = 999999999, $sortBy = 'price_asc') {
        try {
            $orderClause = "ORDER BY p.giaXuat ASC";
            switch ($sortBy) {
                case 'price_desc':
                    $orderClause = "ORDER BY p.giaXuat DESC";
                    break;
                case 'popularity':
                    $orderClause = "ORDER BY COALESCE(sold_count, 0) DESC";
                    break;
                case 'rating':
                    $orderClause = "ORDER BY COALESCE(avg_rating, 0) DESC";
                    break;
                case 'price_asc':
                default:
                    $orderClause = "ORDER BY p.giaXuat ASC";
                    break;
            }
            
            $searchTerm = '%' . $keyword . '%';
            $sql = "SELECT p.*,
                    (SELECT COALESCE(SUM(CASE WHEN od.order_id IS NOT NULL THEN 1 ELSE 0 END), 0)
                     FROM order_details od
                     WHERE od.product_id = p.masp) as sold_count,
                    (SELECT COALESCE(AVG(r.sosao), 0)
                     FROM tblreview r
                     WHERE r.masp = p.masp AND r.trangthai = 'đã duyệt') as avg_rating
                    FROM tblsanpham p
                    WHERE (p.tensp LIKE :keyword OR p.masp LIKE :keyword)
                    AND p.giaXuat >= :minPrice 
                    AND p.giaXuat <= :maxPrice
                    {$orderClause}";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':keyword', $searchTerm);
            $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_INT);
            $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi tìm kiếm với lọc: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy sản phẩm liên quan (cùng danh mục)
     * @param int $productId - ID của sản phẩm hiện tại
     * @param int $categoryId - ID của danh mục
     * @param int $limit - Số sản phẩm cần lấy (default: 5)
     * @return array - Danh sách sản phẩm liên quan
     */
    public function getRelatedProducts($productId, $categoryId, $limit = 5) {
        try {
            $sql = "SELECT p.*,
                    (SELECT COALESCE(SUM(od.quantity), 0)
                     FROM order_details od
                     INNER JOIN orders o ON CAST(od.order_id AS SIGNED) = o.id
                     WHERE od.product_id = p.masp 
                     AND o.trangthai IN ('đã thanh toán', 'đã giao hàng')) as sold_count,
                    (SELECT COALESCE(AVG(r.sosao), 0)
                     FROM tblreview r
                     WHERE r.masp = p.masp AND r.trangthai = 'đã duyệt') as avg_rating
                    FROM {$this->table} p
                    WHERE p.maloaisp = :categoryId 
                    AND p.masp != :productId
                    ORDER BY p.maloaisp DESC, sold_count DESC, p.giaXuat ASC
                    LIMIT :limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm liên quan: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy sản phẩm bán chạy nhất
    public function getTopSellingProducts($startDate, $endDate, $limit = 10) {
        $sql = "SELECT 
                    p.masp, 
                    p.tensp, 
                    p.giaXuat,
                    COUNT(od.id) as quantity_sold,
                    SUM(od.quantity * od.price) as total_revenue
                FROM tblsanpham p
                LEFT JOIN order_details od ON p.masp = od.product_id
                LEFT JOIN orders o ON CAST(od.order_id AS SIGNED) = o.id
                WHERE o.created_at IS NULL 
                   OR (o.created_at >= :startDate AND o.created_at <= :endDate)
                   AND o.trangthai IN ('đã thanh toán', 'đã giao hàng')
                GROUP BY p.masp
                ORDER BY quantity_sold DESC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm bán chạy: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy sản phẩm bán chậm nhất
    public function getSlowSellingProducts($startDate, $endDate, $limit = 10) {
        $sql = "SELECT 
                    p.masp, 
                    p.tensp, 
                    p.giaXuat,
                    COALESCE(COUNT(od.id), 0) as quantity_sold,
                    COALESCE(SUM(od.quantity * od.price), 0) as total_revenue
                FROM tblsanpham p
                LEFT JOIN order_details od ON p.masp = od.product_id
                LEFT JOIN orders o ON CAST(od.order_id AS SIGNED) = o.id 
                    AND o.created_at >= :startDate 
                    AND o.created_at <= :endDate
                    AND o.trangthai IN ('đã thanh toán', 'đã giao hàng')
                GROUP BY p.masp
                HAVING quantity_sold <= 5 OR quantity_sold IS NULL
                ORDER BY quantity_sold ASC
                LIMIT :limit";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm bán chậm: " . $e->getMessage());
            return [];
        }
    }
}

