
// ===============================
// Logout
// ===============================

function logout() {

    localStorage.removeItem("token");
    localStorage.removeItem("user");

    window.location.replace("index.html");

}

// ===============================
// Verifica autenticação
// ===============================

function verificarAutenticacao() {

    const token = localStorage.getItem("token");

    if (!token) {

        logout();

    }

}

// ===============================
// Requisições autenticadas
// ===============================

async function fetchAuth(url, options = {}) {

    verificarAutenticacao();

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