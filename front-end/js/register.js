const form = document.getElementById('registerForm');
    const mensagem = document.getElementById('mensagem');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const nome = document.getElementById('nome').value.trim();
        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;

        mensagem.textContent = '';
        mensagem.style.color = 'red';

        // Validação de confirmação de senha
        if (senha !== confirmarSenha) {
            mensagem.textContent = 'As senhas não coincidem!';
            return;
        }

        if (senha.length < 8) {
            mensagem.textContent = 'A senha deve ter no mínimo 8 caracteres.';
            return;
        }

        try {
            const response = await fetch('http://api.eothawn.com/api/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nome, email, senha })
            });

            const data = await response.json();

            if (data.success) {
                mensagem.style.color = '#10b981';
                mensagem.textContent = '✅ Cadastro realizado com sucesso! Redirecionando...';
                
                setTimeout(() => {
                    window.location.href = 'login.html';
                }, 1800);
            } else {
                mensagem.textContent = data.message || 'Erro ao realizar cadastro';
            }
        } catch (error) {
            mensagem.textContent = 'Erro de conexão com o servidor';
        }
    });