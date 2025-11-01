import axios from 'axios';
import { useEffect, useState } from 'react';
import { ConsumptionData, DateDetailData, PriceData } from '../types/dates.types';

export const useDates = () => {
  const [availableDates, setAvailableDates] = useState<string[]>([]);
  const [selectedDate, setSelectedDate] = useState<string | null>(null);
  const [dateDetails, setDateDetails] = useState<DateDetailData | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string>('');

  // Cargar fechas disponibles al inicializar
  useEffect(() => {
    loadAvailableDates();
  }, []);

  // Cargar detalles cuando se selecciona una fecha
  useEffect(() => {
    if (selectedDate) {
      loadDateDetails(selectedDate);
    }
  }, [selectedDate]);

  const loadAvailableDates = async () => {
    setLoading(true);
    setError('');

    try {
      const [consumptionsResponse, pricesResponse] = await Promise.all([
        axios.get<ConsumptionData[]>('/api/consumptions'),
        axios.get<PriceData[]>('/api/prices')
      ]);

      const consumptionDates = consumptionsResponse.data.map(c => c.date);
      const priceDates = pricesResponse.data.map(p => p.date);

      const allDates = [...new Set([...consumptionDates, ...priceDates])];
      const sortedDates = allDates.sort((a, b) => new Date(b).getTime() - new Date(a).getTime());

      setAvailableDates(sortedDates);

      if (sortedDates.length > 0) {
        setSelectedDate(sortedDates[0]);
      }

    } catch (err) {
      setError('Error al cargar las fechas disponibles');
      console.error('Error loading dates:', err);
    } finally {
      setLoading(false);
    }
  };

  const loadDateDetails = async (date: string) => {
    setLoading(true);
    setError('');

    try {
      const [consumptionsResponse, pricesResponse] = await Promise.all([
        axios.get<ConsumptionData[]>('/api/consumptions'),
        axios.get<PriceData[]>('/api/prices')
      ]);

      const consumption = consumptionsResponse.data.find(c => c.date === date);
      const price = pricesResponse.data.find(p => p.date === date);

      setDateDetails({
        date,
        consumption,
        price
      });

    } catch (err) {
      setError('Error al cargar los detalles de la fecha');
      console.error('Error loading date details:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDateSelect = (date: string) => {
    setSelectedDate(date);
  };

  return {
    availableDates,
    selectedDate,
    dateDetails,
    loading,
    error,
    handleDateSelect,
    refreshDates: loadAvailableDates
  };
};
