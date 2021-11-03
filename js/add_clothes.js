window.onload = function () {
    let addClothesForm = document.getElementById("add-clothes-form");
    if (addClothesForm) {
        addClothesForm.addEventListener("submit", onSubmit, false);

        function onSubmit(e) {
            const formData = new FormData();
            const userId = document.getElementById("user-id").value;
            const title = document.getElementById("clothes-title").value;
            const description = document.getElementById("clothes-description").value;
            const thumbnail = document.getElementById("clothes-thumbnail");
            const size = document.getElementById("clothes-size").value;
            const color = document.getElementById("clothes-color").value;
            const sex = document.getElementById("clothes-sex").value;
            const type = Array.from(document.getElementById("clothes-type").options)
                .filter(opt => opt.selected)
                .map(opt => opt.value);

            formData.append("user_id", userId);
            formData.append("nonce", window.ACAjax.nonce)
            formData.append("action", "add_clothes");
            formData.append("title", title);
            formData.append("description", description);
            formData.append("thumbnail", thumbnail.files[0]);
            formData.append("size", size);
            formData.append("color", color);
            formData.append("sex", sex);
            formData.append("type", type);

            fetch(window.ACAjax.ajaxurl, {
                method: "POST",
                body: formData,
            }).then((resp) => {
                console.log();
            });
            e.preventDefault();
        }
    }

}
