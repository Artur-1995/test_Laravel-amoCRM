<nav class="navbar navbar-expand-lg navbar-light bg-light max-w-6xl mx-auto">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <button class="nav-link active" aria-current="page" id="redirectTransactions">Выбор сделки</button>
        </li>
        <li class="nav-item">
          <button class="nav-link active" aria-current="page" id="redirectHistory">История</button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
  if (window.performance.getEntriesByType) {
    if (window.performance.getEntriesByType("navigation")[0].type === "reload") {
        alert('reloaded')
    }
  }
  document.getElementById('redirectHistory').addEventListener('click', function() {
      window.location.href = '{{ route('history') }}';
  });
  document.getElementById('redirectTransactions').addEventListener('click', function() {
      window.location.href = '{{ route('transactions') }}';
  });
</script>