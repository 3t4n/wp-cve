import React, { FC } from "react";
import Row from "./../core/Row";
import Column from "./../core/Column";
import Headway from "./../core/Headway";
import Icons from "../icons";

type Props = {
  validation: boolean;
};
const Header: FC<Props> = ({ validation }) => {
  return (
    <div className="ect-app-header">
      <Row>
        <Column xs="12" textSm="left" sm="10">
          <div className="header-content">
            <h1>Easy Cloudflare Turnstile</h1>
            {!validation && (
              <p>Your website is not connected to Cloudflare Turnstile</p>
            )}
          </div>
        </Column>

        <Column xs="12" textSm="right" sm="2">
          <div className="header-top">
            <Headway />
          </div>
        </Column>
      </Row>
    </div>
  );
};

export default Header;
