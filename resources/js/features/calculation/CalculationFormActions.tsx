import { Button, Spinner } from 'react-bootstrap';

interface Props {
  loading: boolean;
  onReset: () => void;
}

export const CalculationFormActions = ({ loading, onReset }: Props) => {
  return (
    <div className="d-flex gap-2">
      <Button
        variant="primary"
        type="submit"
        disabled={loading}
      >
        {loading ? (
          <>
            <Spinner
              as="span"
              animation="border"
              size="sm"
              role="status"
              aria-hidden="true"
              className="me-2"
            />
            Calculando...
          </>
        ) : (
          'Calcular'
        )}
      </Button>

      <Button
        variant="outline-secondary"
        type="button"
        onClick={onReset}
        disabled={loading}
      >
        Limpiar
      </Button>
    </div>
  );
};
