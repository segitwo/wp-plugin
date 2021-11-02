window.onload = function () {
    let addClothesForm = document.getElementById("add-clothes-form");
    if (addClothesForm) {
        addClothesForm.addEventListener("submit", onSubmit, false);

        function onSubmit(e) {
            alert();
            e.preventDefault();
        }
    }
}
