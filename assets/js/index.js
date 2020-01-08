const numEtuInput = document.getElementById("numEtu")
const NumEtu = localStorage.getItem("NumEtu");

if (NumEtu !== null) {
    numEtuInput.value = NumEtu;
}

function submitForm() {
    const num = numEtuInput.value;
    localStorage.setItem("NumEtu", num);

    fetch(`/checkNumEtu.php?num_etu=${num}`)
        .then(response => response.text())
        .then(message => {
            if (message == num + " authorized") {
                document.getElementById("inputAll").style.backgroundColor = "#2BE8C6";
                document.getElementById("ShowMessage").innerHTML = "<i class='fas fa-check'></i> Ce numéro existe !";
                document.getElementById("sendID").submit();
            } else {
                document.getElementById("inputAll").style.backgroundColor = "#FE4C6A";
                document.getElementById("ShowMessage").innerHTML = "<i class='fas fa-times'></i> Ce numéro n'existe pas !";
            }
        })
        .catch(error => console.error(error))
}

document.addEventListener('keydown', function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        submitForm();
    }
}, false);