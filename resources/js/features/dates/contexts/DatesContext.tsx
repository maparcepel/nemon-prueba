import React, { createContext, ReactNode, useContext } from 'react';
import { useDates } from '../hooks/useDates';

interface DatesContextType {
  availableDates: string[];
  selectedDate: string | null;
  dateDetails: any;
  loading: boolean;
  error: string;
  handleDateSelect: (date: string) => void;
  refreshDates: () => Promise<void>;
}

const DatesContext = createContext<DatesContextType | undefined>(undefined);

interface DatesProviderProps {
  children: ReactNode;
}

export const DatesProvider: React.FC<DatesProviderProps> = ({ children }) => {
  const datesData = useDates();

  return (
    <DatesContext.Provider value={datesData}>
      {children}
    </DatesContext.Provider>
  );
};

export const useDatesContext = (): DatesContextType => {
  const context = useContext(DatesContext);
  if (context === undefined) {
    throw new Error('useDatesContext must be used within a DatesProvider');
  }
  return context;
};
