import React, { useState } from 'react';
import { HelpSliderContext } from './HelpSliderContext';
import HelpSlider from '../components/HelpSlider';

interface HelpSliderProviderProps {
  children: React.ReactNode;
}

export const HelpSliderProvider: React.FC<HelpSliderProviderProps> = ({
  children,
}) => {
  const [open, setOpenHelpSlider] = useState(false);
  const [component, setComponent] = useState<React.ComponentType | null>(null);
  const [title, setTitleHelpSlider] = useState('');
  return (
    <HelpSliderContext.Provider
      value={{ open, setOpenHelpSlider, setComponent, setTitleHelpSlider }}
    >
      {children}
      {component && (
        <HelpSlider
          title={title}
          Component={component}
          open={open}
          setOpen={setOpenHelpSlider}
        />
      )}
    </HelpSliderContext.Provider>
  );
};

export default HelpSliderProvider;
