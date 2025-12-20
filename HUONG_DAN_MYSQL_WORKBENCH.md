# 🗄️ Hướng dẫn sử dụng MySQL Workbench cho dự án Barbershop

## 📋 Bước 1: Tạo Database trong MySQL Workbench

### 1.1. Kết nối MySQL Workbench
1. Mở **MySQL Workbench**
2. Kết nối đến MySQL server của bạn (thường là `localhost` hoặc `127.0.0.1`)
3. Nhập username và password (thường là `root` và mật khẩu của bạn)

### 1.2. Tạo Database mới
1. Trong MySQL Workbench, click vào biểu tượng **"Create a new schema"** (hoặc nhấn `Ctrl+Shift+N`)
   - Hoặc click chuột phải vào vùng trống → **Create Schema**
   
2. Trong cửa sổ **Create Schema**:
   - **Name**: Nhập `barbershop`
   - **Default Collation**: Chọn `utf8mb4` → `utf8mb4_unicode_ci`
   
3. Click **Apply** (hoặc `Ctrl+Enter`)

4. Xác nhận trong cửa sổ tiếp theo → Click **Apply**

✅ Database `barbershop` đã được tạo thành công!

---

## ⚙️ Bước 2: Cấu hình file .env

1. Mở file `.env` trong thư mục dự án (dùng Notepad++ hoặc VS Code)

2. Tìm và cập nhật các dòng sau:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here
```

**Lưu ý quan trọng:**
- `DB_DATABASE=barbershop` - Tên database vừa tạo
- `DB_USERNAME=root` - Username MySQL của bạn (có thể khác)
- `DB_PASSWORD=your_mysql_password_here` - **Điền mật khẩu MySQL của bạn vào đây**

3. Lưu file `.env`

---

## 🚀 Bước 3: Chạy Migrations

Mở **Command Prompt** hoặc **PowerShell** trong thư mục dự án và chạy:

```bash
php artisan migrate --seed
```

**Kết quả mong đợi:**
```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_reset_tokens_table
Migrated:  2014_10_12_100000_create_password_reset_tokens_table
...
Seeding: DatabaseSeeder
Database seeded successfully!
Admin: admin@barbershop.com / password
Staff: staff@barbershop.com / password
User: user@example.com / password
```

---

## ✅ Bước 4: Kiểm tra Database trong MySQL Workbench

1. Trong MySQL Workbench, refresh database list (click chuột phải → **Refresh All**)

2. Mở rộng database `barbershop` → Bạn sẽ thấy các bảng:
   - ✅ `users`
   - ✅ `services`
   - ✅ `staffs`
   - ✅ `staff_service`
   - ✅ `staff_schedules`
   - ✅ `appointments`
   - ✅ `products`
   - ✅ `orders`
   - ✅ `order_items`
   - ✅ `reviews`

3. Click vào bảng `users` → Click tab **Table Data** để xem dữ liệu mẫu

---

## 🔍 Kiểm tra dữ liệu mẫu

### Xem tài khoản Admin:
1. Mở bảng `users`
2. Tìm dòng có `email = 'admin@barbershop.com'`
3. Kiểm tra `role = 'admin'`

### Xem dịch vụ:
1. Mở bảng `services`
2. Bạn sẽ thấy 4 dịch vụ mẫu

### Xem sản phẩm:
1. Mở bảng `products`
2. Bạn sẽ thấy 3 sản phẩm mẫu

---

## 🛠️ Các lệnh hữu ích khác

### Xem tất cả bảng:
```sql
SHOW TABLES;
```

### Xem cấu trúc bảng:
```sql
DESCRIBE users;
```

### Xem dữ liệu trong bảng:
```sql
SELECT * FROM users;
```

### Xóa và tạo lại database (nếu cần):
```bash
# Trong MySQL Workbench, chạy:
DROP DATABASE IF EXISTS barbershop;
CREATE DATABASE barbershop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Sau đó chạy lại:
php artisan migrate --seed
```

---

## ⚠️ Xử lý lỗi

### Lỗi: "Access denied for user 'root'@'localhost'"

**Nguyên nhân:** Sai mật khẩu MySQL

**Giải pháp:**
1. Kiểm tra lại mật khẩu trong file `.env`
2. Thử kết nối lại MySQL Workbench với mật khẩu đó
3. Nếu quên mật khẩu, có thể reset hoặc tạo user mới

### Lỗi: "Unknown database 'barbershop'"

**Nguyên nhân:** Database chưa được tạo

**Giải pháp:**
- Tạo lại database `barbershop` trong MySQL Workbench (xem Bước 1)

### Lỗi: "Table already exists"

**Nguyên nhân:** Đã chạy migrations trước đó

**Giải pháp:**
```bash
# Xóa tất cả bảng và tạo lại:
php artisan migrate:fresh --seed
```

**⚠️ Cảnh báo:** Lệnh này sẽ xóa TẤT CẢ dữ liệu hiện có!

---

## 📊 Xem dữ liệu trong MySQL Workbench

### Cách 1: Dùng Table Data
1. Click vào database `barbershop`
2. Click vào bảng (ví dụ: `users`)
3. Click tab **Table Data** ở dưới
4. Xem dữ liệu trong bảng

### Cách 2: Dùng SQL Query
1. Click vào **File** → **New Query Tab** (hoặc `Ctrl+T`)
2. Gõ SQL:
```sql
USE barbershop;
SELECT * FROM users;
```
3. Click **Execute** (hoặc `Ctrl+Enter`)

---

## 🎯 Checklist hoàn thành

Sau khi hoàn thành, kiểm tra:

- [ ] Database `barbershop` đã được tạo trong MySQL Workbench
- [ ] File `.env` đã được cấu hình đúng (có password)
- [ ] Migrations đã chạy thành công (không có lỗi)
- [ ] Có thể thấy các bảng trong MySQL Workbench
- [ ] Có thể thấy dữ liệu mẫu trong bảng `users`, `services`, `products`
- [ ] Storage link đã tạo: `php artisan storage:link`
- [ ] Server đang chạy: `php artisan serve`

---

## 🚀 Bước tiếp theo

Sau khi hoàn thành các bước trên:

1. **Tạo Storage Link:**
   ```bash
   php artisan storage:link
   ```

2. **Khởi động Server:**
   ```bash
   php artisan serve
   ```

3. **Truy cập Website:**
   - Mở trình duyệt: http://localhost:8000
   - Đăng nhập với: `admin@barbershop.com` / `password`

---

**Chúc bạn thành công! 🎉**

