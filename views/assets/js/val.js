// formValidation.js
export function initializeFormValidation() {
    const form = document.querySelector('#questionForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const unansweredQuestions = [];

        // Reset validasi
        document.querySelectorAll('input, select').forEach(field => {
            field.classList.remove('border-red-500', 'ring-red-200');
            field.classList.add('border-slate-200');
        });

        // Validasi pertanyaan yang terlihat
        document.querySelectorAll('.question-item').forEach((item) => {
            if (item.style.display === 'none') return;
            
            const questionId = parseInt(item.dataset.questionId);
            let hasAnswer = false;

            // Cek berdasarkan tipe input
            const radioInputs = item.querySelectorAll('input[type="radio"]');
            const checkboxInputs = item.querySelectorAll('input[type="checkbox"]');
            const textInputs = item.querySelectorAll('input[type="text"]');

            if (radioInputs.length) {
                hasAnswer = Array.from(radioInputs).some(radio => radio.checked);
            } else if (checkboxInputs.length) {
                hasAnswer = Array.from(checkboxInputs).some(cb => cb.checked);
            } else if (textInputs.length) {
                hasAnswer = Array.from(textInputs).some(input => input.value.trim() !== '');
            }

            if (!hasAnswer) {
                isValid = false;
                unansweredQuestions.push(`Pertanyaan ${questionId}`);
            }
        });

        if (!isValid) {
            Alpine.store('notifications').add(
                `Mohon jawab semua pertanyaan berikut: ${unansweredQuestions.join(', ')}`,
                'error'
            );
            return;
        }

        form.submit();
    });
}