function handleLoading() {
    const loadingKey = 'form_loading_state';
    
    // Cek status loading dari sessionStorage
    const loadingState = sessionStorage.getItem(loadingKey);
    if (loadingState === 'loading') {
        showLoading();
    }
    
    // Tangkap submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        // Simpan state loading
        sessionStorage.setItem(loadingKey, 'loading');
        showLoading();
    });
    
    function showLoading() {
        // Tampilkan loading spinner
        const loadingEl = document.createElement('div');
        loadingEl.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingEl.innerHTML = `
            <div class="bg-white p-5 rounded-lg flex flex-col items-center">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
                <p class="mt-3 text-gray-700">Menyimpan data...</p>
            </div>
        `;
        document.body.appendChild(loadingEl);
        
        // Disable form
        document.querySelector('form').classList.add('opacity-50', 'pointer-events-none');
    }
}

// Hapus loading state saat halaman selesai load
window.addEventListener('load', function() {
    sessionStorage.removeItem('form_loading_state');
});
