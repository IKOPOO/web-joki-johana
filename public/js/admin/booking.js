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

// Client-side search
const searchInput = document.getElementById('searchInput');
const tableBody = document.getElementById('bookingTableBody');

if (searchInput) {
  searchInput.addEventListener('input', function () {
    const searchTerm = this.value.toLowerCase();
    const rows = tableBody.querySelectorAll('tr');

    rows.forEach(row => {
      const name = row.querySelector('.customer-name')?.textContent.toLowerCase() || '';
      const phone = row.querySelector('.phone-number')?.textContent.toLowerCase() || '';
      const studio = row.querySelector('.badge-studio')?.textContent.toLowerCase() || '';

      const matches = name.includes(searchTerm) || phone.includes(searchTerm) || studio.includes(searchTerm);
      row.style.display = matches ? '' : 'none';
    });
  });
}

// Load packages based on studio
function loadPackages(studioId, prefix) {
  const packageSelect = document.getElementById(prefix + '_package_id');
  packageSelect.innerHTML = '<option value="">Pilih Paket</option>';

  if (studioId && typeof packagesData !== 'undefined') {
    const studioPackages = packagesData.filter(p => p.studio_id == studioId);
    studioPackages.forEach(pkg => {
      const option = document.createElement('option');
      option.value = pkg.id;
      option.textContent = `${pkg.name} - Rp ${Number(pkg.price).toLocaleString('id-ID')}`;
      packageSelect.appendChild(option);
    });
  }
}

// Add Modal
function openAddModal() {
  document.getElementById('addModal').classList.add('active');
}

function closeAddModal() {
  document.getElementById('addModal').classList.remove('active');
}

// Edit Modal
function openEditModal(booking) {
  const form = document.getElementById('editForm');
  form.action = `/admin/bookings/${booking.id}`;

  document.getElementById('edit_user_id').value = booking.user_id;
  document.getElementById('edit_studio_id').value = booking.studio_id;

  // Load packages for the studio first
  loadPackages(booking.studio_id, 'edit');
  // Then set the package after a small delay to ensure options are loaded
  setTimeout(() => {
    document.getElementById('edit_package_id').value = booking.package_id;
  }, 100);

  // Format date and time for inputs
  if (booking.booking_datetime) {
    const dt = new Date(booking.booking_datetime);
    const dateStr = dt.toISOString().slice(0, 10);
    const timeStr = dt.toTimeString().slice(0, 5);
    document.getElementById('edit_booking_date').value = dateStr;
    document.getElementById('edit_booking_time').value = timeStr;
  }

  document.getElementById('edit_note').value = booking.note || '';
  document.getElementById('edit_status').value = booking.status;
  document.getElementById('edit_payment_status').value = booking.payment_status;

  document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('active');
}

// Detail Modal
function openDetailModal(id) {
  const content = document.getElementById('booking-detail-' + id).innerHTML;
  document.getElementById('detailModalBody').innerHTML = content;
  document.getElementById('detailModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
  document.getElementById('detailModal').classList.remove('active');
  document.body.style.overflow = '';
}

// Delete Modal
function confirmDelete(id) {
  const form = document.getElementById('deleteForm');
  form.action = `/admin/bookings/${id}`;
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
