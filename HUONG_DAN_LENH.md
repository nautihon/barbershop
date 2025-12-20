# 📋 Hướng dẫn các lệnh chạy Website Barbershop

## 🚀 Cách nhanh nhất - Sử dụng Script

### Windows:
```bash
start.bat
```

### Linux/Mac:
```bash
chmod +x start.sh
./start.sh
```

---

## 📝 Các lệnh thủ công

### 1. **Cài đặt lần đầu**

```bash
# Cài đặt dependencies PHP
composer install

# Cài đặt dependencies Node.js (nếu có)
npm install

# Copy file cấu hình
copy .env.example .env
# Hoặc trên Linux/Mac:
# cp .env.example .env

# Tạo key cho ứng dụng
php artisan key:generate

# Cấu hình database trong .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=barbershop
# DB_USERNAME=root
# DB_PASSWORD=

# Chạy migrations và seeders
php artisan migrate --seed

# Tạo symbolic link cho storage
php artisan storage:link
```

### 2. **Chạy server**

```bash
# Chạy development server
php artisan serve

# Chạy trên port khác (ví dụ: 8080)
php artisan serve --port=8080

# Chạy trên host và port cụ thể
php artisan serve --host=0.0.0.0 --port=8000
```

**Server sẽ chạy tại:** `http://localhost:8000`

### 2.1. **Tắt server**

**Cách 1: Nhấn Ctrl+C** (Cách đơn giản nhất)
- Trong terminal đang chạy server, nhấn `Ctrl + C`
- Server sẽ dừng ngay lập tức

**Cách 2: Dùng script (Windows)**
```bash
# Double-click file stop.bat
# Hoặc chạy:
stop.bat
```

**Cách 3: Dùng script (Linux/Mac)**
```bash
chmod +x stop.sh
./stop.sh
```

**Cách 4: Tắt thủ công (Windows)**
```bash
# Tìm process đang dùng port 8000
netstat -ano | findstr :8000

# Dừng process (thay PID bằng số process ID)
taskkill /F /PID <PID>
```

**Cách 5: Tắt thủ công (Linux/Mac)**
```bash
# Tìm process đang dùng port 8000
lsof -ti:8000

# Dừng process
kill -9 $(lsof -ti:8000)
```

### 3. **Clear Cache (Khi có lỗi)**

```bash
# Clear tất cả cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Hoặc clear tất cả cùng lúc
php artisan optimize:clear
```

### 4. **Database**

```bash
# Chạy migrations
php artisan migrate

# Chạy migrations và seeders
php artisan migrate --seed

# Reset database (XÓA TẤT CẢ DỮ LIỆU)
php artisan migrate:fresh --seed

# Tạo migration mới
php artisan make:migration create_table_name

# Tạo seeder
php artisan make:seeder SeederName
```

### 5. **Tạo Controller, Model, View**

```bash
# Tạo Controller
php artisan make:controller ControllerName

# Tạo Controller với resource (CRUD)
php artisan make:controller ControllerName --resource

# Tạo Model
php artisan make:model ModelName

# Tạo Model với migration
php artisan make:model ModelName -m

# Tạo Migration
php artisan make:migration migration_name
```

### 6. **Kiểm tra**

```bash
# Kiểm tra routes
php artisan route:list

# Kiểm tra routes theo tên
php artisan route:list --name=admin

# Kiểm tra cấu hình
php artisan config:show database

# Kiểm tra version Laravel
php artisan --version
```

### 7. **Troubleshooting**

```bash
# Nếu có lỗi permission (Linux/Mac)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Nếu có lỗi composer
composer dump-autoload

# Nếu có lỗi npm
npm cache clean --force
npm install

# Kiểm tra PHP version
php -v

# Kiểm tra MySQL
mysql --version
```

---

## 🔄 Quy trình chạy dự án hàng ngày

### **Lần đầu tiên:**
```bash
1. composer install
2. copy .env.example .env
3. Sửa .env (database, app name, etc.)
4. php artisan key:generate
5. php artisan migrate --seed
6. php artisan storage:link
7. php artisan serve
```

### **Các lần sau:**
```bash
1. php artisan serve
```

### **Khi có thay đổi code:**
```bash
1. php artisan optimize:clear
2. php artisan serve
```

### **Khi có thay đổi database:**
```bash
1. php artisan migrate
2. php artisan serve
```

---

## 🌐 Truy cập Website

Sau khi chạy `php artisan serve`, truy cập:

- **Trang chủ:** http://localhost:8000
- **Login:** http://localhost:8000/login
- **Register:** http://localhost:8000/register

### **Tài khoản mặc định (sau khi seed):**

**Admin:**
- Email: `admin@barbershop.com`
- Password: `password`

**Staff:**
- Email: `staff@barbershop.com`
- Password: `password`

**User:**
- Email: `user@example.com`
- Password: `password`

---

## ⚙️ Cấu hình .env quan trọng

```env
APP_NAME="Only 1 Men's Hair Design"
APP_ENV=local
APP_KEY=base64:... (tự động tạo khi chạy key:generate)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## 🛑 Dừng Server

### **Cách 1: Nhấn Ctrl+C** (Đơn giản nhất)
Trong terminal đang chạy server, nhấn `Ctrl + C` để dừng server ngay lập tức.

### **Cách 2: Dùng Script**
- **Windows:** Double-click `stop.bat` hoặc chạy `stop.bat`
- **Linux/Mac:** Chạy `chmod +x stop.sh && ./stop.sh`

### **Cách 3: Tắt thủ công (Windows)**
```bash
# Tìm process đang dùng port 8000
netstat -ano | findstr :8000

# Dừng process (thay <PID> bằng số process ID)
taskkill /F /PID <PID>
```

### **Cách 4: Tắt thủ công (Linux/Mac)**
```bash
# Tìm và dừng process đang dùng port 8000
kill -9 $(lsof -ti:8000)
```

---

## 📱 Chạy trên mạng local

Để truy cập từ thiết bị khác trong cùng mạng:

```bash
php artisan serve --host=0.0.0.0
```

Sau đó truy cập từ thiết bị khác bằng IP của máy:
- Ví dụ: `http://192.168.1.100:8000`

---

## 🔧 Lệnh hữu ích khác

```bash
# Xem tất cả artisan commands
php artisan list

# Tạo user mới (nếu có tinker)
php artisan tinker
>>> User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('password'), 'role' => 'user']);

# Backup database
php artisan db:backup

# Xem logs
tail -f storage/logs/laravel.log
```

---

## ⚠️ Lưu ý

1. **Luôn chạy migrations** khi có thay đổi database
2. **Clear cache** khi có lỗi không rõ nguyên nhân
3. **Kiểm tra .env** nếu có lỗi kết nối database
4. **Storage link** phải được tạo để upload file
5. **Composer install** khi clone project mới

---

## 🆘 Xử lý lỗi thường gặp

### Lỗi: "Class not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Lỗi: "Storage link not found"
```bash
php artisan storage:link
```

### Lỗi: "Database connection failed"
- Kiểm tra MySQL đang chạy
- Kiểm tra thông tin trong .env
- Kiểm tra database đã được tạo chưa

### Lỗi: "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

---

**Chúc bạn code vui vẻ! 🚀**

