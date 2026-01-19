<?php
/**
 * ContentModerationService - Hệ thống kiểm duyệt nội dung
 * Kiểm duyệt đánh giá, bình luận tự động và thủ công
 */

class ContentModerationService {
    
    // Danh sách từ ngữ cấm
    private static $prohibitedWords = [
        'từ cấm 1', 'từ cấm 2', 'từ cấm 3',
        'xấu', 'bê bối', 'lừa đảo',
        // Thêm thêm từ khóa cần cấm
    ];
    
    // Spam patterns
    private static $spamPatterns = [
        // URL patterns
        '/http[s]?:\/\/[^\s]+/i',
        // Email patterns
        '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/i',
        // Phone patterns
        '/(\d{3}[-.\s]?){2}\d{4}/i',
    ];
    
    /**
     * Kiểm tra nội dung tự động
     */
    public static function analyzeContent($content, $rating = 5) {
        $result = [
            'is_approved' => true,
            'spam_score' => 0,
            'issues' => [],
            'warnings' => [],
            'prohibited_words_found' => [],
            'suspicious_patterns_found' => []
        ];
        
        // Kiểm tra độ dài nội dung
        if (strlen($content) < 10) {
            $result['is_approved'] = false;
            $result['issues'][] = 'Nội dung quá ngắn (tối thiểu 10 ký tự)';
        }
        
        if (strlen($content) > 5000) {
            $result['is_approved'] = false;
            $result['issues'][] = 'Nội dung quá dài (tối đa 5000 ký tự)';
        }
        
        // Kiểm tra từ cấm
        $lowerContent = strtolower($content);
        foreach (self::$prohibitedWords as $word) {
            if (stripos($content, $word) !== false) {
                $result['prohibited_words_found'][] = $word;
                $result['spam_score'] += 30;
                $result['issues'][] = "Chứa từ cấm: '$word'";
            }
        }
        
        // Kiểm tra spam patterns
        foreach (self::$spamPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $result['suspicious_patterns_found'][] = $pattern;
                $result['spam_score'] += 25;
                $result['issues'][] = 'Chứa liên kết hoặc thông tin liên hệ nghi ngờ';
            }
        }
        
        // Kiểm tra rating không phù hợp (ví dụ: rating 5 sao nhưng nội dung tiêu cực)
        $negativeWords = ['tệ', 'tệ lắm', 'dở', 'hư', 'lỗi', 'chậm', 'xấu'];
        $negativeCount = 0;
        foreach ($negativeWords as $word) {
            if (stripos($content, $word) !== false) {
                $negativeCount++;
            }
        }
        
        if ($rating >= 4 && $negativeCount >= 3) {
            $result['warnings'][] = 'Nội dung tiêu cực nhưng rating cao - Cần kiểm tra';
            $result['spam_score'] += 15;
        }
        
        // Kiểm tra viết hoa quá nhiều
        $upperCount = strlen(preg_replace('/[^A-Z]/', '', $content));
        if ($upperCount > strlen($content) * 0.5) {
            $result['warnings'][] = 'Nội dung chứa quá nhiều chữ hoa';
            $result['spam_score'] += 10;
        }
        
        // Kiểm tra ký tự đặc biệt quá nhiều
        $specialCount = strlen(preg_replace('/[a-zA-Z0-9\sàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵđ]/', '', $content));
        if ($specialCount > strlen($content) * 0.3) {
            $result['warnings'][] = 'Nội dung chứa quá nhiều ký tự đặc biệt';
            $result['spam_score'] += 10;
        }
        
        // Kiểm tra repeat characters (aaaa, !!!! v.v.)
        if (preg_match('/(.)\1{4,}/', $content)) {
            $result['warnings'][] = 'Nội dung chứa ký tự lặp quá nhiều';
            $result['spam_score'] += 10;
        }
        
        // Kiểm tra số lần lặp lại từ
        $words = str_word_count(strtolower($content), 1);
        $wordFreq = array_count_values($words);
        $maxFreq = max($wordFreq);
        if ($maxFreq > count($words) * 0.4) {
            $result['warnings'][] = 'Nội dung chứa từ lặp lại quá nhiều';
            $result['spam_score'] += 10;
        }
        
        // Tính toán mức duyệt tự động
        if ($result['spam_score'] > 60) {
            $result['is_approved'] = false;
            $result['issues'][] = 'Spam score quá cao (' . $result['spam_score'] . '/100) - Cần kiểm duyệt thủ công';
        } else if ($result['spam_score'] > 30) {
            $result['warnings'][] = 'Mức cảnh báo cao - Nên xem xét kỹ';
        }
        
        return $result;
    }
    
    /**
     * Sanitize nội dung (loại bỏ thẻ HTML, mã độc v.v.)
     */
    public static function sanitizeContent($content) {
        // Loại bỏ thẻ HTML/PHP
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        
        // Loại bỏ các ký tự điều khiển
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $content);
        
        // Trim whitespace
        $content = trim($content);
        
        return $content;
    }
    
    /**
     * Lấy mức độ tin cậy (0-100)
     */
    public static function getTrustScore($spam_score) {
        return max(0, 100 - $spam_score);
    }
    
    /**
     * Lấy trạng thái kiểm duyệt dự đoán
     */
    public static function getPredictedStatus($spam_score) {
        if ($spam_score >= 60) {
            return 'spam';
        } else if ($spam_score >= 30) {
            return 'pending';
        } else {
            return 'approved';
        }
    }
}
?>
