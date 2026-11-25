document.addEventListener('DOMContentLoaded', () => {
    const editProfileForm = document.getElementById('editProfileForm');
    if (editProfileForm) {
        editProfileForm.addEventListener('submit', (e) => {
            let isValid = true;
            const nom = document.getElementById('nom');
            const prenom = document.getElementById('prenom');
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();

            if (document.getElementById('nomError')) document.getElementById('nomError').textContent = '';
            if (document.getElementById('prenomError')) document.getElementById('prenomError').textContent = '';
            document.getElementById('usernameError').textContent = '';
            document.getElementById('emailError').textContent = '';

            if (nom && nom.value.trim().length < 2) {
                document.getElementById('nomError').textContent = "Le nom doit contenir au moins 2 caractères.";
                isValid = false;
            }

            if (prenom && prenom.value.trim().length < 2) {
                document.getElementById('prenomError').textContent = "Le prénom doit contenir au moins 2 caractères.";
                isValid = false;
            }

            if (username.length < 3) {
                document.getElementById('usernameError').textContent = "Le nom d'utilisateur doit faire au moins 3 caractères.";
                isValid = false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = "Veuillez saisir une adresse email valide.";
                isValid = false;
            }

            if (!isValid) e.preventDefault();
        });
    }

    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', (e) => {
            let isValid = true;
            const newPwd = document.getElementById('new_password').value;
            const confirmPwd = document.getElementById('confirm_password').value;

            document.getElementById('newPwdError').textContent = '';
            document.getElementById('confirmPwdError').textContent = '';

            if (newPwd.length < 8) {
                document.getElementById('newPwdError').textContent = "Le mot de passe doit contenir au moins 8 caractères.";
                isValid = false;
            }

            if (newPwd !== confirmPwd) {
                document.getElementById('confirmPwdError').textContent = "La confirmation ne correspond pas au nouveau mot de passe.";
                isValid = false;
            }

            if (!isValid) e.preventDefault();
        });
    }
});
