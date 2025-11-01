export interface ConsumptionData {
  id: number;
  date: string;
  h1: number;
  h2: number;
  h3: number;
  h4: number;
  h5: number;
  h6: number;
  h7: number;
  h8: number;
  h9: number;
  h10: number;
  h11: number;
  h12: number;
  h13: number;
  h14: number;
  h15: number;
  h16: number;
  h17: number;
  h18: number;
  h19: number;
  h20: number;
  h21: number;
  h22: number;
  h23: number;
  h24: number;
  h25: number;
  created_at: string;
  updated_at: string;
}

export interface PriceData {
  id: number;
  date: string;
  h1: number;
  h2: number;
  h3: number;
  h4: number;
  h5: number;
  h6: number;
  h7: number;
  h8: number;
  h9: number;
  h10: number;
  h11: number;
  h12: number;
  h13: number;
  h14: number;
  h15: number;
  h16: number;
  h17: number;
  h18: number;
  h19: number;
  h20: number;
  h21: number;
  h22: number;
  h23: number;
  h24: number;
  h25: number;
  created_at: string;
  updated_at: string;
}

export interface DateDetailData {
  date: string;
  consumption?: ConsumptionData;
  price?: PriceData;
}

export interface HourlyData {
  hour: number;
  consumption: number;
  price: number;
}
