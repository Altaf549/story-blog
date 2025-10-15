<div class="col-md-3 col-lg-2 d-md-block sidebar">
    <div class="position-sticky pt-3">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    Categories
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.stories.index') }}" class="nav-link {{ request()->routeIs('admin.stories.*') ? 'active' : '' }}">
                    Stories
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.privacy.index') }}" class="nav-link {{ request()->routeIs('admin.privacy.*') ? 'active' : '' }}">
                    Privacy Policy
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.terms.index') }}" class="nav-link {{ request()->routeIs('admin.terms.*') ? 'active' : '' }}">
                    Terms & Conditions
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.about.index') }}" class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                    About Us
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.contact.index') }}" class="nav-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
                    Contact Us
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    Banners
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    Users
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>
