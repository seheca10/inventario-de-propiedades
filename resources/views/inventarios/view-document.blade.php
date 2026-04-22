  <!doctype html>
  <html lang="es-CO">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

      <title>Inventario del arrendatario: {{ $inventario->arrendatario }}</title>
    </head>
    <body>

      <div>

        <div class="py-4">
          <center>
          <img src="https://cartagenanorte.com/wp-content/uploads/2021/07/LOGO-3D-PAGINA-CARTAGENA-NORTE.png" width="25%">

          </center>
        </div>

        <div class="container-title">
          <h2 class="text-center font-weight-bold">INVENTARIO DE INMUEBLE ARRENDADO</h2>
        </div>

      </div>

      <hr>

      <div class="container">
          <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <td class="font-weight-bold" colspan="2">FECHA</td>
                  <td colspan="2">{{ $inventario->created_at->format('Y-m-d') }}</td>
                  <td class="font-weight-bold" colspan="3">CONDOMINIO</td>
                  <td colspan="5">{{ $inventario->condominio }}</td>
                </tr>
                <tr>
                  <td class="font-weight-bold" colspan="2">BLOQUE</td>
                  <td colspan="1">{{ $inventario->bloque }}</td>
                  <td class="font-weight-bold" colspan="2">APARTAMENTO</td>
                  <td colspan="1">{{ $inventario->apartamento }}</td>
                  <td class="font-weight-bold" colspan="2">GARAJE</td>
                  <td colspan="1">{{ $inventario->garaje }}</td>
                  <td class="font-weight-bold" colspan="2">DEPÓSITO</td>
                  <td colspan="1">{{ $inventario->deposito }}</td>
                </tr>
                <tr>
                  <td class="font-weight-bold" colspan="3">ARRENDATARIO</td>
                  <td colspan="3">{{ $inventario->arrendatario  }}</td>
                  <td class="font-weight-bold" colspan="3">ARRENDADOR</td>
                  <td colspan="3">{{ $inventario->arrendador }}</td>
                </tr>
                <tr>
                  <td class="font-weight-bold" colspan="2">ALCOBAS</td>
                  <td colspan="1">{{ $inventario->alcobas }}</td>
                  <td class="font-weight-bold" colspan="1">BAÑOS</td>
                  <td colspan="1">{{ $inventario->banos }}</td>
                  <td class="font-weight-bold" colspan="1">PATIO</td>
                  <td colspan="1">{{ $inventario->patio }}</td>
                  <td class="font-weight-bold" colspan="2">JARDIN</td>
                  <td colspan="1">{{ $inventario->jardin }}</td>
                  <td class="font-weight-bold" colspan="1">MTS</td>
                  <td colspan="1">{{ $inventario->metros }}</td>
                </tr>
              </tbody>
            </table>

            <!-- ENTRADA PRINCIPAL -->

            @include('partials.view-pdf.entrada_principal')

            <!-- SALA COMEDOR HALL BALCON -->

            @include('partials.view-pdf.sala_comedor_hall')

            <!-- ALCOBA PRINCIPAL -->

            @include('partials.view-pdf.alcoba_principal')

            <!-- BAÑO ALCOBA PRINCIPAL -->

            @include('partials.view-pdf.bano_principal')

            <!-- COCINA -->

            @include('partials.view-pdf.cocina')

            <!-- OBSERVACIONES -->
            @if ($inventario->propiedad->observaciones->count() > 0)
              @include('partials.view-pdf.observaciones_propiedad')
            @endif

            <!-- OTRAS HABITACIONES -->

            @include('partials.view-pdf.habitaciones')

            <!-- BAÑOS ADICIONALES -->
            @include('partials.view-pdf.banos_adicionales')

            <!-- FIRMAS -->

            <table class="table table-bordered table-striped">
              <thead>
                  <tr>
                      <th colspan="12" class="text-center">FIRMAS</th>
                  </tr>
              </thead>
              <tbody>
          
                  {{-- Texto legal --}}
                  <tr>
                      <td colspan="12" style="font-size: 12px; text-align: justify;">
                          Las partes expresamente declaran que el inmueble objeto del contrato de arrendamiento
                          ha sido entregado por el arrendador conforme al presente inventario, a la persona
                          delegada por el arrendatario para recibirlo.<br><br>
                          Los arrendatarios se comprometen a conservar y mantener el inmueble y su correspondiente
                          dotación en el mismo estado en que lo reciben, salvo los deterioros naturales originados
                          por el adecuado uso del mismo. El arreglo de los daños resultantes del inadecuado uso
                          o del descuido durante el tiempo de la tenencia serán asumidos por el arrendatario.
                          Si estos arreglos no se realizaran, queda el arrendador autorizado para hacerlos por
                          su cuenta y para cobrar ejecutivamente las sumas correspondientes; para este efecto
                          convienen las partes en que tanto el contrato firmado como las facturas de reparación
                          de daños o de reposición de faltantes, prestarán mérito ejecutivo.<br><br>
                          Para constancia de nuestra conformidad firmamos el presente documento el día
                          <b><u>{{ $inventario->created_at->toDateString() }}</u></b>.
                      </td>
                  </tr>
          
                  {{-- Firmas --}}
                  <tr>
          
                      {{-- Firma Arrendatario --}}
                      <td colspan="6" class="text-center">
                          <p><b>Firma Arrendatario:</b></p>
                          <p style="font-size:12px; color:#555;">{{ $inventario->arrendatario }}</p>
          
                          @if ($inventario->firma && $inventario->firma->firma_arrendatario)
                              @php
                                  $b64Arr = $inventario->firma->firma_arrendatario;
                                  // Si por alguna razón llegó con prefijo, lo limpiamos
                                  $b64Arr = preg_replace('/^data:image\/\w+;base64,/', '', $b64Arr);
                                  // Detectar tipo: PNG por defecto (digital), o según tipo guardado
                                  $tipoArr = $inventario->firma->tipo_firma_arrendatario === 'imagen'
                                      ? 'jpeg'
                                      : 'png';
                              @endphp
                              <img src="data:image/{{ $tipoArr }};base64,{{ $b64Arr }}"
                                  style="max-width: 100%; max-height: 150px; object-fit: contain;"
                                  alt="Firma arrendatario">
                          @else
                              <p style="color:#dc3545; font-size:12px;">— Sin firma —</p>
                          @endif
                      </td>
          
                      {{-- Firma Arrendador --}}
                      <td colspan="6" class="text-center">
                          <p><b>Firma Arrendador:</b></p>
                          <p style="font-size:12px; color:#555;">{{ $inventario->nombre_asesor }}</p>
          
                          @if ($inventario->firma && $inventario->firma->firma_arrendador)
                              @php
                                  $b64Ador = $inventario->firma->firma_arrendador;
                                  $b64Ador = preg_replace('/^data:image\/\w+;base64,/', '', $b64Ador);
                                  $tipoAdor = $inventario->firma->tipo_firma_arrendador === 'imagen'
                                      ? 'jpeg'
                                      : 'png';
                              @endphp
                              <img src="data:image/{{ $tipoAdor }};base64,{{ $b64Ador }}"
                                  style="max-width: 100%; max-height: 150px; object-fit: contain;"
                                  alt="Firma arrendador">
                          @else
                              <p style="color:#dc3545; font-size:12px;">— Sin firma —</p>
                          @endif
                      </td>
          
                  </tr>
          
              </tbody>
          </table>

      </div>

    </body>

    <footer class="text-center py-4">
      <span>2024 &copy; Todos los derechos reservados <b>Cartagena Norte Inmobiliaria</b></span><br>
      <small style="font-size: 13px;">Versión 1.0 | Desarrollador por <u>Sebastian Henao Cardona</u></small>
    </footer>

    <style>
      body{
        font-family: "Montserrat", sans-serif;
      }
      .container-title{
        background: #4f4f4f3b;
        width: 50%;
        display: block;
        margin: auto;
        padding: 10px 20px;
        border-radius: 50px;
      }
    </style>

  </html>