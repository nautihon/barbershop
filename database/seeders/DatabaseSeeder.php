<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@barbershop.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0123456789',
        ]);

        // Create Services
        $services = [
            [
                'name' => 'Cắt tóc nam',
                'description' => 'Dịch vụ cắt tóc nam chuyên nghiệp',
                'price' => 50000,
                'duration' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Cắt tóc nữ',
                'description' => 'Dịch vụ cắt tóc nữ theo xu hướng',
                'price' => 80000,
                'duration' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Cạo râu',
                'description' => 'Dịch vụ cạo râu truyền thống',
                'price' => 30000,
                'duration' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Gội đầu',
                'description' => 'Gội đầu và massage da đầu',
                'price' => 40000,
                'duration' => 25,
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create($serviceData);
        }

        // Create Staff
        $staffUser = User::create([
            'name' => 'Nguyễn Văn A',
            'email' => 'staff@barbershop.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '0987654321',
        ]);

        $staff = Staff::create([
            'user_id' => $staffUser->id,
            'name' => 'Nguyễn Văn A',
            'email' => 'staff@barbershop.com',
            'phone' => '0987654321',
            'specialization' => 'Cắt tóc nam, Cạo râu',
            'bio' => 'Thợ cắt tóc với 5 năm kinh nghiệm',
            'status' => 'active',
        ]);

        // Assign services to staff
        $staff->services()->attach([1, 3, 4]); // Cắt tóc nam, Cạo râu, Gội đầu

        // Create Sample User
        $user = User::create([
            'name' => 'Khách hàng',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '0912345678',
        ]);

        // Create Products
        $products = [
            [
                'name' => 'Dầu gội đầu nam',
                'description' => 'Dầu gội đầu chuyên dụng cho nam giới',
                'price' => 150000,
                'stock' => 50,
                'category' => 'Chăm sóc tóc',
                'is_active' => true,
            ],
            [
                'name' => 'Sáp vuốt tóc',
                'description' => 'Sáp vuốt tóc cao cấp, giữ nếp lâu',
                'price' => 200000,
                'stock' => 30,
                'category' => 'Tạo kiểu',
                'is_active' => true,
            ],
            [
                'name' => 'Kem cạo râu',
                'description' => 'Kem cạo râu làm mềm da, giảm kích ứng',
                'price' => 120000,
                'stock' => 40,
                'category' => 'Chăm sóc râu',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@barbershop.com / password');
        $this->command->info('Staff: staff@barbershop.com / password');
        $this->command->info('User: user@example.com / password');
    }
}
