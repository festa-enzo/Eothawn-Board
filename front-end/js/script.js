const botao = document.getElementById("btnNovaTarefa");
const formulario = document.getElementById("formulario");
const form = document.querySelector("form");

const API = "http://api.eothawn.com/api/tasks";

// ===============================
// Mostrar / esconder formulário
// ===============================

botao.addEventListener("click", () => {

    if(formulario.style.display === "block"){
        formulario.style.display = "none";
    } else {
        formulario.style.display = "block";
    }

});
// ===============================
// Quando abrir a página
// ===============================

document.addEventListener("DOMContentLoaded", () => {
    carregarTarefas();
});

// ===============================
// Salvar tarefa
// ===============================

form.addEventListener("submit", async (event) => {

    event.preventDefault();

    const tarefa = document.getElementById("tarefa").value;
    const dia = document.getElementById("dia").value;
    const horario = document.getElementById("horario").value;

    const token = localStorage.getItem("token");

    try {

        const response = await fetch(API, {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            },

            body: JSON.stringify({

                title: tarefa,
                column_id: converterDia(dia),
                time_task: horario,
                is_recurring: 0

            })

        });

        const data = await response.json();

        if (!response.ok) {
            alert(data.message);
            return;
        }

        form.reset();
        formulario.style.display = "none";

        carregarTarefas();

    } catch (erro) {

        console.error(erro);
        alert("Erro ao salvar tarefa.");

    }

});

// ===============================
// Buscar tarefas
// ===============================

async function carregarTarefas() {

    const token = localStorage.getItem("token");

    try {

        const response = await fetch(API, {

            headers: {
                "Authorization": `Bearer ${token}`
            }

        });

        const data = await response.json();

        limparColunas();

        data.tasks.forEach(task => {

            adicionarCard(
                task.title,
                converterNumero(task.column_id),
                task.time_task
            );

        });

    } catch (erro) {

        console.error(erro);

    }

}

// ===============================
// Criar Card
// ===============================

function adicionarCard(tarefa, dia, horario) {

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

    const card = document.createElement("div");

    card.classList.add("card");

    card.textContent = `${horario} | ${tarefa}`;

    coluna.appendChild(card);

}

// ===============================
// Limpar todas as colunas
// ===============================

function limparColunas() {

    const colunas = [

        "segunda-lista",
        "terca-lista",
        "quarta-lista",
        "quinta-lista",
        "sexta-lista",
        "sabado-lista",
        "domingo-lista"

    ];

    colunas.forEach(id => {

        document.getElementById(id).innerHTML = "";

    });

}

// ===============================
// Segunda -> 1
// ===============================

function converterDia(dia) {

    const dias = {

        "Segunda": 1,
        "Terça": 2,
        "Quarta": 3,
        "Quinta": 4,
        "Sexta": 5,
        "Sábado": 6,
        "Domingo": 7

    };

    return dias[dia];

}

// ===============================
// 1 -> Segunda
// ===============================

function converterNumero(id) {

    const dias = {

        1: "Segunda",
        2: "Terça",
        3: "Quarta",
        4: "Quinta",
        5: "Sexta",
        6: "Sábado",
        7: "Domingo"

    };

    return dias[id];

}