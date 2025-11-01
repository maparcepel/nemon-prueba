import { Alert, Badge, Card, Spinner, Table } from 'react-bootstrap';
import { useDatesContext } from './dates/contexts/DatesContext';
import { HourlyData } from './dates/types/dates.types';

export default function DateDetails() {
  const { selectedDate, dateDetails, loading, error } = useDatesContext();

  const generateHourlyData = (): HourlyData[] => {
    if (!dateDetails) return [];

    const hourlyData: HourlyData[] = [];

    for (let hour = 1; hour <= 25; hour++) {
      const hourKey = `h${hour}`;

      const consumption = dateDetails.consumption
        ? Number((dateDetails.consumption as any)[hourKey]) || 0
        : 0;

      const price = dateDetails.price
        ? Number((dateDetails.price as any)[hourKey]) || 0
        : 0;

      hourlyData.push({
        hour,
        consumption,
        price
      });
    }

    return hourlyData;
  };

  const formatHour = (hour: number): string => {
    if (hour === 25) return '00:00 (día siguiente)';
    return `${hour.toString().padStart(2, '0')}:00`;
  };

  const formatNumber = (value: number, decimals: number = 3): string => {
    return value.toFixed(decimals);
  };

  if (!selectedDate) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Detalles por Hora</h5>
        </Card.Header>
        <Card.Body>
          <Alert variant="info" className="mb-0">
            Selecciona una fecha para ver los detalles
          </Alert>
        </Card.Body>
      </Card>
    );
  }

  if (loading) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Detalles por Hora</h5>
        </Card.Header>
        <Card.Body className="text-center">
          <Spinner animation="border" className="me-2" />
          Cargando detalles para {selectedDate}...
        </Card.Body>
      </Card>
    );
  }

  if (error) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Detalles por Hora</h5>
        </Card.Header>
        <Card.Body>
          <Alert variant="danger" className="mb-0">
            {error}
          </Alert>
        </Card.Body>
      </Card>
    );
  }

  if (!dateDetails) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Detalles por Hora</h5>
        </Card.Header>
        <Card.Body>
          <Alert variant="warning" className="mb-0">
            No hay datos disponibles para la fecha {selectedDate}
          </Alert>
        </Card.Body>
      </Card>
    );
  }

  const hourlyData = generateHourlyData();
  const hasConsumption = !!dateDetails.consumption;
  const hasPrice = !!dateDetails.price;

  return (
    <Card>
      <Card.Header className="d-flex justify-content-between align-items-center">
        <h5 className="mb-0">Detalles por Hora</h5>
        <div>
          <small className="text-muted me-3">Fecha: {selectedDate}</small>
          <Badge bg={hasConsumption ? 'success' : 'secondary'} className="me-1">
            Consumo {hasConsumption ? '✓' : '✗'}
          </Badge>
          <Badge bg={hasPrice ? 'success' : 'secondary'}>
            Precio {hasPrice ? '✓' : '✗'}
          </Badge>
        </div>
      </Card.Header>
      <Card.Body style={{ maxHeight: '500px', overflowY: 'auto' }}>
        <Table striped hover responsive size="sm">
          <thead className="sticky-top bg-light">
            <tr>
              <th style={{ width: '30%' }}>Hora</th>
              <th style={{ width: '35%' }}>Consumo (kWh)</th>
              <th style={{ width: '35%' }}>Precio (€/kWh)</th>
            </tr>
          </thead>
          <tbody>
            {hourlyData.map(({ hour, consumption, price }) => (
              <tr key={hour}>
                <td>
                  <strong>H{hour}</strong>
                  <br />
                  <small className="text-muted">
                    {formatHour(hour)}
                  </small>
                </td>
                <td>
                  <span className={consumption > 0 ? 'text-success' : 'text-muted'}>
                    {formatNumber(consumption)}
                  </span>
                  {consumption > 0 && (
                    <small className="text-muted d-block">
                      kWh
                    </small>
                  )}
                </td>
                <td>
                  <span className={price > 0 ? 'text-primary' : 'text-muted'}>
                    {formatNumber(price, 6)}
                  </span>
                  {price > 0 && (
                    <small className="text-muted d-block">
                      €/kWh
                    </small>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </Table>

        {!hasConsumption && !hasPrice && (
          <Alert variant="warning" className="mt-3">
            No hay datos de consumo ni precio para esta fecha
          </Alert>
        )}

        {(hasConsumption || hasPrice) && (
          <div className="mt-3 text-muted">
            <small>
              • Los valores en 0 indican que no hay datos para esa hora
              <br />
              • H25 corresponde a la hora 00:00 del día siguiente
            </small>
          </div>
        )}
      </Card.Body>
    </Card>
  );
}
