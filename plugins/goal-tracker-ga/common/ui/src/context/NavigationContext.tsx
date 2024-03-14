import React, { createContext, ReactNode } from 'react';

export interface NavLinkItem {
  path: string;
  label: string;
  default: boolean;
}

interface NavigationProviderProps {
  children: ReactNode;
  navLinks: NavLinkItem[];
}

interface NavigationContextValue {
  navLinks: NavLinkItem[];
}

const defaultValue: NavigationContextValue = {
  navLinks: [],
};

export const NavigationContext =
  createContext<NavigationContextValue>(defaultValue);

export const NavigationProvider: React.FC<NavigationProviderProps> = ({
  children,
  navLinks,
}) => {
  return (
    <NavigationContext.Provider value={{ navLinks }}>
      {children}
    </NavigationContext.Provider>
  );
};
