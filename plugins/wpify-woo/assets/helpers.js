export const parseDataset = (dataset) => {
  const props = { ...dataset };

  Object.keys(props).forEach((key) => {
    try {
      props[key] = JSON.parse(props[key]);
    } catch (e) {
      if (!Number.isNaN(props[key]) && !Number.isNaN(parseFloat(props[key]))) {
        props[key] = parseFloat(props[key]);
      } else if (['true', 'false'].includes(props[key])) {
        props[key] = Boolean(props[key]);
      } else if (props[key] === 'null') {
        props[key] = null;
      }
    }
  });

  return props;
};

//source: https://stackoverflow.com/questions/35969656/how-can-i-generate-the-opposite-color-according-to-current-color
export const invertColor = (hex, bw) => {
  if (hex.indexOf('#') === 0) {
    hex = hex.slice(1);
  }

  // convert 3-digit hex to 6-digits.
  if (hex.length === 3) {
    hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
  }

  if (hex.length !== 6) {
    return '#000000';
  }

  let r = parseInt(hex.slice(0, 2), 16);
  let g = parseInt(hex.slice(2, 4), 16);
  let b = parseInt(hex.slice(4, 6), 16);

  if (bw) {
    // http://stackoverflow.com/a/3943023/112731
    return (r * 0.299 + g * 0.587 + b * 0.114) > 186
      ? '#000000'
      : '#FFFFFF';
  }

  // invert color components
  r = (255 - r).toString(16);
  g = (255 - g).toString(16);
  b = (255 - b).toString(16);

  // pad each with zeros and return
  return "#" + padZero(r) + padZero(g) + padZero(b);
}