import {createRoot} from 'react-dom/client'
import ShippingExtensions from './components/ShippingExtensions';

const elem = document.getElementById('shipping-extensions');

const root = createRoot(elem);
root.render(<ShippingExtensions
    {...elem.dataset}
    categories={JSON.parse(elem.dataset.categories)}
    plugins={JSON.parse(elem.dataset.plugins)}
/>);
