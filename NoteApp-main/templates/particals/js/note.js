function showSaveStatus() {
    document.getElementById('saveText').style.display = 'inline';
    setTimeout(() => {
        document.getElementById('saveText').style.display = 'none';
    }, 2000);
}

function autoSaveNote() {
    const id = document.getElementById('editNoteId').value;
    const title = document.getElementById('editNoteTitle').value;
    const content = document.getElementById('editNoteContent').value;

    console.log('Saving note:', { id, title, content }); // Debug log

    const formData = new FormData();
    formData.append('id', id);
    formData.append('title', title);
    formData.append('content', content);

    fetch('api/save_note.php', { 
        method: 'POST',
        body: formData
    })
    .then(res => {
        if (res.ok) showSaveStatus();
        const noteItem = document.querySelector(`.note-item[data-id="${id}"]`);
        if (noteItem) {
            noteItem.setAttribute('data-title', title);
            noteItem.setAttribute('data-content', content);
            noteItem.querySelector('.card-title').textContent = title;
            noteItem.querySelector('.card-text').textContent = content;
        }
    });
}


document.querySelectorAll('.note-item').forEach(item => {
    item.addEventListener('click', () => {
        const id = item.getAttribute('data-id');
        const title = item.getAttribute('data-title');
        const content = item.getAttribute('data-content');


        document.getElementById('editNoteId').value = id;
        document.getElementById('editNoteTitle').value = title;
        document.getElementById('editNoteContent').value = content;

         fetch(`api/get_image.php?id=${id}`)
            .then(response => response.json())
            .then(images => {
                const imageContainer = document.querySelector("#imagePreviewContainer");
                imageContainer.innerHTML = '';  // Clear current images

                if (images.length > 0) {
                    // Hiển thị tất cả ảnh trong thư mục ID
                    images.forEach(image => {
                        const imgElement = document.createElement("img");
                        imgElement.src = `statics/${id}/${image}`;
                        imgElement.alt="Ảnh";
                        imgElement.classList.add("img-fluid");
                        imageContainer.appendChild(imgElement); 
                    });
                } 
            })
            .catch(error => {
                console.error("Error fetching images:", error);
            });


        const modal = new bootstrap.Modal(document.getElementById('editNoteModal'));
        modal.show();
    });
});

function autoSaveImage(){
    const id = document.getElementById('editNoteId').value;
    const title = document.getElementById('editNoteTitle').value;
    const content = document.getElementById('editNoteContent').value;
    const image = document.getElementById('editNoteImage').files[0];

    const formData = new FormData();
    formData.append('id', id);
    formData.append('title', title);
    formData.append('content', content);
    if (image) {
        formData.append('image', image);
    }

    fetch('api/save_image.php', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        if (res.ok) showSaveStatus();
        document.getElementById('editNoteImage').value = '';
        const noteItem = document.querySelector(`.note-item[data-id="${id}"]`);

        if (noteItem) {
            noteItem.setAttribute('data-title', title);
            noteItem.setAttribute('data-content', content);
            noteItem.querySelector('.card-title').textContent = title;
            noteItem.querySelector('.card-text').textContent = content;
        }
        fetch(`api/get_image.php?id=${id}`)
            .then(response => response.json())
            .then(images => {
                const imageContainer = document.querySelector("#imagePreviewContainer");
                imageContainer.innerHTML = '';  // Clear current images

                if (images.length > 0) {
                    // Hiển thị tất cả ảnh trong thư mục ID
                    images.forEach(image => {
                        const imgElement = document.createElement("img");
                        imgElement.src = `statics/${id}/${image}`;
                        imgElement.alt="Ảnh";
                        imgElement.classList.add("img-fluid");
                        imageContainer.appendChild(imgElement); 
                    });
                } 
            })
    });
}

function openDeleteModal(noteId) {
    noteIdToDelete = noteId;
    document.getElementById('deletePasswordInput').value = '';
    document.getElementById('deleteErrorMsg').style.display = 'none';
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    deleteModal.show();
}


// Gắn sự kiện auto-save khi người dùng chỉnh sửa
document.getElementById('editNoteTitle').addEventListener('input', autoSaveNote);
document.getElementById('editNoteContent').addEventListener('input', autoSaveNote);
document.getElementById('editNoteImage').addEventListener('input', autoSaveImage);
