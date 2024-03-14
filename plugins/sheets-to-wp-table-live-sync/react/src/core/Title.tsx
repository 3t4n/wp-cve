import React from "react";

import "./_title.scss";

const Title = ({ tagName, children }) => {
    const classes = () => {
        let c = `swptls-title ${tagName ? tagName : ""}`;

        return c;
    };

    if (tagName) {
        if (tagName === "h1") {
            return <h1 className={`${classes()}`}>{children}</h1>;
        }

        if (tagName === "h2") {
            return <h2 className={`${classes()}`}>{children}</h2>;
        }

        if (tagName === "h3") {
            return <h3 className={`${classes()}`}>{children}</h3>;
        }

        if (tagName === "h4") {
            return <h4 className={`${classes()}`}>{children}</h4>;
        }

        return <p className={`${classes()}`}>{children}</p>;
    } else {
        return <h1 className={`${classes()}`}>{children}</h1>;
    }
};

export default Title;


