// Generate Modal
function openGenerateModal() {
  document.getElementById('generateModal').classList.add('active');
}

function closeGenerateModal() {
  document.getElementById('generateModal').classList.remove('active');
}

// Reset Modal
function openResetModal() {
  document.getElementById('resetModal').classList.add('active');
}

function closeResetModal() {
  document.getElementById('resetModal').classList.remove('active');
}

// Handle Reset Form Submission
document.addEventListener('DOMContentLoaded', function () {
  const resetForm = document.getElementById('resetForm');
  if (resetForm) {
    resetForm.addEventListener('submit', function (e) {
      e.preventDefault();

      Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Tindakan ini akan MENGHAPUS SEMUA slot sesi pada rentang tanggal yang dipilih. Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus Slot!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  }
});

// Add Modal
function openAddModal() {
  document.getElementById('addModal').classList.add('active');
}


function closeAddModal() {
  document.getElementById('addModal').classList.remove('active');
}

// Edit Modal
function openEditModal(slot) {
  const form = document.getElementById('editForm');
  form.action = `/session-slots/${slot.id}`;

  // Extract date string YYYY-MM-DD
  const slotDate = new Date(slot.date);
  const dateStr = slotDate.toISOString().split('T')[0];

  document.getElementById('edit_date').value = dateStr;
  document.getElementById('edit_start_time').value = slot.start_time.substring(0, 5);
  document.getElementById('edit_end_time').value = slot.end_time.substring(0, 5);

  document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('active');
}

// Close modals on backdrop click
document.querySelectorAll('.modal-overlay').forEach(modal => {
  modal.addEventListener('click', function (e) {
    if (e.target === this) {
      this.classList.remove('active');
    }
  });
});

// Auto-hide alerts after 5 seconds
document.querySelectorAll('.alert').forEach(alert => {
  setTimeout(() => {
    alert.style.transition = 'opacity 0.5s ease';
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 500);
  }, 5000);
});

// Confirm Delete Single Slot
function confirmDelete(slotId) {
  Swal.fire({
    title: 'Apakah Anda Yakin?',
    text: "Slot sesi ini akan dihapus secara permanen!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById(`delete-form-${slotId}`).submit();
    }
  });
}
