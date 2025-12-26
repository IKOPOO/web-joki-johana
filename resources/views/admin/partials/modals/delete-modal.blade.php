{{--
Delete Confirmation Modal Partial
Usage:
@include('admin.partials.modals.delete-modal', [
'id' => 'deleteStudioModal',
'formId' => 'deleteStudioForm',
'title' => 'Konfirmasi Hapus',
'message' => 'Apakah Anda yakin ingin menghapus item ini?',
'warning' => 'Tindakan ini tidak dapat dibatalkan.',
'closeFunction' => 'closeDeleteModal'
])
--}}
<div class="modal-overlay" id="{{ $id }}">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> {{ $title ?? 'Konfirmasi Hapus' }}</h3>
      <button class="modal-close" onclick="{{ $closeFunction }}()">&times;</button>
    </div>
    <div class="modal-body">
      <p>{{ $message ?? 'Apakah Anda yakin ingin menghapus item ini?' }}</p>
      @if(isset($warningId))
        <p id="{{ $warningId }}" style="color: #dc3545; font-size: 0.9rem; display: none;"></p>
      @endif
      <p style="color: #999; font-size: 0.85rem; margin-top: 0.5rem;">
        {{ $warning ?? 'Tindakan ini tidak dapat dibatalkan.' }}</p>
    </div>
    <form id="{{ $formId }}" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="{{ $closeFunction }}()">Batal</button>
        <button type="submit" class="btn-save btn-confirm-delete">Hapus</button>
      </div>
    </form>
  </div>
</div>