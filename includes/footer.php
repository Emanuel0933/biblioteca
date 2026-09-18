</main>
<footer class="footer">
    <p>Sistema de Biblioteca </p>
</footer>
<script>
function actualizarReloj() {
    const ahora = new Date();
    const opciones = { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    const texto = ahora.toLocaleDateString('es-ES', opciones);
    const el = document.getElementById('reloj');
    if (el) el.textContent = texto.charAt(0).toUpperCase() + texto.slice(1);
}
actualizarReloj();
setInterval(actualizarReloj, 1000);
</script>
</body>
</html>
