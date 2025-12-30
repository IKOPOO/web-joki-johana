// Auto-focus for printing
window.onload = function () {
  if (window.location.href.includes('print=1')) {
    window.print();
  }
}
