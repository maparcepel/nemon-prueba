import { Head } from '@inertiajs/react';

export default function WelcomeSimple() {
  return (
    <>
      <Head title="Nemon - Calculadora de Energía" />
      <div style={{ padding: '20px', fontFamily: 'Arial, sans-serif' }}>
        <h1>🔌 Nemon - Calculadora de Energía</h1>
        <p>¡Aplicación funcionando con Inertia!</p>

        <div style={{ marginTop: '20px' }}>
          <h3>📊 APIs Disponibles:</h3>
          <ul>
            <li><strong>POST /api/calculate</strong> - Calcular precio de energía</li>
            <li><strong>GET /api/consumptions</strong> - Obtener datos de consumo</li>
            <li><strong>GET /api/prices</strong> - Obtener precios</li>
          </ul>
        </div>

        <div style={{ marginTop: '20px' }}>
          <h3>🔗 Enlaces de Prueba:</h3>
          <ul>
            <li><a href="/debug">Información de debug</a></li>
            <li><a href="/api-test">Test de API</a></li>
            <li><a href="/simple">Ruta simple</a></li>
          </ul>
        </div>

        <div style={{
          marginTop: '30px',
          padding: '15px',
          backgroundColor: '#f0f8ff',
          border: '1px solid #0066cc',
          borderRadius: '5px'
        }}>
          <p><strong>Estado:</strong> ✅ Inertia.js funcionando correctamente</p>
          <p><strong>Framework:</strong> Laravel + React + Inertia</p>
          <p><small>La calculadora completa se activará una vez verificado el funcionamiento básico.</small></p>
        </div>
      </div>
    </>
  );
}
