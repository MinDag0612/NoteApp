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

function confirmPass(){ {

}
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
                    <button type="button" class="btn btn-sm share-note-btn ${note.share == 1? 'link' : 'unlink'}" title="Ghim ghi chú">
                        ${note.share ? '🔗' : '❌🔗'}
                    </button>
                    <button type="button" class="btn btn-sm pin-note-btn ${note.is_pin ? 'pinned' : ''}" title="Ghim ghi chú">
                        ${note.is_pin ? '📍' : '📌'}
                    </button>
                        <button type="button" class="btn btn-sm lock-note-btn ${note.is_protected == 1 ? 'Protected' : ''}" title="Khóa ghi chú">
                            ${note.is_protected == 1 ? '🔒' : '🔓'}
                        </button>
                    <button type="button" class="btn-close delete-note-btn" aria-label="Close"></button>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">${note.title}</h5>
                        <p class="card-text"></p>
                    </div>
                        <form class="password-form mt-2 d-none">
                        <input type="password" class="form-control mb-2 password-input" placeholder="Nhập mật khẩu">
                        <button type="submit" class="btn btn-sm btn-primary">Xác nhận</button>
                        </form>

                        <form class="share-form d-none mt-2">
                        <div class="input-group">
                            <input type="email" class="form-control share-email-input" placeholder="Email người nhận" required>
                            <button class="btn btn-primary" type="submit">Gửi</button>
                        </div>
                </div>
            `;


            noteDiv.addEventListener('click', () => {
                if(note.is_protected == 1){
                    console.log("đã Khóa");
                }
                else{
                openEditModal(note.id, note.title, note.content);
                }
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
            const lockForm = noteDiv.querySelector('.password-form');
            const passwordInput = lockForm.querySelector('.password-input');
            const lockBtn = noteDiv.querySelector('.lock-note-btn');
            lockBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Không mở modal
            
            const lockForm = noteDiv.querySelector('.password-form');
            const passwordInput = lockForm.querySelector('.password-input');
            lockForm.classList.toggle('d-none');
            if (!lockForm.classList.contains('d-none')) {
                passwordInput.focus();
            }
        });
            lockForm.addEventListener('submit', (e) => {
                e.stopPropagation();
                e.preventDefault();
                const password = passwordInput.value;
                const action = note.is_protected == 1 ? 'unlock' : 'lock';

                const formData = new FormData();
                formData.append('id', note.id);
                formData.append('password', password);
                formData.append('action', action);

                fetch('api/notepass.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        loadNotes(); // Refresh UI
                    } else {
                        alert(response.message || 'Sai mật khẩu hoặc lỗi hệ thống');
                    }
                })
                .catch(err => {
                    console.error('Lỗi xác thực:', err);
                });
            });

            //
            const shareForm = noteDiv.querySelector('.share-form');
            const shareEmailInput = shareForm.querySelector('.share-email-input');
            const shareBtn = noteDiv.querySelector('.share-note-btn'); 

            // Thêm event click để toggle hiện form chia sẻ (nút có icon link hoặc ghim, bạn có thể dùng nút riêng nếu muốn)
            shareBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // tránh mở modal chỉnh sửa
                shareForm.classList.toggle('d-none');
                if (!shareForm.classList.contains('d-none')) {
                    shareEmailInput.focus();
                }
            });

            // Xử lý submit form chia sẻ
                shareForm.addEventListener('submit', (e) => {
                e.stopPropagation();
                e.preventDefault();
                const emailToShare = shareEmailInput.value.trim();
                if (!emailToShare) {
                    alert("Vui lòng nhập email người nhận");
                    return;
                }
                const action = note.share == 1 ? 'unlink' : 'link';
                const formData = new FormData();
                formData.append('note_id', note.id);
                formData.append('share_email', emailToShare);
                formData.append('action', action);
                fetch('api/share.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        alert("Chia sẻ ghi chú thành công");
                        shareForm.classList.add('d-none');
                        shareEmailInput.value = '';
                        loadNotes(); // reload ghi chú nếu muốn cập nhật
                    } else {
                        alert(response.message || 'Lỗi chia sẻ ghi chú');
                    }
                })
                .catch(err => {
                    console.error('Lỗi chia sẻ ghi chú:', err);
                });
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

document.querySelector("#new-note").addEventListener("click", new_note);
document.addEventListener('DOMContentLoaded', loadNotes);
