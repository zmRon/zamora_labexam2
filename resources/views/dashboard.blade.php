<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="page-container">
        <div class="page-inner">
            <div class="card">
                <div class="card-body">
                    <h3 class="dashboard-greeting">Welcome back, {{ Auth::user()->name }}!</h3>

                    <div class="dashboard-grid">
                        <a href="{{ route('products.index') }}" class="dashboard-card dashboard-card-rice">
                            <h4>Rice Menu</h4>
                        </a>

                        <a href="{{ route('orders.index') }}" class="dashboard-card dashboard-card-orders">
                            <h4>Orders</h4>
                        </a>

                        <a href="{{ route('payments.index') }}" class="dashboard-card dashboard-card-payments">
                            <h4>Payments</h4>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
