function toggleQuestions(selectedOption) {
    const questions = document.querySelectorAll('.question-item');
    const questionMap = {
        'A': [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
        'B': [10,13,14,15,16,17,18,19],
        'C': [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
        'D': [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
        'E': [10,13,14,15,16,17,18,19]
    };

    if (!selectedOption) {
        questions.forEach(question => {
            question.style.display = 'block';
        });
        return;
    }

    questions.forEach(question => {
        question.style.display = 'none';
    });

    const firstQuestion = document.querySelector('.question-item[data-question-id="1"]');
    if (firstQuestion) {
        firstQuestion.style.display = 'block';
    }

    if (questionMap[selectedOption]) {
        const showQuestions = questionMap[selectedOption];
        questions.forEach(question => {
            const questionId = parseInt(question.dataset.questionId);
            if (showQuestions.includes(questionId)) {
                question.style.display = 'block';
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach(question => {
        question.style.display = 'block';
    });

    const firstQuestionOptions = document.querySelectorAll('input[name="question_1"]');
    firstQuestionOptions.forEach(option => {
        option.addEventListener('change', function() {
            const optionId = this.value;
            const optionMapping = {
                '1': 'A',
                '2': 'B',
                '3': 'C',
                '4': 'D',
                '5': 'E'
            };
            
            const selectedOption = optionMapping[optionId];
            if (selectedOption) {
                toggleQuestions(selectedOption);
            }
        });
    });
}); 