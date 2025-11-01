import { Card, Form } from 'react-bootstrap';
import { CalculationError } from './CalculationError';
import { CalculationFormActions } from './CalculationFormActions';
import { CalculationFormFields } from './CalculationFormFields';
import { CalculationResult } from './CalculationResult';
import { useCalculation } from './hooks/useCalculation';

export default function CalculationForm() {
  const {
    formData,
    result,
    error,
    loading,
    handleInputChange,
    handleSubmit,
    resetForm,
    clearError
  } = useCalculation();

  return (
    <Card>
      <Card.Header>
        <h4 className="mb-0">Calculadora de Precios Indexados</h4>
      </Card.Header>
      <Card.Body>
        <Form onSubmit={handleSubmit}>
          <CalculationFormFields
            formData={formData}
            onChange={handleInputChange}
            disabled={loading}
          />

          <CalculationFormActions
            loading={loading}
            onReset={resetForm}
          />
        </Form>

        {error && (
          <CalculationError
            error={error}
            onDismiss={clearError}
          />
        )}

        {result && (
          <CalculationResult result={result} />
        )}
      </Card.Body>
    </Card>
  );
}
