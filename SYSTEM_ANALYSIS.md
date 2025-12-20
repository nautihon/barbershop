# Phân tích Hệ thống Website Barbershop

## 1. Phân tích yêu cầu hệ thống

### 1.1. Mục tiêu dự án
Hệ thống Website Barbershop được xây dựng nhằm:
- Số hóa quy trình đặt lịch cắt tóc, giảm thiểu thời gian chờ đợi
- Quản lý tập trung thông tin khách hàng, nhân viên, dịch vụ
- Tăng doanh thu thông qua bán sản phẩm online
- Nâng cao trải nghiệm khách hàng với giao diện hiện đại, dễ sử dụng

### 1.2. Đối tượng sử dụng

#### Admin (Chủ Barbershop)
- **Mục đích**: Quản lý toàn bộ hoạt động kinh doanh
- **Quyền hạn**: Toàn quyền truy cập và chỉnh sửa hệ thống
- **Nhu cầu**: 
  - Theo dõi doanh thu, thống kê
  - Quản lý nhân sự và dịch vụ
  - Xử lý đơn hàng và lịch hẹn

#### Staff (Nhân viên/Thợ cắt tóc)
- **Mục đích**: Quản lý lịch làm việc cá nhân
- **Quyền hạn**: Xem và cập nhật lịch hẹn của mình
- **Nhu cầu**:
  - Xem lịch làm việc trong ngày/tuần
  - Cập nhật trạng thái hoàn thành dịch vụ
  - Xem thông tin khách hàng

#### User (Khách hàng)
- **Mục đích**: Đặt lịch và mua sản phẩm
- **Quyền hạn**: Quản lý lịch hẹn và đơn hàng của mình
- **Nhu cầu**:
  - Đặt lịch cắt tóc nhanh chóng
  - Mua sản phẩm chăm sóc tóc
  - Theo dõi lịch sử sử dụng dịch vụ

## 2. Mô tả chi tiết các bảng

### 2.1. Bảng `users`
**Mục đích**: Lưu trữ thông tin tất cả người dùng hệ thống

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key, tự động tăng |
| name | varchar(255) | Họ tên người dùng |
| email | varchar(255) | Email (unique) |
| password | varchar(255) | Mật khẩu (hashed) |
| role | enum('admin','staff','user') | Vai trò trong hệ thống |
| phone | varchar(20) | Số điện thoại |
| address | text | Địa chỉ |
| email_verified_at | timestamp | Thời gian xác thực email |
| remember_token | varchar(100) | Token ghi nhớ đăng nhập |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `hasOne` Staff (nếu role = 'staff')
- `hasMany` Appointments
- `hasMany` Orders
- `hasMany` Reviews

### 2.2. Bảng `services`
**Mục đích**: Quản lý các dịch vụ cắt tóc

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| name | varchar(255) | Tên dịch vụ |
| description | text | Mô tả chi tiết |
| price | decimal(10,2) | Giá dịch vụ (VNĐ) |
| duration | integer | Thời gian thực hiện (phút) |
| image | varchar(255) | Đường dẫn hình ảnh |
| is_active | boolean | Trạng thái hoạt động |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsToMany` Staffs (through staff_service)
- `hasMany` Appointments
- `hasMany` Reviews

### 2.3. Bảng `staffs`
**Mục đích**: Thông tin chi tiết về nhân viên/thợ cắt tóc

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key → users.id |
| name | varchar(255) | Tên thợ |
| phone | varchar(20) | Số điện thoại |
| email | varchar(255) | Email (unique) |
| specialization | text | Chuyên môn |
| bio | text | Tiểu sử |
| avatar | varchar(255) | Ảnh đại diện |
| status | enum('active','inactive') | Trạng thái làm việc |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsTo` User
- `belongsToMany` Services (through staff_service)
- `hasMany` StaffSchedules
- `hasMany` Appointments
- `hasMany` Reviews

### 2.4. Bảng `staff_service` (Pivot)
**Mục đích**: Quan hệ nhiều-nhiều giữa Staff và Service

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| staff_id | bigint | Foreign key → staffs.id |
| service_id | bigint | Foreign key → services.id |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Unique constraint**: (staff_id, service_id)

### 2.5. Bảng `staff_schedules`
**Mục đích**: Lịch làm việc của từng thợ

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| staff_id | bigint | Foreign key → staffs.id |
| day_of_week | integer | Thứ trong tuần (0=CN, 1=T2, ..., 6=T7) |
| start_time | time | Giờ bắt đầu |
| end_time | time | Giờ kết thúc |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsTo` Staff

### 2.6. Bảng `appointments`
**Mục đích**: Lịch hẹn cắt tóc của khách hàng

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key → users.id |
| staff_id | bigint | Foreign key → staffs.id |
| service_id | bigint | Foreign key → services.id |
| appointment_date | date | Ngày hẹn |
| appointment_time | time | Giờ hẹn |
| status | enum('pending','confirmed','completed','cancelled') | Trạng thái |
| notes | text | Ghi chú |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsTo` User
- `belongsTo` Staff
- `belongsTo` Service
- `hasOne` Review

**Business Rules**:
- Không được trùng lịch (staff_id + appointment_date + appointment_time)
- Chỉ có thể hủy/đổi lịch khi status = 'pending'
- User chỉ có thể đánh giá khi status = 'completed'

### 2.7. Bảng `products`
**Mục đích**: Sản phẩm chăm sóc tóc

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| name | varchar(255) | Tên sản phẩm |
| description | text | Mô tả |
| price | decimal(10,2) | Giá bán (VNĐ) |
| stock | integer | Số lượng tồn kho |
| image | varchar(255) | Hình ảnh |
| category | varchar(255) | Danh mục |
| is_active | boolean | Trạng thái |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `hasMany` OrderItems

### 2.8. Bảng `orders`
**Mục đích**: Đơn hàng mua sản phẩm

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key → users.id |
| total_amount | decimal(10,2) | Tổng tiền |
| status | enum('pending','confirmed','processing','shipped','delivered','cancelled') | Trạng thái |
| shipping_address | varchar(255) | Địa chỉ giao hàng |
| phone | varchar(20) | Số điện thoại |
| notes | text | Ghi chú |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsTo` User
- `hasMany` OrderItems

### 2.9. Bảng `order_items`
**Mục đích**: Chi tiết đơn hàng

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| order_id | bigint | Foreign key → orders.id |
| product_id | bigint | Foreign key → products.id |
| quantity | integer | Số lượng |
| price | decimal(10,2) | Giá tại thời điểm mua |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsTo` Order
- `belongsTo` Product

**Business Rules**:
- Price lưu giá tại thời điểm mua (không thay đổi khi product.price thay đổi)
- Khi tạo order, phải giảm stock của product

### 2.10. Bảng `reviews`
**Mục đích**: Đánh giá dịch vụ và thợ

| Cột | Kiểu dữ liệu | Mô tả |
|-----|--------------|-------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key → users.id |
| staff_id | bigint | Foreign key → staffs.id (nullable) |
| service_id | bigint | Foreign key → services.id (nullable) |
| appointment_id | bigint | Foreign key → appointments.id (nullable) |
| rating | integer | Điểm đánh giá (1-5) |
| comment | text | Nhận xét |
| created_at | timestamp | Thời gian tạo |
| updated_at | timestamp | Thời gian cập nhật |

**Relationships**:
- `belongsTo` User
- `belongsTo` Staff
- `belongsTo` Service
- `belongsTo` Appointment

**Business Rules**:
- User chỉ có thể đánh giá sau khi appointment status = 'completed'
- Mỗi appointment chỉ có thể có 1 review

## 3. Luồng hoạt động chính

### 3.1. Luồng đặt lịch cắt tóc

```
1. User truy cập trang chủ
   ↓
2. Chọn dịch vụ muốn sử dụng
   ↓
3. Hệ thống hiển thị danh sách thợ cung cấp dịch vụ đó
   ↓
4. User chọn thợ
   ↓
5. User chọn ngày và giờ hẹn
   ↓
6. Hệ thống kiểm tra:
   - Thợ có cung cấp dịch vụ không?
   - Ngày hẹn có hợp lệ không? (sau ngày hiện tại)
   - Khung giờ có trùng với lịch khác không?
   - Thợ có làm việc vào thời gian đó không?
   ↓
7. Nếu hợp lệ → Tạo appointment với status = 'pending'
   ↓
8. Admin xem và xác nhận → status = 'confirmed'
   ↓
9. Đến ngày hẹn, Staff hoàn thành → status = 'completed'
   ↓
10. User có thể đánh giá dịch vụ và thợ
```

### 3.2. Luồng mua hàng online

```
1. User xem danh sách sản phẩm
   ↓
2. User thêm sản phẩm vào giỏ hàng (lưu trong session)
   ↓
3. User xem giỏ hàng và điều chỉnh số lượng
   ↓
4. User điền thông tin giao hàng và đặt hàng
   ↓
5. Hệ thống kiểm tra:
   - Sản phẩm còn tồn kho không?
   - Số lượng có vượt quá tồn kho không?
   ↓
6. Nếu hợp lệ:
   - Tạo Order với status = 'pending'
   - Tạo OrderItems
   - Giảm stock của các sản phẩm
   - Xóa giỏ hàng (session)
   ↓
7. Admin xác nhận đơn hàng → status = 'confirmed'
   ↓
8. Admin cập nhật trạng thái:
   - 'processing' → Đang xử lý
   - 'shipped' → Đã giao hàng
   - 'delivered' → Đã nhận hàng
```

### 3.3. Luồng quản lý nhân viên (Admin)

```
1. Admin tạo tài khoản User với role = 'staff'
   ↓
2. Admin tạo Staff record liên kết với User
   ↓
3. Admin gán dịch vụ cho Staff (many-to-many)
   ↓
4. Admin thiết lập lịch làm việc cho Staff
   ↓
5. Staff đăng nhập và xem lịch làm việc
   ↓
6. Staff cập nhật trạng thái appointment khi hoàn thành
```

## 4. Validation Rules

### 4.1. Đặt lịch
- `service_id`: required, exists:services,id
- `staff_id`: required, exists:staffs,id
- `appointment_date`: required, date, after:today
- `appointment_time`: required
- Custom validation: Staff phải cung cấp service
- Custom validation: Không trùng lịch

### 4.2. Đặt hàng
- `shipping_address`: required, string
- `phone`: required, string, max:20
- Custom validation: Giỏ hàng không rỗng
- Custom validation: Sản phẩm còn tồn kho

### 4.3. Tạo Staff
- `name`: required, string, max:255
- `email`: required, email, unique:staffs,email, unique:users,email
- `phone`: required, string, max:20
- `status`: required, in:active,inactive
- `password`: required, string, min:8

## 5. Security Considerations

1. **Authentication**: Session-based với CSRF protection
2. **Authorization**: Middleware CheckRole kiểm tra quyền truy cập
3. **Password**: Bcrypt hashing
4. **Input Validation**: Validate tất cả input từ user
5. **SQL Injection**: Sử dụng Eloquent ORM (parameterized queries)
6. **XSS Protection**: Blade templates tự động escape
7. **File Upload**: Validate file type và size

## 6. Performance Optimization

1. **Database Indexing**: 
   - Index trên các foreign keys
   - Index trên email, role trong users
   - Index trên status trong appointments, orders

2. **Eager Loading**: 
   - Sử dụng `with()` để tránh N+1 query problem

3. **Pagination**: 
   - Sử dụng pagination cho danh sách dài

4. **Caching**: 
   - Cache danh sách services, staffs (có thể implement sau)

## 7. Error Handling

1. **404 Not Found**: Khi resource không tồn tại
2. **403 Forbidden**: Khi không có quyền truy cập
3. **422 Validation Error**: Khi validation fail
4. **500 Server Error**: Lỗi hệ thống (log và thông báo user-friendly)

## 8. Testing Strategy (Đề xuất)

1. **Unit Tests**: Test Models và Relationships
2. **Feature Tests**: Test các chức năng chính
3. **Integration Tests**: Test luồng hoạt động end-to-end

## 9. Deployment Considerations

1. **Environment Variables**: Cấu hình database, mail, etc.
2. **Storage**: Cấu hình storage cho file uploads
3. **Database Migration**: Chạy migrations trên production
4. **Seeding**: Tạo dữ liệu mẫu (optional)

## 10. Maintenance & Support

1. **Logging**: Laravel log system
2. **Backup**: Database backup định kỳ
3. **Monitoring**: Monitor performance và errors
4. **Updates**: Cập nhật dependencies định kỳ

