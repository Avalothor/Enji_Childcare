//Toogles admin tools button image
const toggler = document.getElementById('toggler');
const icon = document.getElementById('icon');

toggler.addEventListener('click', event => {
    icon.classList.toggle('bi-layout-sidebar-inset');
    icon.classList.toggle('bi-layout-sidebar-inset-reverse');
})

//Toggles Tooltips
$(function () {
    $("[data-bs-toggle='tooltip']").tooltip({ trigger: "hover" });
});

