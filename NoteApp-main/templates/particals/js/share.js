document.addEventListener('DOMContentLoaded', () => {
    const shareNoteModal = document.getElementById('share_note');

    // Khi modal được mở thì tải danh sách ghi chú chia sẻ
    shareNoteModal.addEventListener('shown.bs.modal', function () {
        loadSharedNotes();
    });
});

function loadSharedNotes() {
    fetch('api/get_shared_notes.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('sharedByMeContainer');
            const emptyMsg = document.getElementById('noSharedNotesMessage');

            container.innerHTML = ''; // Xoá cũ

            if (data.length === 0) {
                emptyMsg.style.display = 'block';
            } else {
                emptyMsg.style.display = 'none';

                data.forEach(note => {
                    const div = document.createElement('div');
                    div.className = 'col-md-4';

                    div.innerHTML = `
                        <div class="card border-primary shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">${note.title}</h5>
                                <p class="card-text">${note.content}</p>
                                <small class="text-muted">Chia sẻ bởi: ${note.email}</small>
                            </div>
                        </div>
                    `;
                    container.appendChild(div);
                });
            }
        })
        .catch(err => {
            console.error('Lỗi khi tải ghi chú chia sẻ:', err);
        });
}