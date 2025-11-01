export interface CalculationResult {
  price_indexed: number;
  total_consumption: number;
  total_amount: number;
  calculation_details: {
    days_processed: number;
    formula_used: string;
  };
}

export interface CalculationFormData {
  start_date: string;
  end_date: string;
  formula: string;
}

export interface UseCalculationReturn {
  formData: CalculationFormData;
  result: CalculationResult | null;
  error: string;
  loading: boolean;
  handleInputChange: (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => void;
  handleSubmit: (e: React.FormEvent) => Promise<void>;
  resetForm: () => void;
  clearError: () => void;
}
