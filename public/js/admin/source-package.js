// Status filter function
function filterByStatus() {
  const status = document.getElementById('statusFilter').value;
  const url = new URL(window.location.href);

  if (status) {
    url.searchParams.set('status', status);
  } else {
    url.searchParams.delete('status');
  }

  // Reset to page 1 when filtering
  url.searchParams.delete('page');

  window.location.href = url.toString();
}

// Client-side search
const searchInput = document.getElementById('searchInput');
if (searchInput) {
  searchInput.addEventListener('keyup', function () {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#studioTableBody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(value) ? '' : 'none';
    });
  });
}

// Add Studio Modal
function openAddStudioModal() {
  document.getElementById('addStudioModal').classList.add('active');
}

function closeAddStudioModal() {
  document.getElementById('addStudioModal').classList.remove('active');
}

// Edit Studio Modal
function openEditStudioModal(studio) {
  const form = document.getElementById('editStudioForm');
  form.action = `/admin/studios/${studio.id}`;

  document.getElementById('edit_studio_name').value = studio.name;
  document.getElementById('edit_studio_address').value = studio.address;
  document.getElementById('edit_studio_city').value = studio.city;
  document.getElementById('edit_studio_status').value = studio.status;

  document.getElementById('editStudioModal').classList.add('active');
}

function closeEditStudioModal() {
  document.getElementById('editStudioModal').classList.remove('active');
}

// Delete Studio Modal
function openDeleteStudioModal(studioId, studioName, packageCount) {
  const form = document.getElementById('deleteStudioForm');
  form.action = `/admin/studios/${studioId}`;
  document.getElementById('deleteStudioName').textContent = studioName;

  const warningEl = document.getElementById('deleteWarningPackages');
  const countText = document.getElementById('packageCountText');

  if (packageCount > 0) {
    warningEl.style.display = 'block';
    countText.textContent = `${packageCount} package`;
  } else {
    warningEl.style.display = 'none';
  }

  document.getElementById('deleteStudioModal').classList.add('active');
}

function closeDeleteStudioModal() {
  document.getElementById('deleteStudioModal').classList.remove('active');
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
