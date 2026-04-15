<x-app-layout>
    <x-slot name="header">
        @if($mode === 'create')
            <h2 class="page-title">Add New Rice Product</h2>
        @elseif($mode === 'edit')
            <h2 class="page-title">Edit Rice Product</h2>
        @else
            <div class="header-flex">
                <h2 class="page-title">Rice Menu</h2>
                <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
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
                        @if($products->count() > 0)
                            <div class="table-wrap">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Rice Name</th>
                                            <th>Price/kg</th>
                                            <th>Stock (kg)</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $i => $product)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td class="font-medium">{{ $product->name }}</td>
                                                <td>₱{{ number_format($product->price_per_kg, 2) }}</td>
                                                <td>{{ $product->stock_quantity }}</td>
                                                <td class="wrap">{{ $product->description ?? '—' }}</td>
                                                <td>
                                                    <a href="{{ route('products.edit', $product) }}" class="action-link">Edit</a>
                                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this product?')">
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
                            <p class="empty-state">No products yet. Click "Add Product" to get started.</p>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($mode === 'create')
            <div class="page-inner-narrow">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('products.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name" class="form-label">Rice Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" required autofocus placeholder="e.g. Jasmine, Brown Rice, Dinorado">
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="price_per_kg" class="form-label">Price per kg (₱)</label>
                                <input id="price_per_kg" type="number" step="0.01" min="0" name="price_per_kg" value="{{ old('price_per_kg') }}" class="form-input" required placeholder="0.00">
                                @error('price_per_kg') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="stock_quantity" class="form-label">Stock (kg)</label>
                                <input id="stock_quantity" type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity') }}" class="form-input" required placeholder="0">
                                @error('stock_quantity') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group-last">
                                <label for="description" class="form-label">Description <span style="color:#9ca3af">(optional)</span></label>
                                <textarea id="description" name="description" rows="3" class="form-textarea" placeholder="Short description...">{{ old('description') }}</textarea>
                                @error('description') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @elseif($mode === 'edit')
            <div class="page-inner-narrow">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('products.update', $product) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name" class="form-label">Rice Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required autofocus>
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="price_per_kg" class="form-label">Price per kg (₱)</label>
                                <input id="price_per_kg" type="number" step="0.01" min="0" name="price_per_kg" value="{{ old('price_per_kg', $product->price_per_kg) }}" class="form-input" required>
                                @error('price_per_kg') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label for="stock_quantity" class="form-label">Stock (kg)</label>
                                <input id="stock_quantity" type="number" min="0" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="form-input" required>
                                @error('stock_quantity') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group-last">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" rows="3" class="form-textarea">{{ old('description', $product->description) }}</textarea>
                                @error('description') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
