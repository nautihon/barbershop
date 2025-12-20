# Hướng dẫn chạy dự án Website Barbershop

## 📋 Yêu cầu hệ thống

Trước khi bắt đầu, đảm bảo bạn đã cài đặt:
- **PHP** >= 8.1
- **Composer** (Package manager cho PHP)
- **MySQL** >= 5.7 hoặc **MariaDB** >= 10.3
- **Node.js & NPM** (tùy chọn, cho frontend assets)

## 🚀 Các bước chạy dự án

### Bước 1: Cấu hình file .env

File `.env` đã được tạo tự động khi cài Laravel. Bạn cần cấu hình thông tin database:

1. Mở file `.env` trong thư mục gốc của dự án
2. Tìm và cập nhật các thông tin sau:

```env
APP_NAME=Barbershop
APP_ENV=local
APP_KEY=base64:... (đã được tạo tự động)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=
```

**Lưu ý:**
- `DB_DATABASE`: Tên database bạn muốn tạo (ví dụ: `barbershop`)
- `DB_USERNAME`: Tên user MySQL của bạn (thường là `root`)
- `DB_PASSWORD`: Mật khẩu MySQL (để trống nếu không có)

### Bước 2: Tạo database

Tạo database MySQL mới:

**Cách 1: Sử dụng MySQL Command Line**
```bash
mysql -u root -p
CREATE DATABASE barbershop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Cách 2: Sử dụng phpMyAdmin**
1. Truy cập phpMyAdmin (thường là http://localhost/phpmyadmin)
2. Tạo database mới tên `barbershop`
3. Chọn collation: `utf8mb4_unicode_ci`

### Bước 3: Chạy Migrations

Chạy lệnh sau để tạo các bảng trong database:

```bash
php artisan migrate
```

Lệnh này sẽ tạo tất cả các bảng:
- users
- services
- staffs
- staff_service
- staff_schedules
- appointments
- products
- orders
- order_items
- reviews

### Bước 4: Chạy Seeder (Tạo dữ liệu mẫu)

Chạy lệnh sau để tạo dữ liệu mẫu:

```bash
php artisan db:seed
```

Hoặc chạy cả migrate và seed cùng lúc:

```bash
php artisan migrate --seed
```

**Dữ liệu mẫu sẽ tạo:**
- 1 tài khoản Admin
- 1 tài khoản Staff
- 1 tài khoản User
- 4 dịch vụ mẫu
- 1 nhân viên với dịch vụ được gán
- 3 sản phẩm mẫu

### Bước 5: Tạo Storage Link

Tạo symbolic link để có thể truy cập file uploads:

```bash
php artisan storage:link
```

Lệnh này tạo link từ `storage/app/public` đến `public/storage` để có thể truy cập hình ảnh qua URL.

### Bước 6: Chạy Server

Khởi động server development:

```bash
php artisan serve
```

Server sẽ chạy tại: **http://localhost:8000**

Hoặc chỉ định port khác:

```bash
php artisan serve --port=8080
```

### Bước 7: Truy cập Website

Mở trình duyệt và truy cập:
- **Trang chủ**: http://localhost:8000
- **Đăng nhập**: http://localhost:8000/login

## 🔐 Tài khoản mẫu

Sau khi chạy seeder, bạn có thể đăng nhập với các tài khoản sau:

### Admin (Quản trị viên)
- **Email**: `admin@barbershop.com`
- **Password**: `password`
- **Quyền**: Toàn quyền quản lý hệ thống

### Staff (Nhân viên/Thợ cắt tóc)
- **Email**: `staff@barbershop.com`
- **Password**: `password`
- **Quyền**: Xem lịch làm việc, cập nhật trạng thái lịch hẹn

### User (Khách hàng)
- **Email**: `user@example.com`
- **Password**: `password`
- **Quyền**: Đặt lịch, mua sản phẩm

## 📁 Cấu trúc thư mục quan trọng

```
barbershop/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/               # Models
│   └── Http/Middleware/      # Middleware
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
├── public/                    # Public assets
└── storage/                   # File storage
```

## 🛠️ Các lệnh hữu ích

### Xóa và tạo lại database
```bash
php artisan migrate:fresh --seed
```

### Xem danh sách routes
```bash
php artisan route:list
```

### Xóa cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Tạo controller mới
```bash
php artisan make:controller ControllerName
```

### Tạo model mới
```bash
php artisan make:model ModelName
```

## ⚠️ Xử lý lỗi thường gặp

### Lỗi: "SQLSTATE[HY000] [1045] Access denied"
- **Nguyên nhân**: Sai username/password MySQL
- **Giải pháp**: Kiểm tra lại thông tin trong file `.env`

### Lỗi: "SQLSTATE[42S02] Base table or view not found"
- **Nguyên nhân**: Chưa chạy migrations
- **Giải pháp**: Chạy `php artisan migrate`

### Lỗi: "The stream or file could not be opened"
- **Nguyên nhân**: Thiếu quyền ghi file
- **Giải pháp**: 
  - Windows: Kiểm tra quyền thư mục `storage` và `bootstrap/cache`
  - Linux/Mac: `chmod -R 775 storage bootstrap/cache`

### Lỗi: "Class 'App\Models\...' not found"
- **Nguyên nhân**: Chưa chạy composer autoload
- **Giải pháp**: Chạy `composer dump-autoload`

### Lỗi: "Storage link not found"
- **Nguyên nhân**: Chưa tạo storage link
- **Giải pháp**: Chạy `php artisan storage:link`

## 🎯 Kiểm tra hệ thống

Sau khi chạy xong, kiểm tra:

1. ✅ Database đã có các bảng
2. ✅ Có thể đăng nhập với tài khoản mẫu
3. ✅ Trang chủ hiển thị được
4. ✅ Admin có thể truy cập dashboard
5. ✅ User có thể xem dịch vụ và sản phẩm

## 📝 Ghi chú

- File `.env` chứa thông tin nhạy cảm, không commit lên Git
- File `.env.example` là template, có thể commit
- Môi trường production cần set `APP_DEBUG=false`
- Nên sử dụng HTTPS trong production

## 🔄 Cập nhật dự án

Khi có thay đổi code:

1. Pull code mới (nếu dùng Git)
2. Chạy `composer install` (nếu có thay đổi dependencies)
3. Chạy `php artisan migrate` (nếu có migrations mới)
4. Xóa cache: `php artisan cache:clear`

## 📞 Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
- File log: `storage/logs/laravel.log`
- Laravel documentation: https://laravel.com/docs
- Stack Overflow với tag `laravel`

---

**Chúc bạn thành công! 🎉**

