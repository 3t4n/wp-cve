import React, { FC, useState } from "react";
import DragImage from "../images/drag-3.svg";

import "./_drag.scss";

type Props = {
  customClass?: string;
};

const Drag: FC<Props> = ({ customClass }) => {
  return (
    <div
      className={`swptls-drag ${customClass ? ` ${customClass}` : ``}`}
    >
      <img
        src={DragImage}
        alt="Drag Icon"
      />
    </div>
  );
};

export default Drag;
