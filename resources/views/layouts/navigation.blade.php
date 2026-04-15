<nav class="navbar">
    <div class="navbar-inner">
        <div class="navbar-left">
            <a href="{{ route('dashboard') }}" class="navbar-brand">{{ config('app.name') }}</a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Rice Menu</a>
                <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Orders</a>
                <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">Payments</a>
            </div>
        </div>

        <div class="navbar-right">
            <span class="nav-username">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>

        <button class="nav-toggle" onclick="document.getElementById('nav-mobile').classList.toggle('open')">☰</button>
    </div>

    <div id="nav-mobile" class="nav-mobile">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Rice Menu</a>
        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Orders</a>
        <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">Payments</a>
        <div class="nav-mobile-user">
            <span>{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>
    </div>
</nav>
