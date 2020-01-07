var NumEtu = localStorage.getItem("NumEtu");
if (NumEtu !== null) {
    document.getElementById("numEtu").value = NumEtu;
}

function submitForm() {
    var num = document.getElementById("numEtu").value;
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
}

document.onkeydown = function (e) {
    if (e.key === "Enter") {
        e.preventDefault();
        submitForm();
    }
}