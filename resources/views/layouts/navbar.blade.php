<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Erizon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('jenis-barang.index') ? 'active' : '' }}" href="{{ url('/jenis-barang') }}">Jenis Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('barang.index') ? 'active' : '' }}" href="{{ url('/barang') }}">Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('transaksi.index') ? 'active' : '' }}" href="{{ url('/transaksi') }}">Transaksi</a>
                </li>
                <li class="nav-item   dropdown">
                    <a class="nav-link {{ Route::is('searchTransaksi') || Route::is('filterTransaksi') ? 'active' : '' }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Report
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <li class="nav-item " ><a class="dropdown-item {{ Route::is('searchTransaksi') ? 'active' : '' }}" href="{{ url('/transaksi/searchTransaksi') }}">Result Transaksi</a></li>
                      <li class="nav-item " ><a class="dropdown-item {{ Route::is('filterTransaksi') ? 'active' : '' }}" href="{{ url('/transaksi/filterTransaksi') }}">Perbandingan Transaksi</a></li>
                    </ul>
                </li>
            </ul>
           <a href="{{ route('logout') }}" class="nav-link d-flex text-white ">Logout</a>
        </div>
    </div>
</nav>
