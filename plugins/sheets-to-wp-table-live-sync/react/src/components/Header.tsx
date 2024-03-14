import React from "react";
import { Link } from "react-router-dom";
import Card from "../core/Card";
import Column from "../core/Column";
import Row from "../core/Row";
import { DashboardIcon, Unlock, RedHeart } from "./../icons";

import { isProInstalled, isProActive, getStrings } from "../Helpers";

//styles
import "../styles/_header.scss";
import ChangesLog from "./ChangesLog";

function Header() {
    return (
        <header className="swptls-header-wrap">
            <div className="header-section">
                <Link to="/" className="dashboard-logo">
                    <span className="icon">{DashboardIcon}</span> {getStrings('dashboard')}
                </Link>
                <div className="new-unlock-block">
                    {
                        !isProActive() && (
                            <div className="unlock">
                                <div className="icon">{Unlock}</div>
                                <p><a className="get-ultimate" href="https://go.wppool.dev/KfVZ" target="_blank">{getStrings('get-unlimited-access')}</a></p>
                            </div>
                        )

                    }
                    <ChangesLog />
                </div>
            </div>
        </header>
    );
}

export default Header;
