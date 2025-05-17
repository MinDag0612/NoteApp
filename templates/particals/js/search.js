document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value.trim();
    const email = this.dataset.email
    const resultsList = document.getElementById('searchResults');
    console.log(email)

    if (query === '') {
        resultsList.classList.add('d-none');
        resultsList.innerHTML = '';
        return;
    }

    fetch(`api/search_notes.php?q=${encodeURIComponent(query)}&email=${encodeURIComponent(email)}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                resultsList.innerHTML = '';
                data.forEach(note => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    li.innerHTML = `<strong>${note.title}</strong><br><small>${note.content}</small>`;
                    li.onclick = () => openEditModal(note.id, note.title, note.content); // Hàm đã có sẵn
                    resultsList.appendChild(li);
                });
                resultsList.classList.remove('d-none');
            } else {
                resultsList.innerHTML = `<li class="list-group-item text-muted">Không tìm thấy ghi chú nào.</li>`;
                resultsList.classList.remove('d-none');
            }
        })
        .catch(err => {
            console.error("Lỗi tìm kiếm:", err);
        });
});