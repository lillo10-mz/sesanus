<?php
require_once 'header.php';
?>

<div class="contacto-container">
    <h1>Contacto</h1>
    <p>¿Tienes alguna duda o quieres más información? Rellena el formulario y nos pondremos en contacto contigo lo antes posible.</p>

    <form class="formulario-contacto">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" placeholder="tucorreo@ejemplo.com" required>

        <label for="asunto">Asunto:</label>
        <input type="text" id="asunto" name="asunto" placeholder="Motivo de tu mensaje" required>

        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>

        <div class="botones">
            <button type="submit">Enviar mensaje</button>
        </div>
    </form>
</div>

<?php
require_once 'footer.php';
?>