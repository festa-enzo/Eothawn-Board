const botao = document.getElementById("btnNovaTarefa");
const formulario = document.getElementById("formulario");
const form = document.querySelector("form");
let tarefaEditando = null;

const API = "http://api.eothawn.com/api/tasks";

// ===============================
// Mostrar / esconder formulário
// ===============================

botao.addEventListener("click", () => {

    formulario.style.display =
        formulario.style.display === "block"
            ? "none"
            : "block";

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

    let url = API;
    let metodo = "POST";

    if (tarefaEditando !== null) {
        url = `${API}/${tarefaEditando}`;
        metodo = "PUT";
    }

    try {

        const response = await fetch(url, {

            method: metodo,

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

        tarefaEditando = null;

        form.reset();

        form.querySelector("button[type='submit']").textContent = "Salvar";

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

        if (!response.ok) {
            alert(data.message);
            return;
        }

        limparColunas();

        data.tasks.forEach(task => {

            adicionarCard(
                task.task_id,
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

function adicionarCard(id, tarefa, dia, horario) {

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

    if (!coluna) return;

    const card = document.createElement("div");

    card.classList.add("card");
    card.dataset.id = id;

    card.innerHTML = `
        <span>${horario} | ${tarefa}</span>
        <button class="btnExcluir">🗑</button>
    `;

    card.addEventListener("click", () => {

        editarTask({
            id: id,
            titulo: tarefa,
            dia: dia,
            horario: horario
        });

    });

    card.querySelector(".btnExcluir").addEventListener("click", async (event) => {

        event.stopPropagation();

        await excluirTask(id);

    });

    coluna.appendChild(card);

}

// ===============================
// Alterar Tarefa
// ===============================
    function editarTask(task){

        tarefaEditando = task.id;

        document.getElementById("tarefa").value = task.titulo;
        document.getElementById("dia").value = task.dia;
        document.getElementById("horario").value = task.horario;

        formulario.style.display = "block";

        form.querySelector("button[type='submit']").textContent = "Atualizar";

}
// ===============================
// Excluir tarefa
// ===============================

async function excluirTask(taskId) {

    if (!confirm("Deseja excluir esta tarefa?"))
        return;

    const token = localStorage.getItem("token");

    try {

        const response = await fetch(`${API}/${taskId}`, {

            method: "DELETE",

            headers: {

                "Authorization": `Bearer ${token}`

            }

        });

        const data = await response.json();

        if (!response.ok) {

            alert(data.message);
            return;

        }

        carregarTarefas();

    } catch (erro) {

        console.error(erro);

        alert("Erro ao excluir tarefa.");

    }

}

// ===============================
// Limpar colunas
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

        const coluna = document.getElementById(id);

        if (coluna) {
            coluna.innerHTML = "";
        }

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