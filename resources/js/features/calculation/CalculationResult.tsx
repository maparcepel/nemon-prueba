import { Alert, Col, Row } from 'react-bootstrap';
import { CalculationResult as CalculationResultType } from './types/calculation.types';

interface Props {
  result: CalculationResultType;
}

export const CalculationResult = ({ result }: Props) => {
  return (
    <Alert variant="success" className="mt-3">
      <Alert.Heading>Resultado del Cálculo</Alert.Heading>
      <Row>
        <Col md={6}>
          <p><strong>Precio Indexado:</strong> {result.price_indexed.toFixed(6)} €/kWh</p>
          <p><strong>Consumo Total:</strong> {result.total_consumption.toFixed(3)} kWh</p>
        </Col>
        <Col md={6}>
          <p><strong>Importe Total:</strong> {result.total_amount.toFixed(2)} €</p>
          <p><strong>Días Procesados:</strong> {result.calculation_details.days_processed}</p>
        </Col>
      </Row>
      <hr />
      <p className="mb-0">
        <strong>Fórmula utilizada:</strong> <code>{result.calculation_details.formula_used}</code>
      </p>
    </Alert>
  );
};
