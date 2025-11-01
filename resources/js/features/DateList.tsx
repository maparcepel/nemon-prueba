import { Alert, Card, ListGroup, Spinner } from 'react-bootstrap';
import { useDatesContext } from './dates/contexts/DatesContext';

export default function DateList() {
  const {
    availableDates,
    selectedDate,
    loading,
    error,
    handleDateSelect
  } = useDatesContext();

  if (loading && availableDates.length === 0) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Fechas Disponibles</h5>
        </Card.Header>
        <Card.Body className="text-center">
          <Spinner animation="border" size="sm" className="me-2" />
          Cargando fechas...
        </Card.Body>
      </Card>
    );
  }

  if (error) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Fechas Disponibles</h5>
        </Card.Header>
        <Card.Body>
          <Alert variant="danger" className="mb-0">
            {error}
          </Alert>
        </Card.Body>
      </Card>
    );
  }

  if (availableDates.length === 0) {
    return (
      <Card>
        <Card.Header>
          <h5 className="mb-0">Fechas Disponibles</h5>
        </Card.Header>
        <Card.Body>
          <Alert variant="info" className="mb-0">
            No hay fechas disponibles
          </Alert>
        </Card.Body>
      </Card>
    );
  }

  return (
    <Card>
      <Card.Header className="d-flex justify-content-between align-items-center">
        <h5 className="mb-0">Fechas Disponibles</h5>
      </Card.Header>
      <ListGroup variant="flush" style={{ maxHeight: '400px', overflowY: 'auto' }}>
        {availableDates.map((date: string) => (
          <ListGroup.Item
            key={date}
            action
            active={selectedDate === date}
            onClick={() => handleDateSelect(date)}
            className="d-flex justify-content-between align-items-center"
          >
            <div>
              <small className="fw-bold">{date}</small>
            </div>
            {loading && selectedDate === date && (
              <Spinner animation="border" size="sm" />
            )}
          </ListGroup.Item>
        ))}
      </ListGroup>
    </Card>
  );
}
