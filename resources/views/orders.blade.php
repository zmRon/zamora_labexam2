<x-app-layout>
    <x-slot name="header">
        @if($mode === 'create')
            <h2 class="page-title">New Order</h2>
        @elseif($mode === 'edit')
            <h2 class="page-title">Edit Order #{{ $order->id }}</h2>
        @elseif($mode === 'show')
            <h2 class="page-title">Order #{{ $order->id }}</h2>
        @else
            <div class="header-flex">
                <h2 class="page-title">Orders</h2>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">+ New Order</a>
            </div>
        @endif
    </x-slot>

    <div class="page-container">
        @if($mode === 'index')
            <div class="page-inner">
                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        @if($orders->count() > 0)
                            <div class="table-wrap">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Rice</th>
                                            <th>Qty (kg)</th>
                                            <th>Price/kg</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td class="font-medium">{{ $order->product->name }}</td>
                                                <td>{{ number_format($order->quantity, 2) }}</td>
                                                <td>₱{{ number_format($order->price_per_kg, 2) }}</td>
                                                <td class="font-semibold">₱{{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    @if($order->payment && $order->payment->status === 'Paid')
                                                        <span class="badge badge-paid">Paid</span>
                                                    @else
                                                        <span class="badge badge-unpaid">Unpaid</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order) }}" class="action-link">View</a>
                                                    <a href="{{ route('orders.edit', $order) }}" class="action-link">Edit</a>
                                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this order?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-delete">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="empty-state">No orders yet.</p>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($mode === 'create' || $mode === 'edit')
            <div class="page-inner-narrow">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ $mode === 'create' ? route('orders.store') : route('orders.update', $order) }}">
                            @csrf
                            @if($mode === 'edit')
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="product_id" class="form-label">Rice Product</label>
                                <select id="product_id" name="product_id" class="form-select" required onchange="updateSummary()">
                                    <option value="">-- Choose Rice --</option>
                                    @foreach($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-price="{{ $product->price_per_kg }}"
                                            data-stock="{{ $product->stock_quantity }}"
                                            {{ old('product_id', $mode === 'edit' ? $order->product_id : '') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} — ₱{{ number_format($product->price_per_kg, 2) }}/kg ({{ $product->stock_quantity }} kg left)
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="form-label">Quantity (kg)</label>
                                <input id="quantity" type="number" step="0.01" min="0.1" name="quantity"
                                    value="{{ old('quantity', $mode === 'edit' ? $order->quantity : '') }}"
                                    class="form-input" required placeholder="0.00" oninput="updateSummary()">
                                @error('quantity') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="order-summary">
                                <h3>Order Summary</h3>
                                <div class="summary-row">
                                    <span>Rice:</span>
                                    <span class="value" id="sumRice">—</span>
                                </div>
                                <div class="summary-row">
                                    <span>Price/kg:</span>
                                    <span class="value" id="sumPrice">₱0.00</span>
                                </div>
                                <div class="summary-row">
                                    <span>Quantity:</span>
                                    <span class="value" id="sumQty">0 kg</span>
                                </div>
                                <hr class="summary-divider">
                                <div class="summary-total">
                                    <span>Total:</span>
                                    <span class="value" id="sumTotal">₱0.00</span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    {{ $mode === 'create' ? 'Place Order' : 'Update Order' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function updateSummary() {
                    const sel = document.getElementById('product_id');
                    const qty = parseFloat(document.getElementById('quantity').value) || 0;
                    const opt = sel.options[sel.selectedIndex];

                    if (opt && opt.value) {
                        const price = parseFloat(opt.dataset.price) || 0;
                        const name = opt.text.split(' — ')[0];

                        document.getElementById('sumRice').textContent = name;
                        document.getElementById('sumPrice').textContent = '₱' + price.toFixed(2);
                        document.getElementById('sumQty').textContent = qty.toFixed(2) + ' kg';
                        document.getElementById('sumTotal').textContent = '₱' + (qty * price).toFixed(2);
                    } else {
                        document.getElementById('sumRice').textContent = '—';
                        document.getElementById('sumPrice').textContent = '₱0.00';
                        document.getElementById('sumQty').textContent = '0 kg';
                        document.getElementById('sumTotal').textContent = '₱0.00';
                    }
                }

                document.addEventListener('DOMContentLoaded', updateSummary);
            </script>

        @elseif($mode === 'show')
            <div class="page-inner-narrow">
                <div class="card">
                    <div class="card-body">
                        <div class="detail-grid">
                            <div>
                                <p class="detail-label">Order ID</p>
                                <p class="detail-value">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Date</p>
                                <p class="detail-value">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <hr class="detail-divider">

                        <div class="detail-grid">
                            <div>
                                <p class="detail-label">Rice</p>
                                <p class="detail-value">{{ $order->product->name }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Price/kg</p>
                                <p class="detail-value">₱{{ number_format($order->price_per_kg, 2) }}</p>
                            </div>
                        </div>

                        <div class="detail-grid">
                            <div>
                                <p class="detail-label">Quantity</p>
                                <p class="detail-value">{{ number_format($order->quantity, 2) }} kg</p>
                            </div>
                            <div>
                                <p class="detail-label">Total Amount</p>
                                <p class="detail-value-highlight">₱{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <hr class="detail-divider">

                        <div class="detail-grid">
                            <div>
                                <p class="detail-label">Payment Status</p>
                                @if($order->payment && $order->payment->status === 'Paid')
                                    <span class="badge badge-paid">Paid</span>
                                @else
                                    <span class="badge badge-unpaid">Unpaid</span>
                                @endif
                            </div>
                            <div>
                                <p class="detail-label">Customer</p>
                                <p class="detail-value">{{ $order->user->name }}</p>
                            </div>
                        </div>

                        <div class="detail-actions">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
                            @if($order->payment && $order->payment->status === 'Unpaid')
                                <form method="POST" action="{{ route('payments.pay', $order->payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
