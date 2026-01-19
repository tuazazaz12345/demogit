#!/bin/bash
# Content Moderation System - Setup Script
# Chạy script này để setup hệ thống kiểm duyệt nội dung

echo "================================"
echo "Content Moderation System Setup"
echo "================================"
echo ""

# Check if files exist
echo "✓ Checking files..."

files=(
    "app/ContentModerationService.php"
    "controllers/ContentModerationController.php"
    "models/ReviewModel.php"
    "controllers/ReviewController.php"
    "views/Back_end/ContentModerationDashboard.php"
    "views/Back_end/ContentModerationPending.php"
    "views/Back_end/ContentModerationReview.php"
    "views/Back_end/ContentModerationApproved.php"
    "views/Back_end/ContentModerationRejected.php"
    "migrations/content_moderation_migration.sql"
    "test_content_moderation.php"
    "CONTENT_MODERATION_GUIDE.md"
    "README_MODERATION.md"
)

missing=0
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "  ✓ $file"
    else
        echo "  ✗ Missing: $file"
        missing=$((missing + 1))
    fi
done

if [ $missing -eq 0 ]; then
    echo ""
    echo "✓ All files present!"
else
    echo ""
    echo "✗ $missing file(s) missing!"
    exit 1
fi

echo ""
echo "================================"
echo "Database Migration Required"
echo "================================"
echo ""
echo "Run the following SQL in your database:"
echo ""
echo "  mysql -u username -p database_name < migrations/content_moderation_migration.sql"
echo ""
echo "OR manually run the SQL from:"
echo "  migrations/content_moderation_migration.sql"
echo ""

echo "================================"
echo "Setup Complete!"
echo "================================"
echo ""
echo "Next steps:"
echo "1. Run database migration"
echo "2. Test the system: /phpnangcao/MVC/test_content_moderation.php"
echo "3. Login as admin"
echo "4. Access dashboard: /phpnangcao/MVC/ContentModerationController"
echo "5. Start moderating reviews!"
echo ""
echo "Documentation:"
echo "- README_MODERATION.md (Quick start)"
echo "- CONTENT_MODERATION_GUIDE.md (Detailed guide)"
echo ""
