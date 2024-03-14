import { createContext, useContext } from 'react';

interface HelpSliderContextProps {
  children?: React.ReactNode;
  open: boolean;
  setOpenHelpSlider: (open: boolean) => void;
  setComponent: (component: React.ComponentType | null) => void;
  setTitleHelpSlider: (title: string) => void;
}

export const HelpSliderContext = createContext<HelpSliderContextProps>({
  open: false, // default value for open
  setOpenHelpSlider: () => {},
  setComponent: () => {},
  setTitleHelpSlider: () => {},
});

export const useHelpSliderContext = () => {
  return useContext(HelpSliderContext);
};
