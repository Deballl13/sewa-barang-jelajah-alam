function add_duration_validation(){

    const form = document.getElementById("add_duration");
    const invalid_feedback = form.getElementsByClassName("invalid-feedback");
    let valid = true;

    if(form[0].value.trim() === ""){
        form[0].classList.add("border-danger");
        invalid_feedback[0].innerHTML = "Masukkan durasi";
        invalid_feedback[0].style.display = "block";
        valid = false;
    }
    else if(form[0].value <= 0){
        form[0].classList.add("border-danger");
        invalid_feedback[0].innerHTML = "Masukkan durasi";
        invalid_feedback[0].style.display = "block";
        valid = false;
    }
    else{
        form[0].classList.remove("border-danger");
        invalid_feedback[0].style.display = "none";
    }

    return valid;

}