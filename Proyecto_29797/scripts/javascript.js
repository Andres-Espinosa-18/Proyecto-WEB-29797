// JavaScript Document

const soloTexto = /^[A-Za-zÁÉÍÓÚáéíóúüÜ ]*$/;
const soloNum = /^[0-9]*$/;
function validarSoloTexto(e) {
	let tecla = e.key;
	
	if (!soloTexto.test(tecla) && tecla !=="Backspace") e.preventDefault();
}
function validarSoloNumero(e) {
	let tecla = e.key;
	
	if (!soloNum.test(tecla) && tecla !=="Backspace") e.preventDefault();
}

function productoContenido() {
	document.getElementById("prodID").value = "id1";
	document.getElementById("prodDescripcion").value = "descripcion";
	document.getElementById("prodPresentacion").value = "unidades";
}

function validarCed() {
    let cedulaInput = document.getElementById("cedula").value;
	let cedula = cedulaInput.split('').map(Number);
    let suma = 0;
    let ultimoDigito = cedula[9]; 

    for (let pos = 0; pos < 9; pos++) {
        let digito = cedula[pos];
        if (pos % 2 == 0) {
            digito *= 2;
            if (digito > 9) {
                digito -= 9;
            }
        }
		suma += digito;
    }

    let verificador = (10 - (suma % 10))%10;
 
    if (verificador === ultimoDigito) {
        document.getElementById("valced").innerHTML = "";
    } else {
        document.getElementById("valced").innerHTML = "* Cedula incorrecta";;
    }
}

function programa(e) {

}

document.getElementById("form1").addEventListener('submit', programa);

