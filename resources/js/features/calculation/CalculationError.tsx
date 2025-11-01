import { Alert } from 'react-bootstrap';

interface Props {
  error: string;
  onDismiss: () => void;
}

export const CalculationError = ({ error, onDismiss }: Props) => {
  return (
    <Alert variant="danger" className="mt-3" dismissible onClose={onDismiss}>
      <Alert.Heading>Error</Alert.Heading>
      {error}
    </Alert>
  );
};
