<h1>Nueva solicitud</h1>

<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="bloque">
    <form method="POST" action="<?php echo BASE_URL; ?>/?page=incidencia_store">
        <div>
            <label for="especialidad_id">Tipo de servicio:</label><br>
            <select id="especialidad_id" name="especialidad_id" required>
                <option value="">Selecciona un servicio</option>
                <?php foreach ($especialidades as $especialidad): ?>
                    <option value="<?php echo $especialidad['id']; ?>" <?php echo (!empty($_POST['especialidad_id']) && (int) $_POST['especialidad_id'] === (int) $especialidad['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($especialidad['nombre_especialidad']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <div>
            <label for="descripcion">Descripción de la avería:</label><br>
            <textarea id="descripcion" name="descripcion" rows="5" required><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
        </div>
        <br>

        <div>
            <label for="direccion">Dirección:</label><br>
            <input type="text" id="direccion" name="direccion" required value="<?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?>">
        </div>
        <br>

        <div>
            <label for="telefono_contacto">Teléfono de contacto:</label><br>
            <input type="text" id="telefono_contacto" name="telefono_contacto" required value="<?php echo htmlspecialchars($_POST['telefono_contacto'] ?? ($_SESSION['usuario']['telefono'] ?? '')); ?>">
        </div>
        <br>

        <div>
            <label for="fecha">Fecha solicitada:</label><br>
            <input type="date" id="fecha" name="fecha" required value="<?php echo htmlspecialchars($_POST['fecha'] ?? ''); ?>">
        </div>
        <br>

        <div>
            <label for="franja_horaria">Franja horaria:</label><br>
            <select id="franja_horaria" name="franja_horaria" required>
                <option value="">Selecciona una franja</option>
                <option value="09:00-13:00" <?php echo (($_POST['franja_horaria'] ?? '') === '09:00-13:00') ? 'selected' : ''; ?>>09:00-13:00</option>
                <option value="16:00-20:00" <?php echo (($_POST['franja_horaria'] ?? '') === '16:00-20:00') ? 'selected' : ''; ?>>16:00-20:00</option>
            </select>
        </div>
        <br>

        <div>
            <label for="tipo_urgencia">Urgencia:</label><br>
            <select id="tipo_urgencia" name="tipo_urgencia" required>
                <option value="Estándar" <?php echo (($_POST['tipo_urgencia'] ?? 'Estándar') === 'Estándar') ? 'selected' : ''; ?>>Estándar</option>
                <option value="Urgente" <?php echo (($_POST['tipo_urgencia'] ?? '') === 'Urgente') ? 'selected' : ''; ?>>Urgente</option>
            </select>
        </div>
        <br>

        <button type="submit">Crear solicitud</button>
        <a class="boton secundario" href="<?php echo BASE_URL; ?>/?page=mis_avisos">Volver</a>
    </form>
</div>