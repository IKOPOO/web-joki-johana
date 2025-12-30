// Color palette
const colors = {
  primary: '#FEC72E',
  secondary: '#424242',
  success: '#28a745',
  danger: '#dc3545',
  warning: '#FFC107',
  cream: '#FDF9F1',
};

document.addEventListener('DOMContentLoaded', function () {
  // Auto-hide alerts after 5 seconds
  document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });

  // Charts initialization
  if (typeof usersData !== 'undefined') {
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.plugins.legend.labels.usePointStyle = true;

    // 1. Segmentation Chart
    if (document.getElementById('userSegmentationChart')) {
      new Chart(document.getElementById('userSegmentationChart'), {
        type: 'pie',
        data: {
          labels: ['Customer', 'Staff', 'Admin'],
          datasets: [{
            data: [
              usersData.stats.customer,
              usersData.stats.staff,
              usersData.stats.admin
            ],
            backgroundColor: [
              colors.primary,
              colors.secondary,
              '#e0e0e0'
            ],
            borderWidth: 0,
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: { padding: 20 }
            }
          }
        }
      });
    }

    // 2. Status Chart
    if (document.getElementById('userStatusChart')) {
      new Chart(document.getElementById('userStatusChart'), {
        type: 'pie',
        data: {
          labels: ['Active', 'Suspended'],
          datasets: [{
            data: [
              usersData.stats.active,
              usersData.stats.suspended
            ],
            backgroundColor: [
              colors.success,
              colors.danger
            ],
            borderWidth: 0,
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: { padding: 20 }
            }
          }
        }
      });
    }
  }

  // Close modals on outside click
  document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function (e) {
      if (e.target === this) {
        this.classList.remove('show');
        setTimeout(() => {
          this.style.display = 'none';
        }, 300);
      }
    });
  });
});

// Global functions for inline handlers

// Delete Confirmation
function confirmDelete(userId) {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: "Data user yang dihapus tidak dapat dikembalikan!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Iya, Hapus!',
    cancelButtonText: 'Tidak, Batalkan'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('delete-form-' + userId).submit();
    }
  })
}

// Add User Modal Logic
function openAddModal() {
  const modal = document.getElementById('addUserModal');
  modal.style.display = 'flex';
  modal.offsetHeight;
  modal.classList.add('show');
}

function closeAddModal() {
  const modal = document.getElementById('addUserModal');
  modal.classList.remove('show');
  setTimeout(() => modal.style.display = 'none', 300);
}

// Toggle Studio Field Logic
function toggleStudioField(prefix) {
  const roleSelect = document.getElementById(prefix + '_role');
  const studioContainer = document.getElementById(prefix + '_studio_container');
  const studioSelect = document.getElementById(prefix + '_studio_id');

  if (roleSelect.value === 'STUDIO_STAF') {
    studioContainer.style.display = 'block';
    studioSelect.required = true;
  } else {
    studioContainer.style.display = 'none';
    studioSelect.required = false;
    studioSelect.value = '';
  }
}

// Edit User Modal Logic
function openEditModal(user) {
  // Populate form fields
  document.getElementById('edit_name').value = user.name;
  document.getElementById('edit_email').value = user.email;
  document.getElementById('edit_phone').value = user.phone;

  // Handle Role & Studio
  const roleSelect = document.getElementById('edit_role');
  roleSelect.value = user.role;
  toggleStudioField('edit');

  if (user.role === 'STUDIO_STAF' && user.studio_id) {
    document.getElementById('edit_studio_id').value = user.studio_id;
  }

  document.getElementById('edit_status').value = user.status;

  // Update form action URL
  const form = document.getElementById('editUserForm');
  form.action = `/admin/users/${user.id}`;

  // Show modal
  const modal = document.getElementById('editUserModal');
  modal.style.display = 'flex';
  // Trigger reflow
  modal.offsetHeight;
  modal.classList.add('show');
}

function closeEditModal() {
  const modal = document.getElementById('editUserModal');
  modal.classList.remove('show');
  setTimeout(() => {
    modal.style.display = 'none';
  }, 300);
}
