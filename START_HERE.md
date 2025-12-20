# 🎯 BẮT ĐẦU TẠI ĐÂY - Hướng dẫn chạy dự án Barbershop

## 📝 Tóm tắt nhanh (5 bước)

### 1️⃣ Cấu hình Database
Mở file `.env` và cập nhật:
```env
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=        # Điền mật khẩu MySQL nếu có
```

### 2️⃣ Tạo Database
Tạo database tên `barbershop` trong MySQL (dùng phpMyAdmin hoặc MySQL command line)

### 3️⃣ Chạy Migrations & Seeder
```bash
php artisan migrate --seed
```

### 4️⃣ Tạo Storage Link
```bash
php artisan storage:link
```

### 5️⃣ Khởi động Server
```bash
php artisan serve
```

Truy cập: **http://localhost:8000**

**Hoặc dùng script:** Double-click `start.bat`

**Tắt server:** Nhấn `Ctrl + C` hoặc double-click `stop.bat`

---

## 🔑 Tài khoản mẫu

| Vai trò | Email | Password |
|---------|-------|----------|
| **Admin** | admin@barbershop.com | password |
| **Staff** | staff@barbershop.com | password |
| **User** | user@example.com | password |

---

## 📚 Tài liệu chi tiết

- **Hướng dẫn đầy đủ**: Xem file `HUONG_DAN_CHAY_DU_AN.md`
- **Hướng dẫn nhanh**: Xem file `BAT_DAU_NHANH.md`
- **Phân tích hệ thống**: Xem file `SYSTEM_ANALYSIS.md`
- **README**: Xem file `README.md`

---

## ⚠️ Lỗi thường gặp

**"Access denied for user"** → Kiểm tra lại username/password trong `.env`

**"Unknown database"** → Tạo database `barbershop` trước

**"Class not found"** → Chạy `composer dump-autoload`

---

**Bắt đầu từ đây! 🚀**

