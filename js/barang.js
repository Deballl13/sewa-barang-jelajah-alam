// <function, argumen, dan return>
function validation(action = "store") {
    // <variabel, properti, dom>
    const form = document.getElementById("barang");
    const text_feedback = form.getElementsByClassName("invalid-feedback");
    let valid = true;
    // <aritmatika>
    let length = form.length - 3;

    // <operator perbandingan>
    if (action == "update") {
        // <aritmatika>
        length = form.length - 2;
    }
    // cek input
    for (let i = 0; i < length; i++) {
        // <variabel, manipulasi object>
        if (form[i].value.trim() == "") {
            if (form[i] == form[0]) text_feedback[i].innerHTML = "Masukkan nama barang";
            else if (form[i] == form[1]) text_feedback[i].innerHTML = "Masukkan harga barang";
            else if (form[i] == form[2]) text_feedback[i].innerHTML = "Masukkan stock barang";

            form[i].classList.add("border-danger");
            text_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i].value <= 0) {
            if (form[i] == form[1]) text_feedback[i].innerHTML = "Masukkan harga barang dengan benar";
            else if (form[i] == form[2]) text_feedback[i].innerHTML = "Stock harus besar dari 0";

            form[i].classList.add("border-danger");
            text_feedback[i].style.display = "block";
            valid = false;
        } else {
            form[i].classList.remove("border-danger");
            text_feedback[i].style.display = "none";
        }
    }
    return valid;
}