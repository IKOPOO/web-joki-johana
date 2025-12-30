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