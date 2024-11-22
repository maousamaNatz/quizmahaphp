function handleLoading() {
    const loadingKey = 'form_loading_state';
    
    // Cek status loading dari sessionStorage
    const loadingState = sessionStorage.getItem(loadingKey);
    if (loadingState === 'loading') {
        showLoading();
    }
    
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        sessionStorage.setItem(loadingKey, 'loading');
        Alpine.store('notifications').add('Data berhasil ditambahkan', 'success');
        
        setTimeout(() => {
            sessionStorage.removeItem(loadingKey);
            this.submit();
        }, 1000);
    });
    
    function showLoading() {
        const loadingEl = document.createElement('div');
        loadingEl.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingEl.id = 'loadingOverlay';
        loadingEl.innerHTML = `
            <div class="bg-white p-5 rounded-lg flex flex-col items-center">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
                <p class="mt-3 text-gray-700">Menyimpan data...</p>
            </div>
        `;
        document.body.appendChild(loadingEl);
        
        // Tambah animasi fade in
        gsap.from(loadingEl, {
            opacity: 0,
            duration: 0.3,
            ease: "power2.inOut"
        });
    }
    
    function hideLoading() {
        const loadingEl = document.getElementById('loadingOverlay');
        if (loadingEl) {
            gsap.to(loadingEl, {
                opacity: 0,
                duration: 0.3,
                ease: "power2.inOut",
                onComplete: () => loadingEl.remove()
            });
        }
    }
}

// Panggil fungsi saat DOM loaded
document.addEventListener('DOMContentLoaded', handleLoading);