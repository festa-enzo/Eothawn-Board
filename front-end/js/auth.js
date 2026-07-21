// ===============================
// Verifica se o usuário está logado
// ===============================

function verificarAutenticacao() {

    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "login.html";
    }

}

// ===============================
// Logout
// ===============================

function logout() {

    localStorage.removeItem("token");
    localStorage.removeItem("user");

    window.location.href = "login.html";

}

// ===============================
// Requisições autenticadas
// ===============================

async function fetchAuth(url, options = {}) {

    const token = localStorage.getItem("token");

    options.headers = {
        ...options.headers,
        Authorization: `Bearer ${token}`
    };

    const response = await fetch(url, options);

    if (response.status === 401) {

        logout();
        return null;

    }

    return response;

}