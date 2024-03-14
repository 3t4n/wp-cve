const {registerPlugin} = wp.plugins;
import render from './components/Sidebar';

registerPlugin(
    'daexthefu-options',
    {
      icon: false,
      render,
    },
);