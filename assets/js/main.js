const numEtuInput = document.getElementById("numEtu");
const NumEtu = localStorage.getItem("NumEtu");
let error = false;

// Defaut numEtu value is the value in localStorage
if (NumEtu !== null) {
    numEtuInput.value = NumEtu;
}

/*
 * Submit the form to the checkNumEtu file
 * If response is authorized : It will submit the form
 * If the num is not valid : show an error message
 * It will also set the value of the input into the localStorage key 'NumEtu'
 */
function submitForm() {
    const num = numEtuInput.value;
    localStorage.setItem("NumEtu", num);

    fetch(`/checkNumEtu.php?num_etu=${num}`)
        .then(response => response.text())
        .then(message => {
            if (message == num + " authorized") {
                document.getElementById("inputAll").style.backgroundColor = "#2BE8C6";
                document.getElementById("showMessage").innerHTML = "<i class='fas fa-check'></i> Ce numéro existe !";
                document.getElementById("showMessage").style.color = "#2BE8C6";
                document.getElementById("sendID").submit();
            } else {
                error = true;
                document.getElementById("inputAll").style.backgroundColor = "#FE4C6A";
                document.getElementById("showMessage").innerHTML = "<i class='fas fa-times'></i> Ce numéro n'existe pas !";
                document.getElementById("showMessage").style.color = "#FE4C6A";
            }
        })
        .catch(error => console.error(error))
}

// If enter on the whole document -> call the submitForm() function
document.addEventListener('keydown', function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        submitForm();
    }
}, false);

// If we type after an error it will clear the style of the input for clarity
numEtuInput.addEventListener('input', () => {
    if (!error) return;
    error = false;
    document.getElementById("inputAll").style.backgroundColor = "#FFFFFF";
    document.getElementById("showMessage").innerHTML = "";
    document.getElementById("showMessage").style.color = "";
})

numEtuInput.addEventListener('focus', () => {
    if (window.innerWidth <= 760) {
        document.getElementsByClassName("logo-txt")[0].style.display = "none";
    }
});

numEtuInput.addEventListener('blur', () => {
    if (window.innerWidth <= 760) {
        document.getElementsByClassName("logo-txt")[0].style.display = "inline";
    }
});