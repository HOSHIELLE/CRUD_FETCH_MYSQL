// script.js
// Aquí se maneja toda la lógica del frontend:
// - Eventos del formulario y botones
// - Peticiones al servidor con fetch()
// - Actualización de la tabla de productos

document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del formulario y botones
  const form = document.getElementById("formProducto");
  const btnNuevo = document.getElementById("btnNuevo");
  const btnBuscar = document.getElementById("btnBuscar");
  const btnMostrarTodo = document.getElementById("btnMostrarTodo");

  // Cuando se envía el formulario (clic en Guardar)
  form.addEventListener("submit", (e) => {
    e.preventDefault(); // Evita que la página se recargue

    // Validación simple del navegador (campos required)
    if (!form.checkValidity()) {
      Swal.fire("Validación", "Completa todos los campos.", "warning");
      return;
    }

    // FormData toma todos los campos del formulario
    const formData = new FormData(form);

    // Si hay ID, estamos editando; si no, estamos guardando un nuevo producto
    const accion = document.getElementById("id").value ? "editar" : "guardar";
    formData.append("accion", accion);

    // Enviamos los datos al servidor
    enviarFetch(formData);
  });

  // Botón "Nuevo": limpia el formulario
  btnNuevo.addEventListener("click", () => {
    limpiarFormulario();
  });

  // Botón "Buscar por nombre"
  btnBuscar.addEventListener("click", () => {
    // Tomamos el texto que hay en el campo "producto"
    const nombre = document.getElementById("producto").value.trim();

    if (!nombre) {
      Swal.fire("Atención", "Escribe un nombre de producto para buscar.", "info");
      return;
    }

    // Creamos los datos a enviar al servidor
    const fd = new FormData();
    fd.append("accion", "buscar"); // acción que va a manejar PHP
    fd.append("nombre", nombre);   // texto con el que vamos a filtrar

    enviarFetch(fd);
  });

  // Botón "Mostrar todo": vuelve a cargar la lista completa
  btnMostrarTodo.addEventListener("click", () => {
    listarProductos();
  });

  // Al cargar la página por primera vez, pedimos el listado completo
  listarProductos();
});

// Función general para enviar datos al servidor usando fetch
function enviarFetch(formData) {
  // Leemos la acción para saber cómo procesar la respuesta
  const accion = formData.get("accion");

  fetch("registrar.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json()) // Convertimos la respuesta a JSON
    .then((resp) => {
      // Según la acción, hacemos algo diferente
      switch (accion) {
        case "guardar":
        case "editar":
          if (resp.success) {
            Swal.fire("Éxito", resp.message, "success");
            limpiarFormulario();
            listarProductos(); // recargamos la tabla
          } else {
            mostrarErrores(resp);
          }
          break;

        case "buscar":
          if (resp.success && resp.data) {
            // Mostramos solo los productos que coincidieron
            pintarTabla(resp.data);
            Swal.fire("Coincidencias", resp.message, "success");
          } else {
            Swal.fire("Sin resultados", resp.message, "warning");
          }
          break;

        case "listar":
          if (resp.success) {
            pintarTabla(resp.data);
          }
          break;

        case "eliminar":
          if (resp.success) {
            Swal.fire("Eliminado", resp.message, "success");
            listarProductos();   // recargamos la tabla
            limpiarFormulario(); // por si ese producto estaba cargado en el form
          } else {
            Swal.fire("Error", resp.message || "No se pudo eliminar.", "error");
          }
          break;

        default:
          Swal.fire(
            "Error",
            resp.message || "Acción desconocida en la respuesta.",
            "error"
          );
      }
    })
    .catch(() => {
      // Si hubo un problema con la petición (servidor caído, etc.)
      Swal.fire(
        "Error",
        "No se pudo conectar con el servidor.",
        "error"
      );
    });
}

// Pide al servidor la lista completa de productos
function listarProductos() {
  const fd = new FormData();
  fd.append("accion", "listar");
  enviarFetch(fd);
}

// Dibuja las filas de la tabla con los productos recibidos
function pintarTabla(productos) {
  const tbody = document.getElementById("tbodyProductos");
  tbody.innerHTML = ""; // Limpiamos cualquier fila previa

  productos.forEach((p) => {
    const tr = document.createElement("tr");

    // Cada fila muestra los datos del producto y los botones Editar/Eliminar
    tr.innerHTML = `
      <td>${p.id}</td>
      <td>${p.codigo}</td>
      <td>${p.producto}</td>
      <td>${p.precio}</td>
      <td>${p.cantidad}</td>
      <td class="text-center d-flex gap-2 justify-content-center">
        <button
          class="btn btn-warning btn-warning-custom"
          type="button"
          onclick='cargarEnFormulario(${JSON.stringify(p)})'
        >
          Editar
        </button>

        <button
          class="btn btn-danger btn-danger-custom"
          type="button"
          onclick='eliminarProducto(${p.id})'
        >
          Eliminar
        </button>
      </td>
    `;

    tbody.appendChild(tr);
  });
}

// Carga los datos de un producto en el formulario para poder editarlo
function cargarEnFormulario(p) {
  document.getElementById("id").value = p.id;
  document.getElementById("codigo").value = p.codigo;
  document.getElementById("producto").value = p.producto;
  document.getElementById("precio").value = p.precio;
  document.getElementById("cantidad").value = p.cantidad;
}

// Deja el formulario en blanco para registrar un nuevo producto
function limpiarFormulario() {
  document.getElementById("id").value = "";
  document.getElementById("formProducto").reset();
}

// Muestra el primer error de validación enviado por el servidor
function mostrarErrores(resp) {
  if (resp.errors && Object.keys(resp.errors).length > 0) {
    const primero = Object.values(resp.errors)[0];
    Swal.fire("Validación", primero, "warning");
  } else {
    Swal.fire("Error", resp.message || "Ocurrió un error.", "error");
  }
}

// Elimina un producto por su ID (primero pide confirmación)
function eliminarProducto(id) {
  Swal.fire({
    title: "¿Eliminar producto?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      const fd = new FormData();
      fd.append("accion", "eliminar");
      fd.append("id", id);

      enviarFetch(fd);
    }
  });
}
