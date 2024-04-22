// Obtener elementos
const passwordInput = document.getElementById("passwordInput");
const togglePassword = document.getElementById("togglePassword");

// Agregar evento click al botón
togglePassword.addEventListener("click", function () {
    // Cambiar el tipo de entrada de la contraseña
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePassword.innerHTML = '<span class="far fa-eye"></span>'; // Cambiar el icono a un ojo abierto
    } else {
        passwordInput.type = "password";
        togglePassword.innerHTML = '<span class="far fa-eye-slash"></span>'; // Cambiar el icono a un ojo tachado
    }
});

const state = {};
const carouselList = document.querySelector(".carousel__list");
const carouselItems = document.querySelectorAll(".carousel__item");
const elems = Array.from(carouselItems);

carouselList.addEventListener("click", function (event) {
    var newActive = event.target;
    var isItem = newActive.closest(".carousel__item");

    if (!isItem || newActive.classList.contains("carousel__item_active")) {
        return;
    }

    update(newActive);
});

const update = function (newActive) {
    const newActivePos = newActive.dataset.pos;

    const current = elems.find((elem) => elem.dataset.pos == 0);
    const prev = elems.find((elem) => elem.dataset.pos == -1);
    const next = elems.find((elem) => elem.dataset.pos == 1);
    const first = elems.find((elem) => elem.dataset.pos == -2);
    const last = elems.find((elem) => elem.dataset.pos == 2);

    current.classList.remove("carousel__item_active");

    [current, prev, next, first, last].forEach((item) => {
        var itemPos = item.dataset.pos;

        item.dataset.pos = getPos(itemPos, newActivePos);
    });
};

const getPos = function (current, active) {
    const diff = current - active;

    if (Math.abs(current - active) > 2) {
        return -current;
    }

    return diff;
};
