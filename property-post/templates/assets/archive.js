// Add range slider value display
document.querySelector('.form-range').addEventListener('input', function(e) {
    this.nextElementSibling.textContent = '$' + e.target.value;
});