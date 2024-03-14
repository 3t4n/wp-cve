import React from "react";
import { ctaAddImg, ssgslogo } from "../icons";
import Title from "../core/Title";
import { getStrings } from './../Helpers';

import CtaCorner from "../images/cta-corner.svg";

import "../styles/_ctaAdd.scss";

const CtaAdd = () => {
    return (
        <div className="ctaWrapper">
            <div className="gradient-border">
                <div className="content">
                    <div className="ctaImg">{ctaAddImg}</div>
                    <div className="addInfo">
                        <Title tagName="h4">{getStrings('create-wooCommerce-product')}</Title>
                        <p>
                            <span>{getStrings('install-stock-sync-for-wooCommerce')}</span> {getStrings('and sync your store products')}
                        </p>
                        <a className="btn ctaBtn" href="https://wordpress.org/plugins/stock-sync-with-google-sheet-for-woocommerce/" target="_blank" rel="nofollow">
                            {getStrings('install-now')}
                        </a>
                    </div>
                    <div className="ctaAddlogo">{ssgslogo}</div>
                    <div className="corner-ceal">
                        <img src={CtaCorner} alt="ceal" />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default CtaAdd;
