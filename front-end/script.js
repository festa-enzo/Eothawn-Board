const botao = document.getElementById("btnNovaTarefa");
const formulario = document.getElementById("formulario");
const form = document.querySelector("form");


botao.addEventListener("click", () => {

    if(formulario.style.display === "block"){
        formulario.style.display = "none";
    } else {
        formulario.style.display = "block";
    }

});

form.addEventListener("submit", (event) => {

    event.preventDefault();

    const tarefa = document.getElementById("tarefa").value;
    const dia = document.getElementById("dia").value;
    const horario = document.getElementById("horario").value;

    const card = document.createElement("div");

    card.textContent = `${horario} | ${tarefa}`;

    card.classList.add("card");

    const listas = {
        "Segunda": "segunda-lista",
        "Terça": "terca-lista",
        "Quarta": "quarta-lista",
        "Quinta": "quinta-lista",
        "Sexta": "sexta-lista",
        "Sábado": "sabado-lista",
        "Domingo": "domingo-lista"
    };

    const coluna = document.getElementById(listas[dia]);

    coluna.appendChild(card);

    form.reset();

});