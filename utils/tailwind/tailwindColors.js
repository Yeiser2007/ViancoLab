const tinycolor = require('tinycolor2');

function generateColorScale(baseColor) {
  if (!tinycolor(baseColor).isValid()) {
    baseColor = '#3B82F6';
  }

  const color = tinycolor(baseColor);
  return {
    50: color.clone().lighten(52).toHexString(),
    100: color.clone().lighten(37).toHexString(),
    200: color.clone().lighten(26).toHexString(),
    300: color.clone().lighten(12).toHexString(),
    400: color.clone().lighten(6).toHexString(),
    500: baseColor,
    600: color.clone().darken(6).toHexString(),
    700: color.clone().darken(12).toHexString(),
    800: color.clone().darken(18).toHexString(),
    900: color.clone().darken(24).toHexString()
  };
}
module.exports = { generateColorScale };    