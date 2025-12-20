<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $imagePath = null;
        
        // Xử lý hình ảnh nếu có URL hoặc đường dẫn
        if (!empty($row['hinh_anh']) || !empty($row['image'])) {
            $imageUrl = $row['hinh_anh'] ?? $row['image'] ?? null;
            
            if ($imageUrl) {
                // Nếu là URL, tải về
                if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                    try {
                        $imageContent = file_get_contents($imageUrl);
                        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                        $filename = 'products/' . Str::random(40) . '.' . $extension;
                        Storage::disk('public')->put($filename, $imageContent);
                        $imagePath = $filename;
                    } catch (\Exception $e) {
                        // Nếu không tải được, bỏ qua
                    }
                } elseif (file_exists($imageUrl)) {
                    // Nếu là đường dẫn local
                    $extension = pathinfo($imageUrl, PATHINFO_EXTENSION);
                    $filename = 'products/' . Str::random(40) . '.' . $extension;
                    Storage::disk('public')->put($filename, file_get_contents($imageUrl));
                    $imagePath = $filename;
                }
            }
        }

        return new Product([
            'name' => $row['ten_san_pham'] ?? $row['name'] ?? '',
            'price' => floatval($row['gia_tien'] ?? $row['price'] ?? 0),
            'stock' => intval($row['so_luong'] ?? $row['stock'] ?? 0),
            'category' => $row['danh_muc'] ?? $row['category'] ?? null,
            'description' => $row['mo_ta'] ?? $row['description'] ?? null,
            'image' => $imagePath,
            'is_active' => true,
        ]);
    }

    public function rules(): array
    {
        return [
            'ten_san_pham' => 'required',
            'gia_tien' => 'required|numeric|min:0',
            'so_luong' => 'required|integer|min:0',
        ];
    }
}
