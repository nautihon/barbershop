# Website Barbershop - Hệ thống quản lý tiệm cắt tóc

## 📋 Mô tả dự án

Website Barbershop là hệ thống quản lý toàn diện cho tiệm cắt tóc, hỗ trợ đặt lịch cắt tóc online, quản lý nhân viên, dịch vụ, sản phẩm và đơn hàng. Hệ thống được xây dựng với Laravel Framework, hỗ trợ 3 đối tượng người dùng: Admin, Staff (Nhân viên), và User (Khách hàng).

## 🎯 Mục tiêu dự án

- Xây dựng website phục vụ đặt lịch cắt tóc, lựa chọn thợ, mua sản phẩm chăm sóc tóc online
- Quản lý hiệu quả khách hàng, nhân viên và hoạt động kinh doanh
- Hệ thống phân quyền rõ ràng theo 3 đối tượng: Admin, Staff, User

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 10.x
- **Database**: MySQL
- **Frontend**: Blade Templates, Bootstrap 5
- **Authentication**: Session-based authentication với middleware phân quyền

## 📊 Cấu trúc Database

### Các bảng chính:

1. **users**: Thông tin người dùng (Admin, Staff, User)
   - id, name, email, password, role, phone, address

2. **services**: Dịch vụ cắt tóc
   - id, name, description, price, duration, image, is_active

3. **staffs**: Thông tin nhân viên/thợ cắt tóc
   - id, user_id, name, phone, email, specialization, bio, avatar, status

4. **staff_service**: Bảng trung gian (Many-to-Many)
   - staff_id, service_id

5. **staff_schedules**: Lịch làm việc của thợ
   - id, staff_id, day_of_week, start_time, end_time

6. **appointments**: Lịch hẹn cắt tóc
   - id, user_id, staff_id, service_id, appointment_date, appointment_time, status, notes

7. **products**: Sản phẩm chăm sóc tóc
   - id, name, description, price, stock, image, category, is_active

8. **orders**: Đơn hàng
   - id, user_id, total_amount, status, shipping_address, phone, notes

9. **order_items**: Chi tiết đơn hàng
   - id, order_id, product_id, quantity, price

10. **reviews**: Đánh giá dịch vụ và thợ
    - id, user_id, staff_id, service_id, appointment_id, rating, comment

## 👥 Phân quyền và chức năng

### 1. Admin (Chủ Barbershop)

#### Quản lý dịch vụ
- Thêm, sửa, xóa dịch vụ
- Quản lý giá và thời gian dịch vụ
- Upload hình ảnh dịch vụ

#### Quản lý nhân viên
- Thêm, sửa, xóa thông tin thợ
- Gán dịch vụ cho từng thợ
- Quản lý lịch làm việc của thợ

#### Quản lý lịch đặt
- Xem toàn bộ lịch đặt
- Xác nhận/hủy/đổi lịch
- Theo dõi trạng thái lịch hẹn

#### Quản lý sản phẩm
- Thêm, sửa, xóa sản phẩm
- Quản lý tồn kho và giá bán

#### Quản lý đơn hàng
- Xác nhận đơn hàng
- Cập nhật trạng thái giao hàng

#### Quản lý khách hàng
- Xem danh sách khách hàng
- Lịch sử đặt lịch và mua hàng

#### Thống kê & báo cáo
- Doanh thu theo ngày/tháng
- Số lượng lịch cắt, đơn hàng
- Dịch vụ và thợ được chọn nhiều nhất

### 2. Staff (Nhân viên/Thợ cắt tóc)

- Xem lịch làm việc cá nhân
- Xem danh sách khách hàng đã đặt lịch với mình
- Cập nhật trạng thái lịch cắt (đã hoàn thành)
- Xem thông tin dịch vụ mình đảm nhận

### 3. User (Khách hàng)

#### Đặt lịch cắt tóc
- Xem danh sách dịch vụ
- Xem danh sách thợ và thông tin chi tiết
- Chọn dịch vụ, thợ, ngày & khung giờ
- Quản lý lịch hẹn cá nhân (xem/hủy/đổi lịch)

#### Mua sản phẩm
- Xem danh sách sản phẩm
- Thêm sản phẩm vào giỏ hàng
- Đặt hàng và thanh toán
- Xem lịch sử đơn hàng

#### Đánh giá
- Đánh giá dịch vụ & thợ sau khi sử dụng

## 🚀 Cài đặt và chạy dự án

### Yêu cầu hệ thống:
- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (cho frontend assets)

### Các bước cài đặt:

1. **Clone repository**
```bash
git clone <repository-url>
cd barbershop
```

2. **Cài đặt dependencies**
```bash
composer install
npm install
```

3. **Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database trong file `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=barbershop
DB_USERNAME=root
DB_PASSWORD=
```

5. **Chạy migrations**
```bash
php artisan migrate
```

6. **Tạo storage link**
```bash
php artisan storage:link
```

7. **Chạy server**
```bash
php artisan serve
```

Truy cập: http://localhost:8000

## 📁 Cấu trúc thư mục

```
barbershop/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Controllers cho Admin
│   │   │   ├── Staff/        # Controllers cho Staff
│   │   │   ├── User/         # Controllers cho User
│   │   │   └── Auth/         # Authentication controllers
│   │   └── Middleware/       # Middleware phân quyền
│   └── Models/               # Eloquent Models
├── database/
│   └── migrations/           # Database migrations
├── resources/
│   └── views/
│       ├── layouts/          # Layout chung
│       ├── admin/            # Views cho Admin
│       ├── staff/            # Views cho Staff
│       ├── user/             # Views cho User
│       └── auth/             # Views đăng nhập/đăng ký
├── routes/
│   └── web.php              # Web routes
└── public/                   # Public assets
```

## 🔐 Authentication & Authorization

### Middleware phân quyền:
- `CheckRole`: Kiểm tra quyền truy cập dựa trên role
- Sử dụng: `Route::middleware(['auth', 'role:admin,staff'])->group(...)`

### Routes được bảo vệ:
- Admin routes: `/admin/*` - chỉ Admin
- Staff routes: `/staff/*` - chỉ Staff
- User routes: `/user/*` - chỉ User

## 🔄 Luồng hoạt động chính

### 1. Luồng đặt lịch:
1. User chọn dịch vụ
2. User chọn thợ (kiểm tra thợ có cung cấp dịch vụ không)
3. User chọn ngày & giờ (kiểm tra trùng lịch)
4. Hệ thống tạo appointment với status = 'pending'
5. Admin xác nhận → status = 'confirmed'
6. Staff hoàn thành → status = 'completed'
7. User có thể đánh giá sau khi hoàn thành

### 2. Luồng mua hàng:
1. User xem sản phẩm
2. User thêm vào giỏ hàng (session)
3. User điền thông tin giao hàng
4. Hệ thống tạo Order và OrderItems
5. Admin xác nhận đơn hàng
6. Admin cập nhật trạng thái giao hàng

## 📝 Validation Rules

### Đặt lịch:
- Service phải tồn tại và active
- Staff phải tồn tại và active
- Staff phải cung cấp service đã chọn
- Ngày hẹn phải sau ngày hiện tại
- Không được trùng lịch với appointment khác

### Đặt hàng:
- Sản phẩm phải tồn tại và active
- Số lượng không vượt quá tồn kho
- Thông tin giao hàng bắt buộc

## 🎨 Giao diện

- Sử dụng Bootstrap 5 cho responsive design
- Bootstrap Icons cho icons
- Layout chung với navigation bar
- Card-based design cho các components

## 🔮 Hướng mở rộng trong tương lai

1. **Thanh toán online**
   - Tích hợp VNPay, MoMo, PayPal
   - Thanh toán trước khi đặt lịch

2. **Thông báo real-time**
   - WebSocket cho thông báo đặt lịch
   - Email/SMS notification

3. **Mobile App**
   - React Native hoặc Flutter
   - API backend cho mobile

4. **Tích hợp AI**
   - Gợi ý thợ phù hợp dựa trên lịch sử
   - Chatbot hỗ trợ khách hàng

5. **Loyalty Program**
   - Tích điểm cho khách hàng
   - Voucher và khuyến mãi

6. **Analytics nâng cao**
   - Dashboard với charts
   - Báo cáo chi tiết theo nhiều tiêu chí

7. **Multi-language**
   - Hỗ trợ đa ngôn ngữ (Tiếng Việt, English)

8. **Booking Calendar**
   - Calendar view cho lịch hẹn
   - Drag & drop để đổi lịch

## 👨‍💻 Tác giả

Dự án được phát triển bởi Full-stack Developer

## 📄 License

MIT License

## 📞 Liên hệ

Nếu có thắc mắc hoặc đề xuất, vui lòng tạo issue trên repository.
