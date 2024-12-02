document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tabs label');
    const tabContents = document.querySelectorAll('.tab-content');
    const loginContainer = document.getElementById('login-container');
    const passwordChangeContainer = document.getElementById('password-change-container');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');

            tabs.forEach(tab => tab.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    document.querySelector('.edit-profile-button').addEventListener('click', function() {
        loginContainer.style.display = 'flex';
    });

    document.querySelector('.close-button').addEventListener('click', function() {
        loginContainer.style.display = 'none';
    });
});

