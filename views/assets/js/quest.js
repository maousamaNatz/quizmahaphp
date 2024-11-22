// Definisikan questionMap
const questionMap = {
    '1': [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
    '2': [10,13,14,15,16,17,18,19],
    '3': [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
    '4': [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
    '5': [10,13,14,15,16,17,18,19]
};

// Fungsi untuk mengecek visibilitas pertanyaan
function isQuestionVisible(questionId) {
    const firstQuestionAnswer = document.querySelector('input[name="question_1"]:checked')?.value;
    if (!firstQuestionAnswer) return questionId === 1;
    
    return questionId === 1 || (questionMap[firstQuestionAnswer] && questionMap[firstQuestionAnswer].includes(questionId));
}

// Fungsi untuk menangani visibilitas pertanyaan dengan animasi GSAP
function handleQuestionVisibility() {
    const questionItems = document.querySelectorAll('.question-item');
    const firstQuestionAnswer = document.querySelector('input[name="question_1"]:checked')?.value;

    questionItems.forEach(item => {
        const questionId = parseInt(item.getAttribute('data-question-id'));
        
        if (isQuestionVisible(questionId)) {
            // Tampilkan pertanyaan dengan animasi
            gsap.set(item, { display: 'block' });
            gsap.to(item, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                ease: "power2.out"
            });
        } else {
            // Sembunyikan pertanyaan dengan animasi
            gsap.to(item, {
                opacity: 0,
                y: -20,
                duration: 0.3,
                ease: "power2.in",
                onComplete: () => {
                    item.style.display = 'none';
                }
            });
        }
    });
}

function checkPreviousAnswer() {
    const userId = localStorage.getItem('userId');
    const hash = localStorage.getItem('userHash');
    
    if (userId && hash) {
        // Redirect ke halaman show_answers
        window.location.href = `/traceritesa/tracer/views/questions/show_answers.php?q=${hash}`;
        return true;
    }
    return false;
}

// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    checkPreviousAnswer();

    // Sembunyikan semua pertanyaan kecuali pertanyaan pertama
    const questionItems = document.querySelectorAll('.question-item');
    questionItems.forEach(item => {
        const questionId = parseInt(item.getAttribute('data-question-id'));
        if (questionId !== 1) {
            gsap.set(item, { 
                display: 'none',
                opacity: 0,
                y: -20 
            });
        }
    });

    // Event listener untuk radio buttons pertanyaan pertama
    const firstQuestionRadios = document.querySelectorAll('input[name="question_1"]');
    firstQuestionRadios.forEach(radio => {
        radio.addEventListener('change', handleQuestionVisibility);
    });

    // Form validation
    const form = document.getElementById('questionForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const firstQuestionAnswer = document.querySelector('input[name="question_1"]:checked')?.value;
            if (!firstQuestionAnswer) {
                alert('Mohon jawab pertanyaan pertama');
                return;
            }

            let isValid = true;
            const unansweredQuestions = [];
            
            document.querySelectorAll('.question-item[style*="display: block"]').forEach((item) => {
                const questionId = item.getAttribute('data-question-id');
                let hasAnswer = false;
                
                const inputs = item.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="text"]');
                inputs.forEach(input => {
                    if (input.type === 'text' && input.value.trim() !== '') {
                        hasAnswer = true;
                    } else if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
                        hasAnswer = true;
                    }
                });

                if (!hasAnswer) {
                    isValid = false;
                    unansweredQuestions.push(`Pertanyaan ${questionId}`);
                }
            });

            if (!isValid) {
                alert(`Mohon jawab semua pertanyaan berikut: ${unansweredQuestions.join(', ')}`);
                return;
            }

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    Alpine.store('notifications').add('Data berhasil disimpan', 'success');
                    // Simpan data di localStorage
                    localStorage.setItem('hasAnswered', 'true');
                    localStorage.setItem('userId', result.user_id);
                    localStorage.setItem('userHash', result.hash);
                    
                    // Redirect ke halaman result
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    Alpine.store('notifications').add(result.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Alpine.store('notifications').add('Terjadi kesalahan sistem', 'error');
            }
        });
    }
}); 