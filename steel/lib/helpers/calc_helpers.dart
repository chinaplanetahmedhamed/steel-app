double? calculateSqmPerTon({required double widthMm, required double thicknessMm}) {
  if (widthMm == 0 || thicknessMm == 0) return null;
  return 1000000 / (widthMm * thicknessMm * 7.85);
}