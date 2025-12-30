function copyUrl() {
  const urlInput = document.getElementById('bookingUrl');
  urlInput.select();
  urlInput.setSelectionRange(0, 99999);

  try {
    navigator.clipboard.writeText(urlInput.value).then(() => {
      alert('✅ Link berhasil dicopy!');
    }).catch(err => {
      document.execCommand('copy');
      alert('✅ Link berhasil dicopy!');
    });
  } catch (e) {
    document.execCommand('copy');
    alert('✅ Link berhasil dicopy!');
  }
}

function openEditModal() {
  const modal = document.getElementById('editModal');
  modal.style.display = 'flex'; // Enable display first
  setTimeout(() => {
    modal.classList.add('active'); // Then animate opacity
  }, 10);
}

function closeEditModal() {
  const modal = document.getElementById('editModal');
  modal.classList.remove('active');
  setTimeout(() => {
    modal.style.display = 'none';
  }, 300); // Match transition duration
}

function previewFile() {
  const input = document.getElementById('imageInput');
  const fileName = document.getElementById('fileName');
  if (input.files && input.files.length > 0) {
    fileName.innerText = input.files[0].name;
    fileName.style.color = '#202020';
    fileName.style.fontWeight = 'bold';
  } else {
    fileName.innerText = "Klik atau geser foto ke sini";
    fileName.style.color = '#666';
    fileName.style.fontWeight = '500';
  }
}

// Close modal on outside click
document.addEventListener('DOMContentLoaded', function () {
  const editModal = document.getElementById('editModal');
  if (editModal) {
    editModal.addEventListener('click', function (e) {
      if (e.target === this) {
        closeEditModal();
      }
    });
  }

  // Auto-dismiss alert after 5 seconds
  const alert = document.querySelector('.alert');
  if (alert) {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity = '0';
      setTimeout(() => {
        alert.remove();
      }, 500);
    }, 5000);
  }
});
