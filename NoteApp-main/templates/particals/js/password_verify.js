document.querySelectorAll('.note-item').forEach(item => {
    item.addEventListener('click', () => {
        const isProtected = item.dataset.protected === "true";
        const noteId = item.dataset.id;

        if (isProtected) {
            sessionStorage.setItem('noteToUnlock', noteId);
            document.getElementById('notePasswordInput').value = '';
            document.getElementById('passwordError').style.display = 'none';

            const modal = new bootstrap.Modal(document.getElementById('passwordPromptModal'));
            modal.show();
        } else {
            openNoteEditor(item);
        }
    });
});

document.getElementById('passwordSubmitBtn').addEventListener('click', () => {
    const password = document.getElementById('notePasswordInput').value;
    const noteId = sessionStorage.getItem('noteToUnlock');

    fetch('/api/verify_password.php', {
        method: 'POST',
        body: new URLSearchParams({ id: noteId, password })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('passwordPromptModal'));
            modal.hide();

            const item = document.querySelector(`.note-item[data-id="${noteId}"]`);
            if (item) {
                item.dataset.protected = "false";
                const icon = item.querySelector('.lock-icon');
                if (icon) {
                    icon.classList.remove('bi-lock-fill', 'text-warning');
                    icon.classList.add('bi-unlock', 'text-success');
                }
                openNoteEditor(item);
            }
        } else {
            document.getElementById('passwordError').style.display = 'block';
        }
    });
});

// Hàm mở ghi chú sau khi xác thực
function openNoteEditor(item) {
    const id = item.dataset.id;
    const title = item.dataset.title;
    const content = item.dataset.content;

    document.getElementById('editNoteId').value = id;
    document.getElementById('editNoteTitle').value = title;
    document.getElementById('editNoteContent').value = content;

    fetch(`api/get_image.php?id=${id}`)
        .then(res => res.json())
        .then(images => {
            const container = document.getElementById("imagePreviewContainer");
            container.innerHTML = '';
            images.forEach(img => {
                const imgEl = document.createElement("img");
                imgEl.src = `statics/${id}/${img}`;
                imgEl.classList.add("img-fluid", "col-4");
                container.appendChild(imgEl);
            });
        });

    const modal = new bootstrap.Modal(document.getElementById('editNoteModal'));
    modal.show();
}