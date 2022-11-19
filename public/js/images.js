window.onload = () => {
    // Gestion des boutons "Supprimer"
    let links = document.querySelectorAll("[data-delete]");

    // On boucle sur links
    for (link of links) {
        // On écoute le clic
        link.addEventListener("click", function (e) {
            e.preventDefault();
            if (confirm("Voulez-vous supprimer cette image ?")) {
                fetch(this.getAttribute("href"), {
                    method: "post",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: JSON.stringify({ _token: this.dataset.token }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data)
                        if (data.success){
                            this.parentElement.remove()
                        } 
                        else {
                            alert(data.error)
                        };
                    })
                    .catch((e) => alert(e));
            }
        });
    }
};
