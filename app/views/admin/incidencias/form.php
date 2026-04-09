<h1><?php echo $incidencia ? 'Editar aviso' : 'Crear aviso manual'; ?></h1>

<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="bloque">
    <form method="POST" action="<?php echo BASE_URL; ?>/?page=<?php echo $incidencia ? 'admin_incidencia_update&id=' . $incidencia['id'] : 'admin_incidencia_store'; ?>">
        <div class="grid-dos">
            <div>
                <label for="cliente_id">Cliente:</label><br>
                <select id="cliente_id" name="cliente_id" required>
                    <option value="">Selecciona un cliente</option>
                    <?php
                    $clienteSeleccionado = $_POST['cliente_id'] ?? ($incidencia['cliente_id'] ?? '');
                    foreach ($clientes as $cliente):
                    ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php echo ((string) $clienteSeleccionado === (string) $cliente['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cliente['nombre'] . ' - ' . $cliente['email']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="tecnico_id">Técnico asignado:</label><br>
                <select id="tecnico_id" name="tecnico_id">
                    <option value="">Sin asignar</option>
                    <?php
                    $tecnicoSeleccionado = $_POST['tecnico_id'] ?? ($incidencia['tecnico_id'] ?? '');
                    foreach ($tecnicos as $tecnico):
                    ?>
                        <option value="<?php echo $tecnico['id']; ?>" <?php echo ((string) $tecnicoSeleccionado === (string) $tecnico['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tecnico['nombre_completo'] . ' - ' . ($tecnico['nombre_especialidad'] ?? 'Sin especialidad')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="especialidad_id">Tipo de servicio:</label><br>
                <select id="especialidad_id" name="especialidad_id" required>
                    <option value="">Selecciona un servicio</option>
                    <?php
                    $especialidadSeleccionada = $_POST['especialidad_id'] ?? ($incidencia['especialidad_id'] ?? '');
                    foreach ($especialidades as $especialidad):
                    ?>
                        <option value="<?php echo $especialidad['id']; ?>" <?php echo ((string) $especialidadSeleccionada === (string) $especialidad['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($especialidad['nombre_especialidad']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="telefono_contacto">Teléfono de contacto:</label><br>
                <input type="text" id="telefono_contacto" name="telefono_contacto" required value="<?php echo htmlspecialchars($_POST['telefono_contacto'] ?? ($incidencia['telefono_contacto'] ?? '')); ?>">
            </div>

            <div>
                <label for="fecha">Fecha:</label><br>
                <input type="date" id="fecha" name="fecha" required value="<?php echo htmlspecialchars($_POST['fecha'] ?? (!empty($incidencia['fecha_servicio']) ? date('Y-m-d', strtotime($incidencia['fecha_servicio'])) : '')); ?>">
            </div>

            <div>
                <label for="franja_horaria">Franja horaria:</label><br>
                <?php $franjaSeleccionada = $_POST['franja_horaria'] ?? ($incidencia['franja_horaria'] ?? '09:00-13:00'); ?>
                <select id="franja_horaria" name="franja_horaria" required>
                    <option value="09:00-13:00" <?php echo $franjaSeleccionada === '09:00-13:00' ? 'selected' : ''; ?>>09:00-13:00</option>
                    <option value="16:00-20:00" <?php echo $franjaSeleccionada === '16:00-20:00' ? 'selected' : ''; ?>>16:00-20:00</option>
                </select>
            </div>

            <div>
                <label for="tipo_urgencia">Urgencia:</label><br>
                <?php $urgenciaSeleccionada = $_POST['tipo_urgencia'] ?? ($incidencia['tipo_urgencia'] ?? 'Estándar'); ?>
                <select id="tipo_urgencia" name="tipo_urgencia" required>
                    <option value="Estándar" <?php echo $urgenciaSeleccionada === 'Estándar' ? 'selected' : ''; ?>>Estándar</option>
                    <option value="Urgente" <?php echo $urgenciaSeleccionada === 'Urgente' ? 'selected' : ''; ?>>Urgente</option>
                </select>
            </div>

            <div>
                <label for="estado">Estado:</label><br>
                <?php $estadoSeleccionado = $_POST['estado'] ?? ($incidencia['estado'] ?? 'Pendiente'); ?>
                <select id="estado" name="estado" required>
                    <option value="Pendiente" <?php echo $estadoSeleccionado === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="Asignada" <?php echo $estadoSeleccionado === 'Asignada' ? 'selected' : ''; ?>>Asignada</option>
                    <option value="Finalizada" <?php echo $estadoSeleccionado === 'Finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                    <option value="Cancelada" <?php echo $estadoSeleccionado === 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
        </div>

        <br>

        <div>
            <label for="direccion">Dirección:</label><br>
            <input type="text" id="direccion" name="direccion" required value="<?php echo htmlspecialchars($_POST['direccion'] ?? ($incidencia['direccion'] ?? '')); ?>">
        </div>

        <br>

        <div>
            <label for="descripcion">Descripción de la avería:</label><br>
            <textarea id="descripcion" name="descripcion" rows="5" required><?php echo htmlspecialchars($_POST['descripcion'] ?? ($incidencia['descripcion'] ?? '')); ?></textarea>
        </div>

        <br>

        <button type="submit"><?php echo $incidencia ? 'Guardar cambios' : 'Crear aviso'; ?></button>
        <a class="boton secundario" href="<?php echo BASE_URL; ?>/?page=admin_incidencias">Volver</a>
    </form>
</div>