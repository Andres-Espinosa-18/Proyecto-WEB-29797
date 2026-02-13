// 1. Validar Cédula (Módulo 10) - Se ejecuta en onblur
function validarCed(input) {
    let cedula = input.value.trim();
    if (cedula.length !== 10 || isNaN(cedula)) {
        input.setCustomValidity("Cédula inválida (10 dígitos)");
        input.reportValidity();
        return false;
    }
    // Algoritmo Ecuador
    let total = 0;
    let longitud = cedula.length;
    let longcheck = longitud - 1;
    for(let i = 0; i < longcheck; i++){
        if (i%2 === 0) {
            let aux = cedula.charAt(i) * 2;
            if (aux > 9) aux -= 9;
            total += aux;
        } else {
            total += parseInt(cedula.charAt(i)); 
        }
    }
    total = total % 10 ? 10 - total % 10 : 0;

    if (cedula.charAt(longitud-1) == total) {
        input.setCustomValidity("");
        return true;
    } else {
        input.setCustomValidity("Cédula Incorrecta");
        input.reportValidity();
        return false;
    }
}

// 2. Calcular Edad y Validar 18+
function calcularEdad(inputFecha, idDisplay) {
    const fechaNac = new Date(inputFecha.value);
    const hoy = new Date();
    let edad = hoy.getFullYear() - fechaNac.getFullYear();
    const m = hoy.getMonth() - fechaNac.getMonth();
    
    if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
        edad--;
    }

    // Mostrar edad
    const display = document.getElementById(idDisplay);
    if(display) display.innerText = edad + " años";

    if(edad < 18) {
        alert("El usuario debe ser mayor de 18 años.");
        inputFecha.value = "";
        if(display) display.innerText = "";
    }
}

// 8. Sistema de Modales
function abrirModal(url) {
    const modal = document.getElementById('modal-container');
    const content = document.getElementById('modal-body');
    
    content.innerHTML = "<p style='text-align:center; padding:20px;'>Cargando...</p>";
    modal.style.display = 'flex';

    fetch('views/' + url)
    .then(r => r.text())
    .then(html => {
        content.innerHTML = html;
        // Re-ejecutar scripts dentro del modal
        const scripts = content.querySelectorAll("script");
        scripts.forEach(s => eval(s.textContent));
    });
}

function cerrarModal() {
    document.getElementById('modal-container').style.display = 'none';
}

// Dropdown Menu
function toggleMenuUser() {
    const m = document.getElementById('dropdown-user');
    m.style.display = (m.style.display === 'block') ? 'none' : 'block';
}