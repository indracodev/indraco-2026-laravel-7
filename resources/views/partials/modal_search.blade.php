<!-- modal search -->
<div id="modal-search" class="modal fade" tabindex="-1" aria-labelledby="modal-search-title" aria-hidden="true">
   <div class="modal-dialog modal-fullscreen-lg-down modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header border-0 pb-0">
            <h5 class="modal-title visually-hidden" id="modal-search-title">Cari Produk</h5>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
         </div>
         <div class="modal-body pt-0">
            <form id="search-form" role="search" action="#" method="GET">
               <div class="input-group input-group-lg border-bottom">
                  <span class="input-group-text bg-transparent border-0 px-2">
                     <svg xmlns="http://www.w3.org/2000/svg" width="1.25em" height="1.25em" viewBox="0 0 640 640"><path fill="currentColor" d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z" /></svg>
                  </span>
                  <input type="search" name="q" class="form-control bg-transparent border-0 shadow-none ps-1" id="search-input" placeholder="Cari nama produk, SKU, atau kemasan..." autocomplete="off">
               </div>
               <div id="search-suggestions" class="list-group list-group-flush mt-2 d-none">
                  <!-- Suggestions will appear here -->
               </div>
               <div class="mt-4 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold text-capitalize">Cari</button>
               </div>
            </form>
         </div>
      </div>
   </div>   
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    const searchForm = document.getElementById('search-form');

    let debounceTimer;

    // Fungsi untuk mempercantik URL (slugify)
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Ganti spasi dengan -
            .replace(/[^\w\-]+/g, '')       // Hapus karakter non-word
            .replace(/\-\-+/g, '-')         // Ganti multi - dengan satu -
            .replace(/^-+/, '')             // Hapus - di awal
            .replace(/-+$/, '');            // Hapus - di akhir
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            searchSuggestions.classList.add('d-none');
            searchSuggestions.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('api.products.suggestions') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        searchSuggestions.innerHTML = '';
                        data.forEach(item => {
                            const suggestionItem = document.createElement('a');
                            suggestionItem.href = `{{ url('/products/search') }}/${slugify(item.nama_produk)}`;
                            suggestionItem.className = 'list-group-item list-group-item-action d-flex align-items-center gap-3 border-0 py-3';
                            
                            const imgSrc = item.gambar_utama ? `{{ asset('') }}${item.gambar_utama}` : 'https://placehold.co/50x50?text=No+Image';
                            
                            suggestionItem.innerHTML = `
                                <img src="${imgSrc}" alt="${item.nama_produk}" style="width: 40px; height: 40px; object-fit: contain;">
                                <div>
                                    <div class="fw-bold text-dark">${item.nama_produk}</div>
                                    <small class="text-muted">SKU: ${item.sku || '-'}</small>
                                </div>
                            `;
                            
                            suggestionItem.addEventListener('click', (e) => {
                                e.preventDefault();
                                window.location.href = `{{ url('/products/search') }}/${slugify(item.nama_produk)}`;
                            });

                            searchSuggestions.appendChild(suggestionItem);
                        });
                        searchSuggestions.classList.remove('d-none');
                    } else {
                        searchSuggestions.classList.add('d-none');
                    }
                });
        }, 300);
    });

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query.length > 0) {
            window.location.href = `{{ url('/products/search') }}/${slugify(query)}`;
        }
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchForm.contains(e.target)) {
            searchSuggestions.classList.add('d-none');
        }
    });
});
</script>
