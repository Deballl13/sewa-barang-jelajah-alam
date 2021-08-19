function payment_validation(total){

    const form = document.getElementById("pembayaranDP");
    const invalid_feedback = form.getElementsByClassName("invalid-feedback");
    let valid = true;

    if(form[0].value.trim() === ""){
        form[0].classList.add("border-danger");
        invalid_feedback[0].innerHTML = "Masukkan nominal";
        invalid_feedback[0].style.display = "block";
        valid = false;
    }
    else if(form[0].value <= 0){
        form[0].classList.add("border-danger");
        invalid_feedback[0].innerHTML = "Masukkan nominal";
        invalid_feedback[0].style.display = "block";
        valid = false;
    }
    else if(form[0].value > total){
        form[0].classList.add("border-danger");
        invalid_feedback[0].innerHTML = "Nominal berlebih";
        invalid_feedback[0].style.display = "block";
        valid = false;
    }
    else{
        form[0].classList.remove("border-danger");
        invalid_feedback[0].style.display = "none";
    }

    return valid;

}
