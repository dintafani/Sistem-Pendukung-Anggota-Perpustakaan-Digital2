// =========================================================
// FILTER BUTTON
// =========================================================

const filterTabs = document.getElementById("filter-tabs");

if (filterTabs) {

    filterTabs.addEventListener("click", function (e) {

        if (e.target.tagName === "BUTTON") {

            document
                .querySelectorAll("#filter-tabs button")
                .forEach(function (button) {

                    button.classList.remove("tab-active");

                });

            e.target.classList.add("tab-active");

        }

    });

}


// =========================================================
// MODAL
// =========================================================

const loginModal = document.getElementById("loginModal");
const registerModal = document.getElementById("registerModal");


// =========================================================
// TOMBOL NAVBAR
// =========================================================

const openLogin = document.getElementById("openLogin");
const openRegister = document.getElementById("openRegister");


// =========================================================
// TOMBOL HERO
// =========================================================

const openLoginStudent =
    document.getElementById("openLoginStudent");

const openLoginStaff =
    document.getElementById("openLoginStaff");


// =========================================================
// TOMBOL CTA
// =========================================================

const openRegisterCta =
    document.getElementById("openRegisterCta");


// =========================================================
// TOMBOL CLOSE
// =========================================================

const closeLogin =
    document.getElementById("closeLogin");

const closeRegister =
    document.getElementById("closeRegister");


// =========================================================
// LINK LOGIN → REGISTER
// =========================================================

const loginToRegister =
    document.getElementById("loginToRegister");


// =========================================================
// LINK REGISTER → LOGIN
// =========================================================

const backToLogin =
    document.getElementById("backToLogin");


// =========================================================
// FUNGSI BUKA LOGIN
// =========================================================

function showLoginModal(event) {

    if (event) {
        event.preventDefault();
    }

    // Tutup register
    if (registerModal) {

        registerModal.classList.remove("show");

    }

    // Buka login
    if (loginModal) {

        loginModal.classList.add("show");

        document.body.classList.add("modal-open");

    }

}


// =========================================================
// FUNGSI BUKA REGISTER
// =========================================================

function showRegisterModal(event) {

    if (event) {
        event.preventDefault();
    }

    // Tutup login
    if (loginModal) {

        loginModal.classList.remove("show");

    }

    // Buka register
    if (registerModal) {

        registerModal.classList.add("show");

        document.body.classList.add("modal-open");

    }

}


// =========================================================
// FUNGSI TUTUP LOGIN
// =========================================================

function hideLoginModal() {

    if (loginModal) {

        loginModal.classList.remove("show");

    }

    checkModalStatus();

}


// =========================================================
// FUNGSI TUTUP REGISTER
// =========================================================

function hideRegisterModal() {

    if (registerModal) {

        registerModal.classList.remove("show");

    }

    checkModalStatus();

}


// =========================================================
// CEK MODAL YANG MASIH TERBUKA
// =========================================================

function checkModalStatus() {

    const loginOpen =
        loginModal &&
        loginModal.classList.contains("show");

    const registerOpen =
        registerModal &&
        registerModal.classList.contains("show");


    if (!loginOpen && !registerOpen) {

        document.body.classList.remove("modal-open");

    }

}


// =========================================================
// NAVBAR - MASUK
// =========================================================

if (openLogin) {

    openLogin.addEventListener("click", function (event) {

        showLoginModal(event);

    });

}


// =========================================================
// NAVBAR - DAFTAR
// =========================================================

if (openRegister) {

    openRegister.addEventListener("click", function (event) {

        showRegisterModal(event);

    });

}


// =========================================================
// HERO - SISWA
// =========================================================

if (openLoginStudent) {

    openLoginStudent.addEventListener("click", function (event) {

        showLoginModal(event);

    });

}


// =========================================================
// HERO - PETUGAS
// =========================================================

if (openLoginStaff) {

    openLoginStaff.addEventListener("click", function (event) {

        showLoginModal(event);

    });

}


// =========================================================
// CTA - DAFTAR
// =========================================================

if (openRegisterCta) {

    openRegisterCta.addEventListener("click", function (event) {

        showRegisterModal(event);

    });

}


// =========================================================
// CLOSE LOGIN
// =========================================================

if (closeLogin) {

    closeLogin.addEventListener("click", function (event) {

        event.preventDefault();

        hideLoginModal();

    });

}


// =========================================================
// CLOSE REGISTER
// =========================================================

if (closeRegister) {

    closeRegister.addEventListener("click", function (event) {

        event.preventDefault();

        hideRegisterModal();

    });

}


// =========================================================
// LOGIN → REGISTER
// =========================================================

if (loginToRegister) {

    loginToRegister.addEventListener("click", function (event) {

        showRegisterModal(event);

    });

}


// =========================================================
// REGISTER → LOGIN
// =========================================================

if (backToLogin) {

    backToLogin.addEventListener("click", function (event) {

        showLoginModal(event);

    });

}


// =========================================================
// KLIK BACKDROP / AREA GELAP
// =========================================================

window.addEventListener("click", function (event) {

    if (event.target === loginModal) {

        hideLoginModal();

    }

    if (event.target === registerModal) {

        hideRegisterModal();

    }

});


// =========================================================
// ESC UNTUK MENUTUP MODAL
// =========================================================

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {

        hideLoginModal();

        hideRegisterModal();

    }

});


// =========================================================
// SHOW / HIDE PASSWORD
// =========================================================

function togglePassword(inputId, button) {

    const input =
        document.getElementById(inputId);

    if (!input) {

        return;

    }


    if (input.type === "password") {

        input.type = "text";

        button.textContent = "🙈";

    } else {

        input.type = "password";

        button.textContent = "👁";

    }

}


// =========================================================
// REGISTER BERHASIL
// =========================================================
// Jika Laravel mengirim session register_success,
// modal register otomatis dibuka.
// =========================================================

const registerSuccess =
    document.querySelector(".register-popup-success");

if (registerSuccess && registerModal) {

    registerModal.classList.add("show");

    document.body.classList.add("modal-open");

}