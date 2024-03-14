import React, { createContext, useContext, useState } from 'react';

interface PromoContextData {
  showPromo: boolean;
  setShowPromo: (showPromo: boolean) => void;
}

const PromoContext = createContext<PromoContextData | undefined>(undefined);

interface PromoContextProviderProps {
  children: React.ReactNode;
}

const PromoContextProvider: React.FC<PromoContextProviderProps> = ({
  children,
}) => {
  const [showPromo, setShowPromo] = useState<boolean>(false);

  return (
    <PromoContext.Provider value={{ showPromo, setShowPromo }}>
      {children}
    </PromoContext.Provider>
  );
};

export function usePromoContext() {
  const context = useContext(PromoContext);
  if (!context) {
    throw new Error(
      'usePromoContext must be used within a PromoContextProvider',
    );
  }
  return context;
}

export { PromoContext, PromoContextProvider };
