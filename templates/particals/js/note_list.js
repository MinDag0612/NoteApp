function openEditModal(id, title, content) {
    document.getElementById('editNoteId').value = id;
    document.getElementById('editNoteTitle').value = title;
    document.getElementById('editNoteContent').value = content;

    const titleInput = document.getElementById('editNoteTitle');
    const contentInput = document.getElementById('editNoteContent');
    const imageInput = document.getElementById('editNoteImage');

    // Gỡ bỏ sự kiện trước đó
    titleInput.removeEventListener('input', autoSaveNote);
    contentInput.removeEventListener('input', autoSaveNote);
    imageInput.removeEventListener('input', autoSaveImage);

    // Thêm lại sự kiện sau khi mở modal
    titleInput.addEventListener('input', autoSaveNote);
    contentInput.addEventListener('input', autoSaveNote);
    imageInput.addEventListener('input', autoSaveImage);

    // Load ảnh nếu có
    fetch(`api/get_image.php?id=${id}`)
        .then(res => res.json())
        .then(images => {
            const imageContainer = document.getElementById('imagePreviewContainer');
            imageContainer.innerHTML = '';

            images.forEach(image => {
                const img = document.createElement('img');
                img.src = `statics/${id}/${image}`;
                img.alt = "Ảnh";
                img.classList.add('img-fluid', 'm-1');
                imageContainer.appendChild(img);
            });
        });

    // Hiển thị modal Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('editNoteModal'));
    modal.show();
}

function removeNote(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch('api/remove_note.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text()) // chỉ đọc text, không cần JSON
    .then(result => {
        console.log("Xóa ghi chú:", result.trim());
        loadNotes();
    })
    .catch(err => {
        console.error("Lỗi khi xóa", err);
    });
}

function loadNotes() {
    const email = document.querySelector("#new-note").dataset.email;

    const formData = new FormData();
    formData.append('email', email);

    fetch('api/get_notes.php', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        console.log("Raw response:", res);
        return res.json(); // chuyển sang JSON
    })
    .then(notes => {
        // console.log(res)
        const container = document.getElementById('notesContainer');
        container.innerHTML = ''; // Xoá nội dung cũ

        if (notes.length === 0) {
            container.innerHTML = '<p class="text-muted">Bạn chưa có ghi chú nào.</p>';
            return;
        }

        notes.forEach(note => {
            const noteDiv = document.createElement('div');
            noteDiv.className = 'col note-item';
            noteDiv.dataset.id = note.id;
            noteDiv.dataset.title = note.title;
            noteDiv.dataset.content = note.content;

            noteDiv.innerHTML = `
                <div class="card h-100 note-card p-3 position-relative m-2" data-id="${note.id}">
                    <!-- Header chứa nút ghim và nút xóa -->
                    <div class="d-flex justify-content-between align-items-start">
                    <button type="button" class="btn btn-sm pin-note-btn ${note.is_pin ? 'pinned' : ''}" title="Ghim ghi chú">
                        ${note.is_pin ? '📍' : '📌'}
                    </button>
                    <button type="button" class="btn-close delete-note-btn" aria-label="Close"></button>
                </div>

                    <div class="card-body">
                        <h5 class="card-title">${note.title}</h5>
                        <p class="card-text">${note.content}</p>
                    </div>
                </div>
            `;


            noteDiv.addEventListener('click', () => {
                openEditModal(note.id, note.title, note.content);
            });
            // Nút ghim
            const pinBtn = noteDiv.querySelector('.pin-note-btn');
            pinBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Không mở modal

            const formData = new FormData();
            formData.append('id', note.id);

            fetch('api/pin_note.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text()) // hoặc res.json() nếu bạn dùng json
            .then(data => {
                loadNotes(); // Gọi lại để reload và thấy thay đổi
            })
            .catch(err => {
                console.error('Lỗi khi ghim:', err);
            });
        });

            const deleteBtn = noteDiv.querySelector('.delete-note-btn');
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Ngăn click vào card
                const confirmDelete = confirm("Bạn có chắc muốn xoá ghi chú này?");
                if (confirmDelete) {
                    // console.log(note.id)
                    removeNote(note.id); // bạn cần định nghĩa hàm này
                }
            });

            container.appendChild(noteDiv);
            // console.log(`Note ${note.id}:`, note.title, note.content);

            // Gắn sự kiện auto-save cho các phần tử mới thêm vào
            noteDiv.querySelector('.card-title').addEventListener('input', autoSaveNote);
            noteDiv.querySelector('.card-text').addEventListener('input', autoSaveNote);
        });
    });
}


function new_note() {
    const email = document.querySelector("#new-note").dataset.email;
    console.log(email);
    const formData = new FormData();
    formData.append('email', email);

    fetch('api/new_note.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showSaveStatus();
            loadNotes();
            openEditModal(data.id, data.title, data.content);
        } else {
            console.error("Tạo ghi chú thất bại:", data.message);
        }
    })
    .catch(err => {
        console.error("Lỗi khi tạo ghi chú:", err);
    });
}

function changeProfile() {
    const currEmail = document.getElementById('changeProfileBtn').dataset.email;
    const newEmail = document.getElementById("accountEmail").value;
    const newName = document.getElementById("accountName").value;

    const formData = new FormData();
    formData.append('newEmail', newEmail);
    formData.append('newName', newName);
    formData.append('currEmail', currEmail);

    fetch('api/updateProfile.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(text => {
        alert(text);
        // Nếu text là 1 thông báo thành công cụ thể:
        window.location.href = 'login.php';
    })
    .catch(err => {
        console.error("Fetch error", err);
    });
}

function updateAvt() {
    const newAvt = document.getElementById('avatarFile').files[0];
    const id = document.getElementById('btnAvt').dataset.id;

    const formData = new FormData();
    formData.append('newAvt', newAvt);
    formData.append('id' , id)

    fetch('api/updateAvatar.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(text => {
        alert(text);
        window.location.href = 'home.php';
    })
    .catch(err => {
        console.error("Fetch error", err);
    });
}

document.querySelector("#new-note").addEventListener("click", new_note);
document.addEventListener('DOMContentLoaded', loadNotes);
document.getElementById('accountModal').addEventListener("submit",  (event) => {
    event.preventDefault();
    changeProfile();
})
document.getElementById('avatarModal').addEventListener("submit",  (event) => {
    event.preventDefault();
    updateAvt();
})
