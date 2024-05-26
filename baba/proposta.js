document.addEventListener("DOMContentLoaded", function() {
    const aceitaButtons = document.querySelectorAll(".btn-aceita");
    const recusaButtons = document.querySelectorAll(".btn-recusa");
    const enviarRecusaButtons = document.querySelectorAll(".btn-enviar-recusa");

    aceitaButtons.forEach(button => {
        button.addEventListener("click", function() {
            const propostaId = this.getAttribute("data-proposta-id");
            const dataAceite = new Date().toISOString().slice(0, 19).replace('T', ' ');  // Data no formato YYYY-MM-DD HH:MM:SS
            $.ajax({
                url: 'atualizar_proposta.php',
                type: 'POST',
                data: { idProposta: propostaId, estado: 1, dataAceite: dataAceite },
                success: function(response) {
                    if (response.trim() === "success") {
                        alert("Proposta aceita com sucesso");
                        location.reload();
                    } else {
                        alert("Erro ao aceitar a proposta: " + response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro AJAX: ", textStatus, errorThrown);
                    console.error("Resposta do servidor: ", jqXHR.responseText);
                    alert("Erro ao aceitar a proposta: " + textStatus + " - " + errorThrown);
                }
            });
        });
    });

    recusaButtons.forEach(button => {
        button.addEventListener("click", function() {
            const modalBody = this.closest(".modal-body");
            const modalRecusa = modalBody.nextElementSibling;
            modalBody.classList.add("hide");
            modalRecusa.classList.remove("hide");
        });
    });

    enviarRecusaButtons.forEach(button => {
        button.addEventListener("click", function() {
            const propostaId = this.getAttribute("data-proposta-id");
            const motivo = this.closest('.modal-recusa').querySelector('textarea').value;
            const dataRecusa = new Date().toISOString().slice(0, 19).replace('T', ' ');  // Data no formato YYYY-MM-DD HH:MM:SS
            $.ajax({
                url: 'atualizar_proposta.php',
                type: 'POST',
                data: { idProposta: propostaId, estado: 0, motivoRecusa: motivo, dataRecusa: dataRecusa },
                success: function(response) {
                    if (response.trim() === "success") {
                        alert("Proposta recusada com sucesso");
                        location.reload();
                    } else {
                        alert("Erro ao recusar a proposta: " + response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro AJAX: ", textStatus, errorThrown);
                    console.error("Resposta do servidor: ", jqXHR.responseText);
                    alert("Erro ao recusar a proposta: " + textStatus + " - " + errorThrown);
                }
            });
        });
    });

    const openModalButtons = document.querySelectorAll(".open-modal");
    const closeModalButtons = document.querySelectorAll(".close-modal");
    const fades = document.querySelectorAll(".fade");
    const modals = document.querySelectorAll(".modal");

    openModalButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
            modals[index].classList.remove("hide");
            fades[index].classList.remove("hide");
        });
    });

    closeModalButtons.forEach((button, index) => {
        button.addEventListener("click", () => {
            modals[index].classList.add("hide");
            fades[index].classList.add("hide");
        });
    });

    fades.forEach((fade, index) => {
        fade.addEventListener("click", () => {
            modals[index].classList.add("hide");
            fade.classList.add("hide");
        });
    });
});
