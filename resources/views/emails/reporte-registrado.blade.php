<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #1C2B24;">
    <h2>Hola {{ $reporte->dueno->name }},</h2>
    <p>Tu reporte fue registrado exitosamente en RastroPet.</p>

    <table style="border-collapse: collapse; margin-top: 16px;">
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Número de reporte:</td>
            <td style="padding: 6px 12px;">{{ $reporte->numero_reporte }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Mascota:</td>
            <td style="padding: 6px 12px;">{{ $reporte->mascota }} ({{ $reporte->especie }})</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Estado:</td>
            <td style="padding: 6px 12px;">{{ $reporte->estado }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Zona:</td>
            <td style="padding: 6px 12px;">{{ $reporte->zona->nombre }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Fecha de pérdida:</td>
            <td style="padding: 6px 12px;">{{ $reporte->fecha_perdida }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Guarda este número de reporte para dar seguimiento a tu caso.</p>
    <p>¡Gracias por confiar en RastroPet!</p>
</body>
</html>