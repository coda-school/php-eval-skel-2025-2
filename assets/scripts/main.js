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
