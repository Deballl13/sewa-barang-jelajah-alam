const form = document.getElementById("storePinjam");
const invalid_feedback = form.getElementsByClassName("invalid-feedback");


function showModal(n) {
    // <operator perbandingan, manipulasi object>
    if (n == 1 && validation01()) form[6].setAttribute("data-bs-toggle", "modal");
    else if (n == -1) form[6].removeAttribute("data-bs-toggle");
}


function validation01() {
    let valid = true;

    for (let i = 0; i < 6; i++) {
        if (form[i].value.trim() === "") {
            if (form[i] == form[0]) invalid_feedback[i].innerHTML = "Masukkan nik";
            else if (form[i] == form[1]) invalid_feedback[i].innerHTML = "Masukkan nama";
            else if (form[i] == form[2]) invalid_feedback[i].innerHTML = "Masukkan no hp";
            else if (form[i] == form[3]) invalid_feedback[i].innerHTML = "Masukkan alamat";
            else if (form[i] == form[4]) invalid_feedback[i].innerHTML = "Masukkan tanggal peminjaman";
            else if (form[i] == form[5]) invalid_feedback[i].innerHTML = "Masukkan durasi peminjaman";

            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i] == form[0] && form[i].value.length < 16) {
            invalid_feedback[i].innerHTML = "Nik anda kurang lengkap";
            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i] == form[1] && !form[i].value.match(/^[a-zA-Z ]+$/)) {
            invalid_feedback[i].innerHTML = "Nama hanya boleh huruf";
            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i] == form[2] && form[i].value.length < 11) {
            invalid_feedback[i].innerHTML = "No hp anda kurang lengkap";
            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i] == form[2] && !form[i].value.match(/^[0]{1}[8]{1}[-\s\./0-9]*$/g)) {
            invalid_feedback[i].innerHTML = "Format tidak sesuai";
            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i] == form[3] && form[i].value.length < 10) {
            invalid_feedback[i].innerHTML = "Alamat anda kurang lengkap";
            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else if (form[i] == form[5] && form[i].value <= 0) {
            invalid_feedback[i].innerHTML = "Masukkan durasi dengan benar";
            form[i].classList.add("border-danger");
            invalid_feedback[i].style.display = "block";
            valid = false;
        } else {
            form[i].classList.remove("border-danger");
            invalid_feedback[i].style.display = "none";
        }
    }

    return valid;
}

// <object, array>
var barang = {};
var brg = [];
var qty = [];
var field_brg = [];
var field_qty = [];

for (let i = 8; i < form.length - 1; i++) {
    // <operator perbandingan, manipulasi object, method in object>
    if (i % 2 == 0) {
        barang.kode = () => {
            brg.push(form[i]);
            return brg;
        };
        continue;
    } else {
        barang.qty = () => {
            qty.push(form[i]);
            return qty;
        };
    }

    field_brg = barang.kode();
    field_qty = barang.qty();
}

// <variabel, dom>
var toast = document.getElementsByClassName("toast-view")[0];

for (let i = 0; i < field_brg.length; i++) {
    // <event, manipulasi object>
    field_brg[i].addEventListener("click", function() {
        field_qty[i].toggleAttribute("disabled");
        if (field_qty[i].hasAttribute("disabled")) {
            field_qty[i].value = "";
            field_qty[i].classList.remove("border-danger");
        } else {
            var stock = field_qty[i].getAttribute("stock");
            var nama_barang = field_qty[i].getAttribute("nama_barang");
            field_qty[i].addEventListener("input", function() {
                if (parseInt(field_qty[i].value) > stock) {
                    // <pemrosesan string, method string>
                    toast.innerHTML = "Jumlah " + nama_barang.toLowerCase() + " melebihi stock";
                    setTimeout(showToast, 500);
                    setTimeout(hideToast, 2000);
                    field_qty[i].classList.add("border-danger");
                } else {
                    field_qty[i].classList.remove("border-danger");
                }
            });
        }
    });
}

// <function, args, dan return>
function showToast() {
    toast.style.display = "block";
}

// <function, args, dan return>
function hideToast() {
    toast.style.display = "none";
}

// <function, args, dan return>
function validation02() {
    // <variabel, properti>
    let count = 0;
    let valid = true;
    let nama_empty = [];
    let nama_more = [];

    for (let i = 0; i < field_brg.length; i++) {
        if (field_brg[i].checked == true) {
            // <aritmatika>
            count++;
        }
    }

    // <operator perbandingan, manipulasi object>
    if (count == 0) {
        toast.innerHTML = "Anda belum memilih barang yang akan disewa";
        setTimeout(showToast, 500);
        setTimeout(hideToast, 2000);
        valid = false;
    }

    for (let i = 0; i < field_qty.length; i++) {
        var stock = field_qty[i].getAttribute("stock");
        var nama_barang = field_qty[i].getAttribute("nama_barang");

        if (!field_qty[i].hasAttribute("disabled")) {
            if (field_qty[i].value.trim() == "") {
                nama_empty.push(nama_barang);
                valid = false;
            } else if (parseInt(field_qty[i].value) > stock) {
                nama_more.push(nama_barang);
                valid = false;
            }
        }
    }

    if (nama_empty.length > 0) {
        toast.innerHTML = "Jumlah " + nama_empty + " masih kosong";
        setTimeout(showToast, 500);
        setTimeout(hideToast, 2000);
    }
    if (nama_more.length > 0) {
        toast.innerHTML = "Jumlah " + nama_more + " melebihi stock";
        setTimeout(showToast, 500);
        setTimeout(hideToast, 2000);
    }

    return valid;
}