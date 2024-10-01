<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">My Application</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page" href="/">Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('barang*') ? 'active' : '' }}" aria-current="page" href="/barang">Master Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('customer*') ? 'active' : '' }}" aria-current="page" href="/customer">Master Customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tjenis*') ? 'active' : '' }}" aria-current="page" href="/tjenis">Master Jenis Transaksi</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
