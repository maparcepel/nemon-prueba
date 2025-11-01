import axios from 'axios';
import { useState } from 'react';
import { CalculationFormData, CalculationResult, UseCalculationReturn } from '../types/calculation.types';

export const useCalculation = (): UseCalculationReturn => {
  const [formData, setFormData] = useState<CalculationFormData>({
    start_date: '',
    end_date: '',
    formula: '[OMIE_MD] * 1.21'
  });

  const [result, setResult] = useState<CalculationResult | null>(null);
  const [error, setError] = useState<string>('');
  const [loading, setLoading] = useState(false);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!formData.start_date || !formData.end_date || !formData.formula) {
      setError('Todos los campos son obligatorios');
      return;
    }

    setLoading(true);
    setError('');
    setResult(null);

    try {
      const response = await axios.post<CalculationResult>('/api/calculate', formData, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      setResult(response.data);
    } catch (err) {
      if (axios.isAxiosError(err)) {
        if (err.response?.data?.error) {
          setError(err.response.data.error);
        } else if (err.response?.status === 422) {
          // Validation errors
          const validationErrors = err.response.data?.errors;
          if (validationErrors) {
            const firstError = Object.values(validationErrors)[0];
            setError(Array.isArray(firstError) ? firstError[0] : String(firstError));
          } else {
            setError('Datos de entrada no válidos');
          }
        } else {
          setError(`Error del servidor: ${err.response?.status || 'Desconocido'}`);
        }
      } else {
        setError('Error de conexión. Verifica tu red e inténtalo de nuevo.');
      }
    } finally {
      setLoading(false);
    }
  };

  const resetForm = () => {
    setFormData({
      start_date: '',
      end_date: '',
      formula: '[OMIE_MD] * 1.21'
    });
    setResult(null);
    setError('');
  };

  const clearError = () => {
    setError('');
  };

  return {
    formData,
    result,
    error,
    loading,
    handleInputChange,
    handleSubmit,
    resetForm,
    clearError
  };
};
