const { generateColorScale } = require('../../utils/tailwind/tailwindColors.js');

const colorPalettes = {
  primary: generateColorScale('#2C5234'),  
  secondary: generateColorScale('#817C33'),
  succes: generateColorScale('#409711'),    
  warning: generateColorScale('#f7d91b'),   
  info: generateColorScale('#3B82F6'),     
  accent: generateColorScale('#F59E0B'),   
  danger: generateColorScale('#EF4444'),   
  neutral: generateColorScale('#6B7280'),
  vianco : generateColorScale('#65A675'),
};
module.exports = {
  ...colorPalettes,
};