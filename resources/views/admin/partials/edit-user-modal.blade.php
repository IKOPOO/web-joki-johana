<!-- Edit User Modal -->
<div id="editUserModal" class="modal-overlay" style="display: none;">
  <div class="modal-container">
    <div class="modal-header">
      <h3>Edit User</h3>
      <button type="button" class="close-modal" onclick="closeEditModal()">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <form id="editUserForm" method="POST" action="">
      @csrf
      @method('PUT')

      <div class="modal-body">
        <!-- Name -->
        <div class="form-group">
          <label for="edit_name">Nama Lengkap</label>
          <input type="text" id="edit_name" name="name" class="form-control" required>
        </div>

        <!-- Email -->
        <div class="form-group">
          <label for="edit_email">Email Address</label>
          <input type="email" id="edit_email" name="email" class="form-control" required>
        </div>

        <!-- Phone -->
        <div class="form-group">
          <label for="edit_phone">No. Handphone</label>
          <input type="text" id="edit_phone" name="phone" class="form-control" required>
        </div>

        <!-- Role -->
        <div class="form-group">
          <label for="edit_role">Role Pengguna</label>
          <select id="edit_role" name="role" class="form-control" required onchange="toggleStudioField('edit')">
            <option value="CUSTOMER">Customer</option>
            <option value="STUDIO_STAF">Studio Staff</option>
            <option value="LENSIA_ADMIN">Admin Lensia</option>
          </select>
        </div>

        <!-- Studio (Hidden by default) -->
        <div class="form-group" id="edit_studio_container" style="display: none;">
          <label for="edit_studio_id">Studio (Wajib untuk Staff)</label>
          <select id="edit_studio_id" name="studio_id" class="form-control">
            <option value="">Pilih Studio</option>
            @foreach($studios as $studio)
              <option value="{{ $studio->id }}">{{ $studio->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Status -->
        <div class="form-group">
          <label for="edit_status">Status Akun</label>
          <select id="edit_status" name="status" class="form-control" required>
            <option value="ACTIVE">Active</option>
            <option value="SUSPENDED">Suspended</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
        <button type="submit" class="btn-save">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<style>
  /* Modal Styles */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  }

  .modal-overlay.show {
    opacity: 1;
    visibility: visible;
  }

  .modal-container {
    background: #fff;
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-20px);
    transition: all 0.3s ease;
  }

  .modal-overlay.show .modal-container {
    transform: translateY(0);
  }

  .modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .modal-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
  }

  .close-modal {
    background: none;
    border: none;
    font-size: 18px;
    color: #999;
    cursor: pointer;
    padding: 5px;
  }

  .close-modal:hover {
    color: #333;
  }

  .modal-body {
    padding: 24px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #555;
  }

  .form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
  }

  .form-control:focus {
    border-color: #FEC72E;
    outline: none;
  }

  .modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
  }

  .btn-cancel {
    background: #f5f5f5;
    color: #666;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
  }

  .btn-save {
    background: #FEC72E;
    color: #333;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
  }

  .btn-save:hover {
    background: #e6b21f;
  }
</style>

<script>
  function closeEditModal() {
    const modal = document.getElementById('editUserModal');
    modal.classList.remove('show');
    setTimeout(() => {
      modal.style.display = 'none';
    }, 300);
  }

  // Close on outside click
  document.getElementById('editUserModal').addEventListener('click', function (e) {
    if (e.target === this) {
      closeEditModal();
    }
  });
</script>