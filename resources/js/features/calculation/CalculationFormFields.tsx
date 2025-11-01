import { Col, Form, Row } from 'react-bootstrap';
import { CalculationFormData } from './types/calculation.types';

interface Props {
  formData: CalculationFormData;
  onChange: (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => void;
  disabled: boolean;
}

export const CalculationFormFields = ({ formData, onChange, disabled }: Props) => {
  return (
    <>
      <Row>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Fecha de inicio</Form.Label>
            <Form.Control
              type="date"
              name="start_date"
              value={formData.start_date}
              onChange={onChange}
              required
              disabled={disabled}
            />
          </Form.Group>
        </Col>
        <Col md={6}>
          <Form.Group className="mb-3">
            <Form.Label>Fecha de fin</Form.Label>
            <Form.Control
              type="date"
              name="end_date"
              value={formData.end_date}
              onChange={onChange}
              required
              disabled={disabled}
              min={formData.start_date}
            />
          </Form.Group>
        </Col>
      </Row>

      <Form.Group className="mb-3">
        <Form.Label>Fórmula de cálculo</Form.Label>
        <Form.Control
          as="textarea"
          rows={3}
          name="formula"
          value={formData.formula}
          onChange={onChange}
          placeholder="Ejemplo: [OMIE_MD] * 1.21 + 0.05"
          required
          disabled={disabled}
        />
        <Form.Text className="text-muted">
          Usa <code>[OMIE_MD]</code> como placeholder para el precio de mercado.
          Operadores permitidos: +, -, *, /, ( )
        </Form.Text>
      </Form.Group>
    </>
  );
};
