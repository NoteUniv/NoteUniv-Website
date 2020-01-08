const numEtuInput = document.getElementById("numEtu")

const NumEtu = localStorage.getItem("NumEtu");
if (NumEtu !== null) {
    numEtuInput.value = NumEtu;
}

function submitForm() {
    const num = numEtuInput.value;
    localStorage.setItem("NumEtu", num);

    $.ajax({
        url: "checkNumEtu.php?num_etu=" + num,
        type: 'GET',
        async: false,
        cache: false,
        timeout: 10000,
        error: function () {
            return true;
        },
        success: function (msg) {
            if (msg == num + " authorized") {
                document.getElementById("sendID").submit();
                $(".input").css("background-color", "#2BE8C6");
                $("#message").html("<i class='fas fa-check'></i> Ce numéro existe !");
            } else {
                $(".input").css("background-color", "#FE4C6A");
                $("#message").html("<i class='fas fa-times'></i> Ce numéro n'existe pas !");
            }
        }
    });

    /* fetch(`/checkNumEtu.php?num_etu=${num}`)
        .then(response => response.text())
        .then(message => {
            if (message == num + " authorized") {
                document.getElementById("sendID").submit();
                $(".input").css("background-color", "#2BE8C6");
                $("#message").html("<i class='fas fa-check'></i> Ce numéro existe !");
            } else {
                $(".input").css("background-color", "#FE4C6A");
                $("#message").html("<i class='fas fa-times'></i> Ce numéro n'existe pas !");
            }
        })
        .catch(error => console.error(error)) */
}


numEtuInput.addEventListener('keydown', function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        submitForm();
    }
})