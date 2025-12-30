function openBookingModal() {
  document.getElementById('bookingOverlay').classList.add('active');
  document.getElementById('bookingModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
  document.getElementById('bookingOverlay').classList.remove('active');
  document.getElementById('bookingModal').classList.remove('active');
  document.body.style.overflow = '';
}

// Slot Fetching Logic
const dateInput = document.getElementById('date');
const slotsContainer = document.getElementById('slots-container');
const slotsLoading = document.getElementById('slots-loading');
const slotInput = document.getElementById('session_slot_id');
const studioId = document.querySelector('input[name="studio_id"]').value;

document.addEventListener("DOMContentLoaded", function () {
  // Re-open modal if there are errors
  if (window.hasErrors) {
    openBookingModal();
  }

  // Initialize Flatpickr
  flatpickr(dateInput, {
    minDate: "today",
    dateFormat: "Y-m-d",
    onChange: function (selectedDates, dateStr, instance) {
      // Trigger the change logic manually since Flatpickr might not bubble it the same way
      fetchSlots(dateStr);
    }
  });

  // Dynamic Price Update Logic
  const summaryBox = document.getElementById('summaryBox');
  const totalPriceEl = document.getElementById('totalPrice');
  const packageInputs = document.querySelectorAll('input[name="package_id"]');

  packageInputs.forEach(input => {
    input.addEventListener('change', function () {
      const price = this.getAttribute('data-price');
      if (price) {
        summaryBox.style.display = 'block';
        totalPriceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
      }
    });
  });
});

function fetchSlots(date) {
  if (!date) return;

  slotsContainer.innerHTML = '';
  slotsLoading.style.display = 'block';
  slotInput.value = ''; // Reset selection

  // Check if bookingSlotsRoute is defined
  const url = window.bookingSlotsRoute ? `${window.bookingSlotsRoute}?studio_id=${studioId}&date=${date}` : `?studio_id=${studioId}&date=${date}`;

  fetch(url)
    .then(response => response.json())
    .then(data => {
      slotsLoading.style.display = 'none';

      if (!Array.isArray(data)) {
        console.error('Invalid response:', data);
        slotsContainer.innerHTML = '<p style="color:red;">Terjadi kesalahan memuat data. Periksa konsol.</p>';
        return;
      }

      if (data.length === 0) {
        slotsContainer.innerHTML = '<p style="color:#999;">Tidak ada sesi tersedia pada tanggal ini. Hubungi studio untuk info lebih lanjut.</p>';
      } else {
        data.forEach(slot => {
          const div = document.createElement('div');
          div.className = `slot-card ${slot.is_booked ? 'disabled' : ''}`;
          div.textContent = slot.display_time;

          if (!slot.is_booked) {
            div.onclick = function () {
              // Deselect others
              document.querySelectorAll('.slot-card').forEach(el => el.classList.remove('selected'));
              // Select this
              this.classList.add('selected');
              slotInput.value = slot.id;
            };
          } else {
            div.title = "Sesi ini sudah dipesan";
          }

          slotsContainer.appendChild(div);
        });
      }
    })
    .catch(err => {
      slotsLoading.style.display = 'none';
      slotsContainer.innerHTML = '<p style="color:red;">Gagal memuat jadwal. Silakan coba lagi.</p>';
      console.error(err);
    });
}
