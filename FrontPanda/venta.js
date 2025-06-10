$(document).ready(function () {
  function cargarProductos(select) {
    $.get('cargarOpciones.php?tipo=productos', function (data) {
      select.empty();
      select.append('<option value="">Seleccione un producto</option>');
      select.append(data);
    });
  }

  function cargarMediosDePago() {
    $.get('cargarOpciones.php?tipo=mediopago', function (data) {
      console.log("Medios de pago cargados:", data); // <-- Verifica esto en consola
      $('#medioPago').empty();
      $('#medioPago').append('<option value="">Seleccione un medio de pago</option>');
      $('#medioPago').append(data);
    });
  }

  function actualizarTotal() {
    let total = 0;

    $('.productoRow').each(function () {
      const precio = parseFloat($(this).find('.precio').val()) || 0;
      const cantidad = parseInt($(this).find('.cantidad').val()) || 1;
      total += precio * cantidad;
    });

    $('#total').text(total.toFixed(2));
    $('#valorPagado').attr('min', total.toFixed(2));
  }

  // Cargar productos y medios de pago al inicio
  cargarProductos($('.producto').first());
  cargarMediosDePago();

  // Evento al cambiar un producto
  $(document).on('change', '.producto', function () {
    const productoID = $(this).val();
    const row = $(this).closest('.productoRow');
    const precioInput = row.find('.precio');

    if (productoID) {
      $.post('obtener_precio.php', { id_producto: productoID }, function (data) {
        try {
          const producto = JSON.parse(data);
          precioInput.val(producto.precio_venta);
          actualizarTotal();
        } catch (error) {
          console.error("Error al convertir JSON:", error);
          precioInput.val('');
        }
      });
    } else {
      precioInput.val('');
      actualizarTotal();
    }
  });

  // Evento al cambiar la cantidad
  $(document).on('input', '.cantidad', actualizarTotal);

  // Agregar nueva fila de producto
  $('#agregarProducto').click(function () {
    const nuevaFila = $('.productoRow').first().clone();
    nuevaFila.find('select').val('');
    nuevaFila.find('.precio').val('');
    nuevaFila.find('.cantidad').val(1);
    $('#productosContainer').append(nuevaFila);
    cargarProductos(nuevaFila.find('.producto'));
  });

  // Eliminar fila de producto
  $(document).on('click', '.eliminar', function () {
    if ($('.productoRow').length > 1) {
      $(this).closest('.productoRow').remove();
      actualizarTotal();
    }
  });

  // Validar y enviar formulario
  $('#formVenta').submit(function (e) {
    e.preventDefault();

    let total = 0;
    let faltanPrecios = false;

    $('.productoRow').each(function () {
      const precio = parseFloat($(this).find('.precio').val());
      const cantidad = parseInt($(this).find('.cantidad').val());

      if (isNaN(precio) || isNaN(cantidad)) {
        faltanPrecios = true;
      } else {
        total += precio * cantidad;
      }
    });

    if (faltanPrecios) {
      alert('Hay productos sin precio asignado. Verifique la selección.');
      return;
    }

    const valorPagado = parseFloat($('#valorPagado').val());
    if (isNaN(valorPagado)) {
      alert('Ingrese un valor pagado válido.');
      return;
    }

    if (valorPagado < total) {
      alert(`El valor pagado ($${valorPagado}) es menor al total de la venta ($${total})`);
      return;
    }

    $.post('procesarVenta.php', $('#formVenta').serialize(), function (response) {
      $('#mensaje').html(response);
    });
  });
});