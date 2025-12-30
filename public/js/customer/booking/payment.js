function showPaymentDetail(type) {
  // Hide all first
  const container = document.getElementById('payment-details-container');
  container.style.display = 'block';

  ['qris', 'bank', 'ewallet', 'cash'].forEach(id => {
    document.getElementById('detail-' + id).style.display = 'none';
  });

  // Show selected
  document.getElementById('detail-' + type).style.display = 'block';
}

function copyText(text) {
  navigator.clipboard.writeText(text).then(function () {
    alert('Nomor berhasil disalin!');
  }, function (err) {
    console.error('Could not copy text: ', err);
  });
}

function previewImage(event) {
  var input = event.target;
  var placeholder = document.getElementById('uploadPlaceholder');
  var preview = document.getElementById('imagePreview');

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
      placeholder.style.display = 'none';
    }

    reader.readAsDataURL(input.files[0]);
  }
}
