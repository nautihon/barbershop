@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mt-4">
    <h2>Giỏ hàng</h2>
    
    @php
        $total = $total ?? 0;
    @endphp
    
    @if(count($products) > 0)
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product['image'])
                                    <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                @endif
                                {{ $product['name'] }}
                            </td>
                            <td>{{ number_format($product['price']) }} VNĐ</td>
                            <td>
                                <form action="{{ route('user.cart.update', $product['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $product['quantity'] }}" min="1" max="{{ \App\Models\Product::find($product['id'])->stock }}" class="form-control d-inline" style="width: 80px;" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>{{ number_format($product['subtotal']) }} VNĐ</td>
                            <td>
                                <form action="{{ route('user.cart.remove', $product['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Tổng cộng:</th>
                            <th>{{ number_format($total) }} VNĐ</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkoutModal" onclick="updateModalTotal({{ $total }})">
                        Thanh toán
                    </button>
                    <a href="{{ route('user.products.index') }}" class="btn btn-secondary">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center">
                <p class="text-muted">Giỏ hàng của bạn đang trống.</p>
                <a href="{{ route('user.products.index') }}" class="btn btn-primary">Mua sắm ngay</a>
            </div>
        </div>
    @endif
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.orders.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thông tin giao hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Địa chỉ giao hàng</label>
                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if(auth()->user()->loyalty_points > 0)
                    <div class="mb-3">
                        <label for="loyalty_points" class="form-label">Sử dụng điểm tích lũy (Bạn có {{ number_format(auth()->user()->loyalty_points) }} điểm, 1 điểm = 100 VNĐ)</label>
                        <input type="number" class="form-control" id="loyalty_points" name="loyalty_points" 
                               value="0" min="0" max="{{ auth()->user()->loyalty_points }}" 
                               onchange="calculateDiscount()">
                        <small class="text-muted">Nhập 0 nếu không muốn sử dụng điểm</small>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="notes" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <div class="alert alert-info">
                        <div><strong>Tổng tiền:</strong> <span id="originalTotal">{{ number_format($total ?? 0) }}</span> VNĐ</div>
                        <div id="discountInfo" style="display: none;">
                            <strong>Giảm giá:</strong> -<span id="discountAmount">0</span> VNĐ
                        </div>
                        <div class="mt-2"><strong>Thành tiền:</strong> <span id="modalTotal">{{ number_format($total ?? 0) }}</span> VNĐ</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đặt hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateModalTotal(total) {
    document.getElementById('modalTotal').textContent = new Intl.NumberFormat('vi-VN').format(total);
    document.getElementById('originalTotal').textContent = new Intl.NumberFormat('vi-VN').format(total);
}

function calculateDiscount() {
    const loyaltyPoints = parseInt(document.getElementById('loyalty_points').value) || 0;
    const originalTotal = {{ $total ?? 0 }};
    const discountAmount = Math.min(loyaltyPoints * 100, originalTotal);
    const finalTotal = originalTotal - discountAmount;
    
    document.getElementById('discountAmount').textContent = new Intl.NumberFormat('vi-VN').format(discountAmount);
    document.getElementById('modalTotal').textContent = new Intl.NumberFormat('vi-VN').format(finalTotal);
    
    if (discountAmount > 0) {
        document.getElementById('discountInfo').style.display = 'block';
    } else {
        document.getElementById('discountInfo').style.display = 'none';
    }
}
</script>
@endsection

