<nav class="navbar navbar-default top-navbar" role="navigation">
    <div class="navbar-header">

        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!-- Branding Image -->
        <a class="navbar-brand" href="{{ url('/dashboard') }}">
            <strong>Accounting</strong>
        </a>
    </div>

    <!-- Right Side Of Navbar -->
    <ul class="nav navbar-top-links navbar-right">
        <!-- Authentication Links -->
        @if (Auth::guest())
            <li><a href="{{ url('/login') }}">Login</a></li>
        @else
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                </ul>
            </li>
        @endif
    </ul>

</nav>

<nav class="navbar-default navbar-side" role="navigation">
    <div id="sideNav" href=""><i class="fa fa-caret-right"></i></div>
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">

            <li>
                <a class="{{ Request::path() == 'dashboard' ? 'active-menu' : '' }}" href="{{ route('dashboard_path') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
            <li>
                <a class="{{ Request::path() == 'clients' ? 'active-menu' : '' }}" href="{{ route('clients_path') }}"><i class="fa fa-group"></i> Clients</a>
            </li>
            <li>
                <a class="{{ Request::path() == 'suppliers' ? 'active-menu' : '' }}" href="{{ route('suppliers_path') }}"><i class="fa fa-truck"></i> Suppliers</a>
            </li>
			<li>
                <a class="{{ Request::path() == 'invoices' ? 'active-menu' : '' }}" href="{{ route('invoices_path') }}"><i class="fa fa-file-excel-o"></i> Invoices</a>
            </li>
            <li>
                <a class="{{ Request::path() == 'transactions' ? 'active-menu' : '' }}" href="{{ route('transactions_path') }}"><i class="fa fa-list-alt"></i> Transactions</a>
            </li>
            
        </ul>

    </div>

</nav>
<!-- /. NAV SIDE  -->