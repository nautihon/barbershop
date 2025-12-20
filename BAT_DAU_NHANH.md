# 🚀 Hướng dẫn nhanh - Bắt đầu dự án Barbershop

## ⚡ Các bước thực hiện (theo thứ tự)

### BƯỚC 1: Cấu hình Database trong file .env

1. Mở file `.env` (nằm ở thư mục gốc của dự án)
2. Tìm các dòng sau và cập nhật:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=
```

**Lưu ý quan trọng:**
- `DB_DATABASE=barbershop` - Tên database bạn sẽ tạo
- `DB_USERNAME=root` - Thay bằng username MySQL của bạn nếu khác
- `DB_PASSWORD=` - Điền mật khẩu MySQL nếu có (ví dụ: `DB_PASSWORD=yourpassword`)

### BƯỚC 2: Tạo Database

**Cách 1: Dùng MySQL Command Line**
```bash
# Mở Command Prompt hoặc PowerShell
mysql -u root -p
# Nhập mật khẩu MySQL (nếu có)

# Trong MySQL, chạy:
CREATE DATABASE barbershop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Cách 2: Dùng phpMyAdmin**
1. Mở trình duyệt, truy cập: `http://localhost/phpmyadmin`
2. Click "New" (Tạo mới) ở sidebar trái
3. Đặt tên database: `barbershop`
4. Chọn Collation: `utf8mb4_unicode_ci`
5. Click "Create"

**Cách 3: Dùng MySQL Workbench hoặc HeidiSQL**
- Tạo database mới tên `barbershop`
- Chọn charset: `utf8mb4`
- Chọn collation: `utf8mb4_unicode_ci`

### BƯỚC 3: Chạy Migrations

Mở terminal/command prompt trong thư mục dự án và chạy:

```bash
php artisan migrate
```

**Kết quả mong đợi:**
```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_reset_tokens_table
...
```

Nếu có lỗi, xem phần "Xử lý lỗi" bên dưới.

### BƯỚC 4: Tạo dữ liệu mẫu (Seeder)

```bash
php artisan db:seed
```

Hoặc chạy cả migrate và seed cùng lúc:

```bash
php artisan migrate --seed
```

**Kết quả:**
```
Seeding: DatabaseSeeder
Database seeded successfully!
Admin: admin@barbershop.com / password
Staff: staff@barbershop.com / password
User: user@example.com / password
```

### BƯỚC 5: Tạo Storage Link

```bash
php artisan storage:link
```

**Kết quả:**
```
The [public/storage] link has been connected to [storage/app/public].
```

### BƯỚC 6: Khởi động Server

```bash
php artisan serve
```

**Kết quả:**
```
INFO  Server running on [http://127.0.0.1:8000]
```

### BƯỚC 7: Mở trình duyệt

Truy cập: **http://localhost:8000**

---

## 🔐 Đăng nhập với tài khoản mẫu

### Admin Dashboard
1. Vào: http://localhost:8000/login
2. Email: `admin@barbershop.com`
3. Password: `password`
4. Sau khi đăng nhập → Tự động chuyển đến Admin Dashboard

### Staff Dashboard  
1. Email: `staff@barbershop.com`
2. Password: `password`

### User (Khách hàng)
1. Email: `user@example.com`
2. Password: `password`

---

## ⚠️ Xử lý lỗi thường gặp

### ❌ Lỗi: "Access denied for user 'root'@'localhost'"

**Nguyên nhân:** 
- Sai username/password MySQL
- MySQL chưa chạy
- Database chưa được tạo

**Giải pháp:**
1. Kiểm tra MySQL đã chạy chưa (Services → MySQL)
2. Kiểm tra lại file `.env`:
   ```env
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```
3. Tạo database `barbershop` (xem Bước 2)
4. Thử kết nối bằng MySQL client để xác nhận

### ❌ Lỗi: "Unknown database 'barbershop'"

**Nguyên nhân:** Database chưa được tạo

**Giải pháp:**
- Tạo database `barbershop` (xem Bước 2)

### ❌ Lỗi: "Class 'App\Models\...' not found"

**Giải pháp:**
```bash
composer dump-autoload
```

### ❌ Lỗi: "The stream or file could not be opened"

**Giải pháp:**
- Kiểm tra quyền thư mục `storage` và `bootstrap/cache`
- Đảm bảo có quyền ghi file

### ❌ Lỗi: "No application encryption key has been specified"

**Giải pháp:**
```bash
php artisan key:generate
```

---

## ✅ Checklist hoàn thành

Sau khi chạy xong, kiểm tra:

- [ ] File `.env` đã được cấu hình đúng
- [ ] Database `barbershop` đã được tạo
- [ ] Migrations đã chạy thành công (không có lỗi)
- [ ] Seeder đã chạy (có thông báo "Database seeded successfully")
- [ ] Storage link đã tạo
- [ ] Server đang chạy tại http://localhost:8000
- [ ] Có thể truy cập trang chủ
- [ ] Có thể đăng nhập với tài khoản admin

---

## 🎯 Test nhanh hệ thống

1. **Test đăng nhập Admin:**
   - Vào http://localhost:8000/login
   - Đăng nhập với `admin@barbershop.com` / `password`
   - Kiểm tra có thấy Admin Dashboard

2. **Test đăng ký User mới:**
   - Vào http://localhost:8000/register
   - Đăng ký tài khoản mới
   - Kiểm tra có thể đăng nhập

3. **Test xem dịch vụ:**
   - Đăng nhập với user
   - Vào trang chủ
   - Kiểm tra có thấy danh sách dịch vụ

---

## 📞 Cần hỗ trợ?

1. Kiểm tra file log: `storage/logs/laravel.log`
2. Chạy lệnh kiểm tra: `php artisan about`
3. Xem tài liệu Laravel: https://laravel.com/docs/10.x

---

**Chúc bạn thành công! 🎉**

