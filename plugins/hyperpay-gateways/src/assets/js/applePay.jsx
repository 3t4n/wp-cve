
import { Helmet } from "react-helmet";
import { useState, useEffect, useRef } from 'react'
import { clearUpCopyAndPay, generateOptions, useFetch } from './Helpers';



const ApplePay = React.memo((props) => {

    const [checkoutId, setCheckoutId] = useState(null)
    const shippingMethod = useRef(null)

    useEffect(() => {
        useFetch("hyperpay_prepare_checkout" , props.setting.site_url)
        .get("").then(res => {
            setCheckoutId(res.data)
            window.wpwlOptions = generateOptions(props, shippingMethod, res.data);
            })

        return clearUpCopyAndPay()

    }, [])


    return (
        <div>
            {checkoutId && <div>
                <form action="" className="paymentWidgets" data-brands={props.setting.brands}></form>
                <Helmet>
                    <script src={`https://${props.setting.extraScriptData.testMode ? 'eu-test.oppwa.com' : 'eu-prod.oppwa.com'}/v1/paymentWidgets.js?checkoutId=${checkoutId}`}></script>
                </Helmet>
            </div>
            }
        </div>
    )

}, () => true)


export default ApplePay



