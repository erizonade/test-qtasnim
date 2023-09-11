<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Erizon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/jenis-barang') }}">Jenis Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/barang') }}">Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/transaksi') }}">Transaksi</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
