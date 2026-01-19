@echo off
REM Content Moderation System - Windows Setup
REM Chạy script này để setup hệ thống kiểm duyệt nội dung trên Windows

echo ================================
echo Content Moderation System Setup
echo ================================
echo.

REM Check if files exist
echo Checking files...

setlocal enabledelayedexpansion

set files[0]=app\ContentModerationService.php
set files[1]=controllers\ContentModerationController.php
set files[2]=models\ReviewModel.php
set files[3]=controllers\ReviewController.php
set files[4]=views\Back_end\ContentModerationDashboard.php
set files[5]=views\Back_end\ContentModerationPending.php
set files[6]=views\Back_end\ContentModerationReview.php
set files[7]=views\Back_end\ContentModerationApproved.php
set files[8]=views\Back_end\ContentModerationRejected.php
set files[9]=migrations\content_moderation_migration.sql
set files[10]=test_content_moderation.php
set files[11]=CONTENT_MODERATION_GUIDE.md
set files[12]=README_MODERATION.md

set missing=0

for /l %%i in (0,1,12) do (
    if exist "!files[%%i]!" (
        echo   [OK] !files[%%i]!
    ) else (
        echo   [ERROR] Missing: !files[%%i]!
        set /a missing=!missing! + 1
    )
)

echo.
if %missing% equ 0 (
    echo [SUCCESS] All files present!
) else (
    echo [ERROR] %missing% file(s) missing!
    pause
    exit /b 1
)

echo.
echo ================================
echo Database Migration Required
echo ================================
echo.
echo Run the following SQL in your database:
echo.
echo   Option 1 (phpMyAdmin):
echo   - Open phpMyAdmin
echo   - Select your database
echo   - Click "Import"
echo   - Choose file: migrations\content_moderation_migration.sql
echo   - Click "Import"
echo.
echo   Option 2 (Command line):
echo   - mysql -u username -p database_name < migrations\content_moderation_migration.sql
echo.
echo   Option 3 (Manual):
echo   - Open migrations\content_moderation_migration.sql
echo   - Copy SQL and run in your database
echo.

echo ================================
echo Setup Complete!
echo ================================
echo.
echo Next steps:
echo 1. Run database migration (see above)
echo 2. Test the system: http://localhost/phpnangcao/MVC/test_content_moderation.php
echo 3. Login as admin to your website
echo 4. Access dashboard: http://localhost/phpnangcao/MVC/ContentModerationController
echo 5. Start moderating reviews!
echo.
echo Documentation:
echo - README_MODERATION.md (Quick start - open in text editor)
echo - CONTENT_MODERATION_GUIDE.md (Detailed guide - open in text editor)
echo.
echo Press any key to exit...
pause > nul
