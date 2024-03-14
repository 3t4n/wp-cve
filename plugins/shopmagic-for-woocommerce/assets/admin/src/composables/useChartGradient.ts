import Color from "color";

export function useChartGradient({ ctx, chartArea }, start = "#50C878") {
  const color = Color(start);
  const gradient = ctx.createLinearGradient(0, chartArea?.top || 0, 0, chartArea?.bottom || 400);
  gradient.addColorStop(0, color.toString());
  gradient.addColorStop(0.5, color.alpha(0.25).toString());
  gradient.addColorStop(1, color.alpha(0).toString());
  return gradient;
}
