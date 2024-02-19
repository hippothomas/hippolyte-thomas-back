export function pictures() {
    document.getElementById("edit-btn").addEventListener("click", () => {
        document.getElementById('edit-picture').style.display = 'block';
        document.getElementById('show-picture').style.display = 'none';
    });
}