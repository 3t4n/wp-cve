import React, { createContext, useContext, useState } from 'react';

export interface Tab {
  name: string | JSX.Element;
  href: string;
  current?: boolean;
  primary?: boolean;
  rootTab?: boolean;
  firstTime?: boolean;
  hasIssue?: boolean;
  isFirstTime?: boolean;
  icon?: JSX.Element;
  showTitle?: boolean;
}

type UseTabsReturn = {
  tabs: Tab[];
  updateTab: (index: number, newTabData: Partial<Tab>) => void;
  updateTabByName: (name: string, newTabData: Partial<Tab>) => void;
  updateHasIssueByName: (name: string, hasIssue: boolean) => void;
};

export const TabContext = createContext<UseTabsReturn | null>(null);

const useTabs = (initialTabs: Tab[]): UseTabsReturn => {
  const [tabs, setTabs] = useState(initialTabs);

  const updateTab = (index: number, newTabData: Partial<Tab>): void => {
    setTabs(prevTabs =>
      prevTabs.map((tab, i) => (i === index ? { ...tab, ...newTabData } : tab)),
    );
  };

  const updateTabByName = (name: string, newTabData: Partial<Tab>): void => {
    setTabs(prevTabs =>
      prevTabs.map(tab =>
        tab.name === name ? { ...tab, ...newTabData } : tab,
      ),
    );
  };

  const updateHasIssueByName = (name: string, hasIssue: boolean): void => {
    setTabs(prevTabs =>
      prevTabs.map(tab => (tab.name === name ? { ...tab, hasIssue } : tab)),
    );
  };

  return { tabs, updateTab, updateTabByName, updateHasIssueByName };
};

type TabContextProviderProps = {
  initialTabs: Tab[];
  children: React.ReactNode;
};

export const TabContextProvider: React.FC<TabContextProviderProps> = ({
  initialTabs,
  children,
}) => {
  const tabManager = useTabs(initialTabs);

  return (
    <TabContext.Provider value={tabManager}>{children}</TabContext.Provider>
  );
};
