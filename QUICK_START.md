# ⚡ Quick Start - Chạy Website Barbershop

## 🎯 3 Bước nhanh nhất

### **Bước 1: Cài đặt (chỉ lần đầu)**
```bash
composer install
copy .env.example .env
php artisan key:generate
```

### **Bước 2: Cấu hình Database**
Mở file `.env` và sửa:
```env
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=your_password
```

### **Bước 3: Chạy**
```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

**Truy cập:** http://localhost:8000

---

## 🛑 Tắt Server

**Cách 1:** Nhấn `Ctrl + C` trong terminal

**Cách 2 (Windows):** Double-click file `stop.bat` hoặc chạy:
```bash
stop.bat
```

---

## 🚀 Hoặc dùng Script (Windows)

Chỉ cần double-click file `start.bat` hoặc chạy:
```bash
start.bat
```

---

## 📋 Tài khoản đăng nhập

Sau khi chạy `migrate --seed`:

- **Admin:** admin@barbershop.com / password
- **Staff:** staff@barbershop.com / password  
- **User:** user@example.com / password

---

## ⚠️ Nếu gặp lỗi

```bash
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan serve
```

---

---

## 📁 Các file script

- `start.bat` / `start.sh` → Chạy server
- `stop.bat` / `stop.sh` → Tắt server

---

Xem chi tiết tại: `HUONG_DAN_LENH.md`

