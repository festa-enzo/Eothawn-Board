const botao = document.getElementById("btnNovaTarefa");
const formulario = document.getElementById("formulario");
const tarefa = document.getElementById("tarefa").value;
const dia = document.getElementById("dia").value;
const horario = document.getElementById("horario").value;


botao.addEventListener("click", () => {

    if(formulario.style.display === "block"){
        formulario.style.display = "none";
    } else {
        formulario.style.display = "block";
    }

});

console.log(tarefa);
console.log(dia);
console.log(horario);