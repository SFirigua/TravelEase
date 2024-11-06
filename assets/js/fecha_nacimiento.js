const today = new Date();
const minDate = new Date(today.setFullYear(today.getFullYear() - 18));
const minDateString = minDate.toISOString().split('T')[0]; // Convierte a formato yyyy-mm-dd
document.getElementById('fecha_nacimiento').setAttribute('max', minDateString);

