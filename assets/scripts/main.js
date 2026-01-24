const btnCopyLink = document.getElementById("btn-copy-link");
const messageCopyLink = document.getElementById("message-copy-link");

if (btnCopyLink) {
    btnCopyLink.addEventListener("click", async () => {
        await navigator.clipboard.writeText(window.location.href);
        messageCopyLink.style.display = 'block';
        setTimeout(() => {
            messageCopyLink.style.display = 'none';
        }, 1000);
    })
}

function updateFileName(input) {
    const display = document.getElementById('file-name-display');

    if (input.files && input.files.length > 0) {
        const fileName = input.files[0].name;
        display.textContent = fileName;
    } else {
        display.textContent = "Aucun fichier choisi";
    }
}
