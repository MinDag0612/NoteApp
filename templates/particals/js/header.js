document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleViewBtn');
    const notesContainer = document.getElementById('notesContainer');
    const fontSelect = document.getElementById('fontSelect');
    const colorPicker = document.getElementById('colorPicker');
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    let isGrid = true;

    toggleBtn.addEventListener('click', () => {
        if (isGrid) {
            notesContainer.classList.remove('row', 'row-cols-1', 'row-cols-md-3', 'g-4');
            document.querySelectorAll('.note-item').forEach(item => {
                item.classList.add('m-2');
                item.classList.remove('col');
            });
            toggleBtn.textContent = 'Grid View';
        } else {
            notesContainer.classList.add('row', 'row-cols-1', 'row-cols-md-3', 'g-4');
            document.querySelectorAll('.note-item').forEach(item => {
                item.classList.remove('m-2');
                item.classList.add('col');
            });
            toggleBtn.textContent = 'List View';
        }
        isGrid = !isGrid;
    });

    fontSelect.addEventListener('change', () => {
        document.querySelectorAll('.note-card').forEach(card => {
            card.style.fontFamily = fontSelect.value;
        });
    });

    colorPicker.addEventListener('input', () => {
        document.querySelectorAll('.note-card').forEach(card => {
            card.style.backgroundColor = colorPicker.value;
        });
    });

    darkModeSwitch.addEventListener('change', () => {
        if (darkModeSwitch.checked) {
            document.body.classList.add('custom-dark-bg', 'custom-dark-text');
            document.querySelector('.modal-content').classList.add('custom-dark-bg', 'custom-dark-text');
            document.getElementById('header').classList.add('custom-dark-text');
            document.querySelector('h2').classList.remove('text-secondary');
        } else {
            document.body.classList.remove('custom-dark-bg', 'custom-dark-text');
            document.querySelector('.modal-content').classList.remove('custom-dark-bg', 'custom-dark-text');
            document.getElementById('header').classList.remove('custom-dark-text');
            document.querySelector('h2').classList.add('text-secondary');
        }
    });

});




