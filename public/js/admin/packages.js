// Status filter
function filterByStatus() {
  const status = document.getElementById('statusFilter').value;
  const url = new URL(window.location.href);

  if (status) {
    url.searchParams.set('status', status);
  } else {
    url.searchParams.delete('status');
  }
  url.searchParams.delete('page');
  window.location.href = url.toString();
}

// Add Modal
function openAddModal() {
  document.getElementById('addModal').classList.add('active');
}

function closeAddModal() {
  document.getElementById('addModal').classList.remove('active');
}

// Edit Modal
function openEditModal(pkg) {
  const form = document.getElementById('editForm');
  // studioId must be defined in the blade view before including this script
  form.action = `/admin/studios/${studioId}/packages/${pkg.id}`;

  document.getElementById('edit_name').value = pkg.name;
  document.getElementById('edit_description').value = pkg.description;
  document.getElementById('edit_duration').value = pkg.duration_minutes;
  document.getElementById('edit_price').value = pkg.price;
  document.getElementById('edit_is_active').checked = pkg.is_active;

  document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('active');
}

// Delete Modal
function openDeleteModal(packageId, packageName) {
  const form = document.getElementById('deleteForm');
  // studioId must be defined in the blade view before including this script
  form.action = `/admin/studios/${studioId}/packages/${packageId}`;
  document.getElementById('deletePackageName').textContent = packageName;
  document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('active');
}

// Close modals on backdrop click
document.querySelectorAll('.modal-overlay').forEach(modal => {
  modal.addEventListener('click', function (e) {
    if (e.target === this) {
      this.classList.remove('active');
    }
  });
});

// Close on escape key
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.active').forEach(modal => {
      modal.classList.remove('active');
    });
  }
});

// Auto-hide alerts after 5 seconds
document.querySelectorAll('.alert').forEach(alert => {
  setTimeout(() => {
    alert.style.transition = 'opacity 0.5s ease';
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 500);
  }, 5000);
});
